<?php
namespace Brightside\Personnel\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor;
use Brightside\Paginatedprocessors\Processing\DataToPaginatedData;

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
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
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
            $processedRecordVariables[$record['uid']] = $this->contentDataProcessor->process(
                $recordContentObjectRenderer,
                $processorConfiguration,
                $processedRecordVariables[$record['uid']]
            );
        }

        //MO: Make default sorting according to manual sorting
        if(
            isset($cObj->data['tx_personnel_orderby'])
            && $cObj->data['CType'] === "personnel_selected"
            && isset($cObj->data['tx_personnel'])
            && $cObj->data['tx_personnel']
        )
        {
            $defaultSorting = array_flip(GeneralUtility::intExplode(",", $cObj->data['tx_personnel']));
            $processedRecordVariablesSortedCleaned = array_filter(array_replace($defaultSorting, $processedRecordVariables), function($item) {
                return !is_int($item);
            });
            if(count($processedRecordVariablesSortedCleaned)){
                $processedRecordVariables = $processedRecordVariablesSortedCleaned;
                unset($processedRecordVariablesSortedCleaned);
            }
        }

        $processedData[$targetVariableName] = $processedRecordVariables;
        $allProcessedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);

        $paginationSettings = $processorConfiguration['pagination.'];
        if ((int)($cObj->stdWrapValue('isActive', $paginationSettings ?? []))) {
          $paginatedData = new DataToPaginatedData();
          $allProcessedData = $paginatedData->getPaginatedData(
              $cObj,
              $contentObjectConfiguration,
              $processorConfiguration,
              $allProcessedData,
              $allProcessedData[$processorConfiguration['as']],
              $processorConfiguration['as']
          );
          return $allProcessedData;
        } else {
          return $allProcessedData;
        }
    }
}
