<?php
defined('TYPO3_MODE') or die();
(function () {
  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personnel_domain_model_person');
  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_personnel_domain_model_person');
})();
