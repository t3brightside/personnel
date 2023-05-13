<?php
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

$extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
$personnelConiguration = $extensionConfiguration->get('personnel');

$tempColumns = array(
    'tx_personnel_authors' => [
        'exclude' => 1,
        'label' => 'Author',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'tx_personnel_domain_model_person',
            'foreign_table_where' => 'AND tx_personnel_domain_model_person.sys_language_uid IN (-1,0)',
            'size' => '3',
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ]
    ],
);

ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $tempColumns, 1);

if ($personnelConiguration['personnelEnableNewsAuthors']) {
    ExtensionManagementUtility::addToAllTCAtypes(
        'tx_news_domain_model_news',
        'tx_personnel_authors',
        '',
        'after:bodytext'
    );
}
