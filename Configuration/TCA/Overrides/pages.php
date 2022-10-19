<?php
defined('TYPO3_MODE') || defined('TYPO3') || die('Access denied.');

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
$personnelConiguration = $extensionConfiguration->get('personnel');

$tempColumns = array(
    'tx_personnel_authors' => [
        'exclude' => 1,
        'label' => 'Author / Contact person',
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

ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns, 1);

if ($personnelConiguration['personnelEnableAuthors']) {
    ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '--palette--;Personnel;personnelcontact',
        '1',
        'after:lastUpdated'
    );
    $GLOBALS['TCA']['pages']['palettes']['personnelcontact']['showitem'] = 'tx_personnel_authors,';
}
