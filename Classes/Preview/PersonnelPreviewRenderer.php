<?php

declare(strict_types=1);

namespace Brightside\Personnel\Preview;

use TYPO3\CMS\Backend\Preview\PreviewRendererInterface;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Collection\LazyRecordCollection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PersonnelPreviewRenderer extends StandardContentPreviewRenderer implements PreviewRendererInterface
{
    private const PERSONNEL_TABLE = 'tx_personnel_domain_model_person';
    private const TT_CONTENT_TABLE = 'tt_content'; // Added constant

    /**
     * Safely retrieves a field value from the record, supporting both array (v12)
     * and RecordInterface (v14+) access.
     */
    private function getRecordValueSafely(array|RecordInterface $record, string $field, mixed $default = null): mixed
    {
        if (is_array($record)) {
            return $record[$field] ?? $default;
        } elseif ($record instanceof RecordInterface) {
            return $record->has($field) ? $record->get($field) : $default;
        }
        return $default;
    }

    /**
     * Checks if a field is available for editing based on permissions AND TSconfig overrides (User/Page).
     */
    private function isFieldAvailableForRecord(array|RecordInterface $record, string $fieldName): bool
    {
        // 1. Check if the field exists in tt_content columns array (TCA structure check)
        if (!isset($GLOBALS['TCA'][self::TT_CONTENT_TABLE]['columns'][$fieldName])) {
            return false;
        }
        
        // 2. Permission Check (non_exclude_fields)
        if (!isset($GLOBALS['BE_USER']) || !$GLOBALS['BE_USER']->check('non_exclude_fields', self::TT_CONTENT_TABLE . ':' . $fieldName)) {
            return false;
        }

        // --- 3. TSconfig Removal/Disabled Check (User + Page) ---
        
        $pid = $this->getRecordValueSafely($record, 'pid', 0);
        $CType = $this->getRecordValueSafely($record, 'CType', '');

        // Fetch both configurations (using documented API)
        $userTsConfig = $GLOBALS['BE_USER']->getTSConfig();
        $pageTsConfig = $pid > 0 ? BackendUtility::getPagesTSconfig((int)$pid) : [];
        
        // Define all places where the field config might live:
        $tsConfigPaths = [
            // [Table/Field config array, CType specific config array]
            [
                $userTsConfig['TCEFORM.'][self::TT_CONTENT_TABLE . '.'][$fieldName . '.'] ?? [],
                $userTsConfig['TCEFORM.'][self::TT_CONTENT_TABLE . '.'][$fieldName . '.']['types.'][$CType . '.'] ?? [],
            ],
            [
                $pageTsConfig['TCEFORM.'][self::TT_CONTENT_TABLE . '.'][$fieldName . '.'] ?? [],
                $pageTsConfig['TCEFORM.'][self::TT_CONTENT_TABLE . '.'][$fieldName . '.']['types.'][$CType . '.'] ?? [],
            ],
        ];

        foreach ($tsConfigPaths as [$globalConfig, $typeConfig]) {
            // Check 3a: Global Field Rule (TCEFORM.tt_content.field.disabled = 1)
            $isDisabledGlobal = (bool)($globalConfig['disabled'] ?? false);
            if ($isDisabledGlobal) {
                return false;
            }

            // Check 3b: Conditional Type Rule (TCEFORM.tt_content.field.types.CType.disabled = 1)
            $isDisabledType = (bool)($typeConfig['disabled'] ?? false);
            if ($isDisabledType) {
                return false;
            }

            // Check 3c: Explicit Removal (TCEFORM.field.removeItems = fieldname)
            $removeItems = $globalConfig['removeItems'] ?? '';
            if (GeneralUtility::inList($removeItems, $fieldName)) {
                return false;
            }
        }
        
        // If all checks pass, the field is available for viewing/editing.
        return true;
    }

    /**
     * Extract UIDs from a field that could be a string CSV (v12) or LazyRecordCollection (v14).
     */
    private function extractUids(mixed $field): array
    {
        if (!$field) return [];

        if (is_string($field)) {
            return GeneralUtility::intExplode(',', $field, true);
        }

        if ($field instanceof LazyRecordCollection) {
            $uids = [];
            foreach ($field as $record) {
                if (is_object($record) && method_exists($record, 'has') && $record->has('uid')) {
                    $uids[] = (int)$record->get('uid');
                }
            }
            return $uids;
        }
        
        if (is_array($field)) {
            return array_filter(array_map('intval', $field));
        }

        return [];
    }
    
    /**
     * Fetches titles for the given page UIDs.
     */
    private function getPageTitles(mixed $pages): array
    {
        $pageIds = $this->extractUids($pages);
        if (empty($pageIds)) return [];

        $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
        $titles = [];
        foreach ($pageIds as $uid) {
            $page = $pageRepository->getPage((int)$uid);
            if ($page && isset($page['title'])) {
                $titles[] = $page['title'];
            }
        }
        return $titles;
    }

    /**
     * Fetches category records for the given UIDs.
     */
    private function getCategories(mixed $categories): array
    {
        $categoryIds = $this->extractUids($categories);
        if (empty($categoryIds)) return [];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');

        return $queryBuilder
            ->select('uid', 'title')
            ->from('sys_category')
            ->where($queryBuilder->expr()->in('uid', $categoryIds))
            ->executeQuery()
            ->fetchAllAssociative() ?: [];
    }

    // ------------------------------
    // Preview Rendering Methods
    // ------------------------------

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $record = $item->getRecord();
        $getValue = fn(string $field, mixed $default = null) => $this->getRecordValueSafely($record, $field, $default);
        
        // Helper function to check field availability
        $isFieldAvailable = fn(string $field) => $this->isFieldAvailableForRecord($record, $field);

        $CType = $getValue('CType', '');
        $pids = $getValue('pages');
        $orderBy = $getValue('tx_personnel_orderby');
        $maxResults = $getValue('tx_personnel_limit');
        $firstResult = $getValue('tx_personnel_startfrom');
        $selectedCategories = $getValue('selected_categories');
        $selectedRecords = $getValue('tx_personnel');
        
        $disableImages = (bool)$getValue('tx_personnel_images', 0);
        $cropRatio = $getValue('tx_personnel_cropratio');
        $disableInformation = (bool)$getValue('tx_personnel_information', 0);
        $disableVcard = (bool)$getValue('tx_personnel_vcard', 0);
        
        $paginationEnabled = (bool)$getValue('tx_paginatedprocessors_paginationenabled', 0);
        $itemsPerPage = $getValue('tx_paginatedprocessors_itemsperpage');
        $pageLinksShown = $getValue('tx_paginatedprocessors_pagelinksshown');
        $anchor = $getValue('tx_paginatedprocessors_anchor');
        $anchorId = $getValue('tx_paginatedprocessors_anchorid');
        $urlSegment = $getValue('tx_paginatedprocessors_urlsegment');
        
        // --- Data Fetching & Query Execution Setup ---
        
        $pageTitles = $this->getPageTitles($pids);
        $categoryRecords = $this->getCategories($selectedCategories);
        $categoryTitles = array_column($categoryRecords, 'title');
        
        $personnelRecords = [];
        $query = null;
        $selectedRecordUids = $this->extractUids($selectedRecords);
        $pidUids = $this->extractUids($pids);
        $categoryUids = $this->extractUids($selectedCategories);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::PERSONNEL_TABLE);

        // --- Query Construction and Execution (Simplified) ---

        if ($CType === 'personnel_selected' && !empty($selectedRecordUids)) {
            $query = $queryBuilder
                ->select('uid', 'firstname', 'lastname')
                ->from(self::PERSONNEL_TABLE)
                ->where($queryBuilder->expr()->in('uid', $selectedRecordUids));
        } elseif ($CType === 'personnel_frompages' && !empty($pidUids)) {
            $query = $queryBuilder
                ->selectLiteral(self::PERSONNEL_TABLE . '.*')
                ->from(self::PERSONNEL_TABLE);

            $constraints = [$queryBuilder->expr()->in(self::PERSONNEL_TABLE . '.pid', $pidUids)];

            if (!empty($categoryUids)) {
                $query->leftJoin(
                    self::PERSONNEL_TABLE, 
                    'sys_category_record_mm', 
                    'category_mm',
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->eq(self::PERSONNEL_TABLE . '.uid', $queryBuilder->quoteIdentifier('category_mm.uid_foreign')),
                        $queryBuilder->expr()->eq('category_mm.tablenames', $queryBuilder->createNamedParameter(self::PERSONNEL_TABLE)),
                        $queryBuilder->expr()->eq('category_mm.fieldname', $queryBuilder->createNamedParameter('categories'))
                    )
                );
                $constraints[] = $queryBuilder->expr()->in('category_mm.uid_local', $categoryUids);
            }

            $query->where($queryBuilder->expr()->and(...$constraints));
            $query->groupBy(self::PERSONNEL_TABLE . '.uid');

            if (!empty($orderBy)) {
                [$column, $direction] = GeneralUtility::trimExplode(' ', $orderBy, true, 2);
                if (!empty($column)) {
                    $query->orderBy($column, $direction);
                }
            }
            if (!empty($maxResults)) {
                $query->setMaxResults((int)$maxResults);
            }
            if (!empty($firstResult)) {
                $query->setFirstResult((int)$firstResult);
            }
        }
        
        if ($query instanceof QueryBuilder) {
            $personnelRecords = $query->executeQuery()->fetchAllAssociative();
        }

        // Reorder array for 'personnel_selected'
        if ($CType === 'personnel_selected' && !empty($selectedRecordUids)) {
             $personnelRecordsReindex = [];
            foreach ($personnelRecords as $person) {
                $personnelRecordsReindex[$person['uid']] = $person;
            }
            $defaultSorting = array_flip($selectedRecordUids);
            $personnelRecordsSortedCleaned = array_filter(
                array_replace($defaultSorting, $personnelRecordsReindex), 
                fn($item) => !is_int($item) 
            );
            $personnelRecords = array_values($personnelRecordsSortedCleaned);
        }

        // --- HTML Output Generation ---

        $output = '<div class="element-preview-content">';
        
        // Define common styling for labels
        $labelStyle = 'display: inline-block; min-width: 200px; font-weight: bold;';

        // Helper function to create an HTML detail line wrapped in the edit link
        $createDetailLine = function (string $label, string $value) use ($record, $labelStyle): string {
            $content = '<strong style="' . $labelStyle . '">' . htmlspecialchars($label) . '</strong>' . htmlspecialchars($value);
            return '<div>' . $this->linkEditContent($content, $record) . '</div>';
        };
      
        if ($CType === 'personnel_selected' && $isFieldAvailable('tx_personnel') && !empty($personnelRecords)) {
            $personNames = [];
            foreach ($personnelRecords as $person) {
                $name = trim(($person['firstname'] ?? '') . ' ' . ($person['lastname'] ?? ''));
                if ($name === '') {
                    $name = 'Person UID: ' . $person['uid'];
                }
                $personNames[] = $name;
            }
            $value = implode(', ', $personNames);
            $output .= $createDetailLine('Selected Persons:', $value);
        }
        
        if ($isFieldAvailable('pages') && !empty($pageTitles)) {
            $value = '<span>' . implode(', ', $pageTitles) . '</span>';
            $content = '<strong style="' . $labelStyle . '">Persons from:</strong>' . $value;
            $output .= '<div>' . $this->linkEditContent($content, $record) . '</div>';
        }
        
        if ($isFieldAvailable('selected_categories') && !empty($categoryTitles)) {
            $value = implode(', ', $categoryTitles);
            $output .= $createDetailLine('Category filter (ANY):', $value);
        }
        
        if ($isFieldAvailable('tx_personnel_orderby') && $orderBy) {
            $output .= $createDetailLine('Order by:', $orderBy);
        }
        
        if ($isFieldAvailable('tx_personnel_startfrom') && $firstResult) {
            $output .= $createDetailLine('Start from record:', $firstResult);
        }
        
        if ($isFieldAvailable('tx_personnel_limit') && $maxResults) {
            $output .= $createDetailLine('Limit to:', $maxResults);
        }
        
        if ($isFieldAvailable('tx_personnel_template') && $getValue('tx_personnel_template')) {
            $output .= $createDetailLine('Layout:', $getValue('tx_personnel_template'));
        }
        
        if ($isFieldAvailable('tx_personnel_titlewrap') && $getValue('tx_personnel_titlewrap')) {
            $output .= $createDetailLine('Name wrap:', $getValue('tx_personnel_titlewrap'));
        }
        
        if ($isFieldAvailable('tx_personnel_images')) {
            if ($disableImages) {
                $output .= $createDetailLine('Images:', 'disabled');
            } else {
                $output .= $createDetailLine('Images:', 'enabled');
                
                if ($isFieldAvailable('tx_personnel_cropratio') && $cropRatio) {
                    $output .= $createDetailLine('Image crop:', $cropRatio);
                }
            }
        }
        
        if ($isFieldAvailable('tx_personnel_information')) {
            if (!$disableInformation) { 
                $output .= $createDetailLine('Information:', 'enabled');
            } else {
                $output .= $createDetailLine('Information:', 'disabled');
            }
        }
        
        if ($isFieldAvailable('tx_personnel_vcard')) {
            if (!$disableVcard) { 
                $output .= $createDetailLine('vCard:', 'enabled');
            } else {
                $output .= $createDetailLine('vCard:', 'disabled');
            }
        }
            
        $isPaginationConfigAvailable = $isFieldAvailable('tx_paginatedprocessors_paginationenabled');

        if ($paginationEnabled && $isPaginationConfigAvailable) {
            
            $paginationContent = '<br /><strong>Pagination:</strong> active';

            if ($isFieldAvailable('tx_paginatedprocessors_itemsperpage') && $itemsPerPage) {
                $paginationContent .= ' •&nbsp;items per page: ' . htmlspecialchars($itemsPerPage);
            }
            if ($isFieldAvailable('tx_paginatedprocessors_pagelinksshown') && $pageLinksShown) {
                $paginationContent .= ' •&nbsp;page links shown: ' . htmlspecialchars($pageLinksShown);
            }
            
            if ($isFieldAvailable('tx_paginatedprocessors_anchorid') && is_numeric($anchorId) && (int)$anchorId > 0) {
                $paginationContent .= ' •&nbsp;focus on page change: ' . htmlspecialchars($anchorId);
            } else {
                if ($isFieldAvailable('tx_paginatedprocessors_anchor') && $anchor) {
                    $paginationContent .= ' •&nbsp;focus self on page change';
                }
            }
            if ($isFieldAvailable('tx_paginatedprocessors_urlsegment') && $urlSegment) {
                $paginationContent .= ' •&nbsp;anchor: ' . htmlspecialchars($urlSegment);
            }

            $output .= '<div>' . $this->linkEditContent($paginationContent, $record) . '</div>';
        }

        $output .= '</div>';
        return $output;
    }
}