<?php
defined('TYPO3_MODE') or die();

// $pluginSignature = str_replace('_', '', $_EXTKEY) . '_show';
// $TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personnel_domain_model_person');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_personnel_domain_model_person');