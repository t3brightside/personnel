<?php
namespace Brightside\Personnel\DataProcessing;

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

use TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

/**
 * Fetch records from the database, using the default .select syntax from TypoScript.
 *
 * This way, e.g. a FLUIDTEMPLATE cObject can iterate over the array of records.
 *
 * Example TypoScript configuration:
 *
 * 10 = TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor
 * 10 {
 *   table = tt_address
 *   pidInList = 123
 *   where = company="Acme" AND first_name="Ralph"
 *   order = RAND()
 *   as = addresses
 *   dataProcessing {
 *     10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
 *     10 {
 *       references.fieldName = image
 *     }
 *   }
 * }
 *
 * where "as" means the variable to be containing the result-set from the DB query.
 */
class PersonnelDatabaseQueryProcessor extends DatabaseQueryProcessor
{
    /**
     * Fetches records from the database as an array
     *
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     *
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
            return $processedData;
        }

        // the table to query, if none given, exit
        $tableName = $cObj->stdWrapValue('table', $processorConfiguration);
        if (empty($tableName)) {
            return $processedData;
        }
        if (isset($processorConfiguration['table.'])) {
            unset($processorConfiguration['table.']);
        }
        if (isset($processorConfiguration['table'])) {
            unset($processorConfiguration['table']);
        }

        // The variable to be used within the result
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'records');

        //MO: new sorting provided by tx_personnel_orderby field
        if(
            isset($cObj->data['tx_personnel_orderby'])
            && $cObj->data['tx_personnel_orderby'] !== ""
            && $cObj->data['tx_personnel_orderby'] !== "0"
        ) {
            $processorConfiguration['orderBy'] = $cObj->data['tx_personnel_orderby'];
        }

        // Execute a SQL statement to fetch the records
        $records = $cObj->getRecords($tableName, $processorConfiguration);

        $processedRecordVariables = [];
        foreach ($records as $key => $record) {
            /** @var ContentObjectRenderer $recordContentObjectRenderer */
            $recordContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $recordContentObjectRenderer->start($record, $tableName);
            $processedRecordVariables[$record['uid']] = ['data' => $record];
            $processedRecordVariables[$record['uid']] = $this->contentDataProcessor->process($recordContentObjectRenderer, $processorConfiguration, $processedRecordVariables[$record['uid']]);
        }

        //MO: Make default sorting according to manual sorting
        if(
            isset($cObj->data['tx_personnel_orderby'])
            && $cObj->data['tx_personnel_orderby'] === "0"
            && isset($cObj->data['tx_personnel'])
            && $cObj->data['tx_personnel'])
        {
            $defaultSorting = array_flip(GeneralUtility::intExplode(",", $cObj->data['tx_personnel']));
            $processedRecordVariablesSorted = array_replace($defaultSorting, $processedRecordVariables);
            if(count($processedRecordVariablesSorted)){
                $processedRecordVariables = $processedRecordVariablesSorted;
                unset($processedRecordVariablesSorted);
            }
        }

        $processedData[$targetVariableName] = $processedRecordVariables;

        $allProcessedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);
        $paginationSettings = $processorConfiguration['pagination.'];
        $paginationIsActive = (int)($cObj->stdWrapValue('isActive', $paginationSettings ?? []));
        if ($paginationIsActive) {


          $uniquePaginatorId = $cObj->stdWrapValue('uniqueId', $paginationSettings ?? []);
          $uniquePaginatorIdKey = $cObj->getRequest()->getQueryParams()['paginatorId'];

          $uniquePaginatorName = $cObj->stdWrapValue('uniquePaginatorName', $paginationSettings ?? []);
          $uniquePaginatorNameKey = $cObj->getRequest()->getQueryParams()['paginatorName'];


          if(($uniquePaginatorId == $uniquePaginatorIdKey) OR ($uniquePaginatorId == $uniquePaginatorNameKey)) {
            $currentPage = (int)$cObj->getRequest()->getQueryParams()['paginationPage'] ? : 1;
          }
            else {
            $currentPage = 1;
          }
          $uniquePaginatorName = $paginationSettings['uniquePaginatorName'] ? : 0;
          if ($uniquePaginatorName) {
            $paginationArray = 'pagination_' . $uniquePaginatorId;
          } else {
            $paginationArray = 'pagination';
          }
          $itemsToPaginate = $allProcessedData[$processorConfiguration['as']];
          $itemsPerPage = (int)($cObj->stdWrapValue('itemsPerPage', $paginationSettings ?? [])) ? : 10;
          $paginator = new ArrayPaginator($itemsToPaginate, $currentPage, $itemsPerPage);
          $pagination = new SimplePagination($paginator);
          $allProcessedData = array_diff_key($allProcessedData, array_flip([$processorConfiguration['as']]));
          $paginatedData = array(
          $processorConfiguration['as'] => $paginator->getPaginatedItems(),
            $paginationArray => array(
              'uniqueId' => $uniquePaginatorId,
              'numberOfPages' => $paginator->getNumberOfPages(),
              'currentPageNumber' => $paginator->getCurrentPageNumber(),
              'keyOfFirstPaginatedItem' => $paginator->getKeyOfFirstPaginatedItem(),
              'keyOfLastPaginatedItem' => $paginator->getKeyOfLastPaginatedItem(),
              'allPageNumbers' => $pagination->getAllPageNumbers(),
              'previousPageNumber' => $pagination->getPreviousPageNumber(),
              'nextPageNumber' => $pagination->getNextPageNumber(),
              'uniquePaginatorName' => $uniquePaginatorName
            )
          );
          $allProcessedData = array_merge($allProcessedData, $paginatedData);
          return $allProcessedData;
        } else {
          return $allProcessedData;
        }
    }
}
