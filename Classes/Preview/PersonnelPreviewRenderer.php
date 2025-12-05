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
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\ImageService;
use Exception;

class PersonnelPreviewRenderer extends StandardContentPreviewRenderer implements PreviewRendererInterface
{
    private const PERSONNEL_TABLE = 'tx_personnel_domain_model_person';

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

    /**
     * Renders an <img> tag for a given FileReference using ImageService.
     * NOTE: This function is currently unused as the image strip was removed from the preview content.
     */
    private function renderImageTagForPreview(FileReference $fileReference, int $width, string $cropVariant = null, string $alt = ''): string
    {
        $imageService = GeneralUtility::makeInstance(ImageService::class);
        $defaultStyle = 'width: 64px; height: 64px; background: #eee; border: 1px solid #ccc; display: inline-block;';

        try {
            $processingInstructions = [
                'width' => $width,
                'height' => $width, // Fixed height for a square thumbnail
                'crop' => $fileReference->getProperty('crop') ?: null,
                'min' => $width . ',' . $width, // Ensure minimum size for processing
            ];
            
            if ($cropVariant) {
                $processingInstructions['cropVariant'] = $cropVariant;
            }

            $processedImage = $imageService->applyProcessingInstructions($fileReference, $processingInstructions);
            $imageUri = $imageService->getImageUri($processedImage);

            return sprintf(
                '<img src="%s" width="%d" height="%d" alt="%s" style="max-width: 100%%; max-height: 100%%; object-fit: cover;" />',
                htmlspecialchars($imageUri),
                $width,
                $width,
                htmlspecialchars($alt)
            );
        } catch (Exception $e) {
            // Return a visible placeholder with the error message for debugging purposes.
            $fallbackTitle = 'Image Processing Failed. Error: ' . $e->getMessage();
            return '<div title="' . htmlspecialchars($fallbackTitle) . '" style="' . $defaultStyle . '">❌</div>';
        }
    }

    // ------------------------------
    // Preview Rendering Methods
    // ------------------------------

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $record = $item->getRecord();
        $getValue = fn(string $field, mixed $default = null) => $this->getRecordValueSafely($record, $field, $default);

        // Safely retrieve all fields
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
        
        // Pagination fields
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
        
        // 1. Details List (Configuration)
        
        if ($CType === 'personnel_selected' && !empty($personnelRecords)) {
            $personNames = [];
            foreach ($personnelRecords as $person) {
                $name = trim(($person['firstname'] ?? '') . ' ' . ($person['lastname'] ?? ''));
                if ($name === '') {
                    $name = 'Person UID: ' . $person['uid'];
                }
                $personNames[] = $name;
            }
            $value = implode(', ', $personNames);
            $output .= $createDetailLine('Persons:', $value);
        }
        // ----------------------------------------------------
        
        if (!empty($pageTitles)) {
            $value = '<span>' . implode(', ', $pageTitles) . '</span>';
            $content = '<strong style="' . $labelStyle . '">Persons from:</strong>' . $value;
            $output .= '<div>' . $this->linkEditContent($content, $record) . '</div>';
        }
        
        if (!empty($categoryTitles)) {
            $value = implode(', ', $categoryTitles);
            $output .= $createDetailLine('Category filter (ANY):', $value);
        }
        
        if ($orderBy) {
            $output .= $createDetailLine('Order by:', $orderBy);
        }
        
        if ($firstResult) {
            $output .= $createDetailLine('Start from record:', $firstResult);
        }
        
        if ($maxResults) {
            $output .= $createDetailLine('Limit to:', $maxResults);
        }
        
        if ($getValue('tx_personnel_template')) {
            $output .= $createDetailLine('Layout:', $getValue('tx_personnel_template'));
        }
        
        if ($getValue('tx_personnel_titlewrap')) {
            $output .= $createDetailLine('Name wrap:', $getValue('tx_personnel_titlewrap'));
        }
        
        if ($disableImages) {
            $output .= $createDetailLine('Images:', 'disabled');
        } else {
            $output .= $createDetailLine('Images:', 'enabled');
            if ($cropRatio) {
                $output .= $createDetailLine('Image crop:', $cropRatio);
            }
        }
        
        if (!$disableInformation) { 
            $output .= $createDetailLine('Information:', 'enabled');
        } else {
            $output .= $createDetailLine('Information:', 'disabled');
        }
        
        if (!$disableVcard) { 
            $output .= $createDetailLine('vCard:', 'enabled');
        } else {
            $output .= $createDetailLine('vCard:', 'disabled');
        }
        
        // Pagination
        if ($paginationEnabled) {
            $paginationContent = '<br /><strong>Pagination:</strong> active';

            if ($itemsPerPage) {
                $paginationContent .= ' •&nbsp;items per page: ' . htmlspecialchars($itemsPerPage);
            }
            if ($pageLinksShown) {
                $paginationContent .= ' •&nbsp;page links shown: ' . htmlspecialchars($pageLinksShown);
            }
            
            if (is_numeric($anchorId) && (int)$anchorId > 0) { 
                $paginationContent .= ' •&nbsp;focus on page change: ' . htmlspecialchars($anchorId);
            } else {
                if ($anchor) {
                    $paginationContent .= ' •&nbsp;focus self on page change';
                }
            }
            if ($urlSegment) {
                $paginationContent .= ' •&nbsp;anchor: ' . htmlspecialchars($urlSegment);
            }
            
            $output .= '<div>' . $this->linkEditContent($paginationContent, $record) . '</div>';
        }

        $output .= '</div>';

        return $output;
    }
}