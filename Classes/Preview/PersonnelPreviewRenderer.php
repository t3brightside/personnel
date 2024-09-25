<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Brightside\Personnel\Preview;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\MathUtility;

class PersonnelPreviewRenderer extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(
            'EXT:personnel/Resources/Private/Templates/Backend/Preview.html',
        );

        $record = $item->getRecord();
        $view->assign('personnelitem', $record);

        // Initialize an array to store personnel records
        $personnelRecords = [];

        $CType = $record['CType'];
        $pids = $record['pages'];
        $orderBy = !empty($record['tx_personnel_orderby']) ? $record['tx_personnel_orderby'] : null;
        $maxResults = !empty($record['tx_personnel_limit']) ? intval($record['tx_personnel_limit']) : null;
        $firstResult = !empty($record['tx_personnel_startfrom']) ? intval($record['tx_personnel_startfrom']) : null;
        $selectedCategories = $record['selected_categories'];
        $selectedRecords = $record['tx_personnel'];

        // Query for selected personnel records
        if ($CType == 'personnel_selected' && $selectedRecords) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_personnel_domain_model_person');
            $query = $queryBuilder
                ->select('*')
                ->from('tx_personnel_domain_model_person')
                ->where(
                    $queryBuilder->expr()->in('uid', $selectedRecords)
                );
        }

        // Query for personnel records from selected pages/sysfolders

        if ($CType == 'personnel_frompages' && $pids) {
            // Get titles of selected startingpoints
            if ($record['pages']) {
                $pageIds = explode(',', $record['pages']);
                $pageIds = array_map('intval', $pageIds);
                $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
                $pageTitles = [];
                foreach ($pageIds as $pageUid) {
                    $pageData = $pageRepository->getPage($pageUid);
                    if ($pageData && isset($pageData['title'])) {
                        $pageTitles[] = $pageData['title'];
                    }
                }
                $view->assign('pageTitles', $pageTitles);
            }


            // Get selected catefories
            if ($selectedCategories) {
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_personnel_domain_model_person');
                $query = $queryBuilder
                    ->select('title')
                    ->from('sys_category')
                    ->where(
                        $queryBuilder->expr()->in('uid', $selectedCategories)
                    );
                $queryResult = $query->executeQuery();
                $cetegoryTitles = $queryResult->fetchAllAssociative();
                $view->assign('catTitles', $cetegoryTitles);
            }

            // Fetch personnel records with the given page UID
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_personnel_domain_model_person');
            $query = $queryBuilder
                ->select('tx_personnel_domain_model_person.*')
                ->from('tx_personnel_domain_model_person')
                ->leftJoin(
                    'tx_personnel_domain_model_person',
                    'sys_category_record_mm',
                    'category_mm',
                    $queryBuilder->expr()->eq('tx_personnel_domain_model_person.uid', 'category_mm.uid_foreign')
                );
            // Select only from certain categories
            if ($selectedCategories) {
                $query->where(
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->in('tx_personnel_domain_model_person.pid', $pids),
                        $queryBuilder->expr()->in('category_mm.uid_local', $selectedCategories)
                    )
                );
            } else {
                $query->where(
                    $queryBuilder->expr()->in('tx_personnel_domain_model_person.pid', $pids)
                );
            }

            $query->groupBy('tx_personnel_domain_model_person.uid');

            if($orderBy) {
                [$column, $direction] = explode(' ', $orderBy, 2);
                $query->orderBy($column,$direction);
            }

            if($maxResults) {
                $query->setMaxResults($maxResults);
            }

            if($firstResult) {
                $query->setFirstResult($firstResult);
            }
        }

        // Query execution

        if ($CType == 'personnel_frompages' && $pids){
            $query->executeQuery();
            $queryResult = $query->executeQuery();
            $personnelRecords = $queryResult->fetchAllAssociative();
        }

        // Reorder array to sort by selected records order
        if (
            $CType == 'personnel_selected' &&
            $selectedRecords
        ) {
            // Query execution
            $query->executeQuery();
            $queryResult = $query->executeQuery();
            $personnelRecords = $queryResult->fetchAllAssociative();

            $personnelRecordsReindex = [];
            foreach ($personnelRecords as $item) {
                $personnelRecordsReindex[$item['uid']] = $item;
            }
            $defaultSorting = array_flip(GeneralUtility::intExplode(",", $selectedRecords));
            $personnelRecordsSortedCleaned = array_filter(array_replace($defaultSorting, $personnelRecordsReindex), function($item) {
                return !is_int($item);
            });
            if(count($personnelRecordsSortedCleaned)){
                $personnelRecords = $personnelRecordsSortedCleaned;
                unset($personnelRecordsReindex,$personnelRecordsSortedCleaned);
            }
        }

        // Get images for persons
        foreach ($personnelRecords as &$personnelRecord) {
            if (!empty($personnelRecord['images'])) {
                $fileReferences = BackendUtility::resolveFileReferences('tx_personnel_domain_model_person', 'images', $personnelRecord);
                // Limit to the first image only
                if (!empty($fileReferences)) {
                    $firstImageReference = reset($fileReferences); // Get the first element of the array
                    $personnelRecord['resolvedImages'] = [$firstImageReference]; // Store in a new array
                }
            }
        }

        // Assign to template
        if ($selectedRecords || $pids){
            $view->assign('personnelRecords', $personnelRecords);
        }
        $out = $view->render();
        return $this->linkEditContent($out, $record);
    }
}
