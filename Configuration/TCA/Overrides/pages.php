<?php
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
$extConf = $extensionConfiguration->get('personnel');

if ($extConf['personnelEnableAuthors']) {
    $tempColumns = array(
        'tx_personnel' => [
            'exclude' => 1,
            'label' => 'Persons',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_personnel_domain_model_person',
                'MM' => 'tx_personnel_mm',
                'MM_opposite_field' => 'pages',
                'MM_match_fields' => [
                    'tablenames' => 'pages',
                    'fieldname' => 'tx_personnel',
                ],
                'size' => 5,
                'autoSizeMax' => 5,
                'maxitems' => 9999,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => true,
                        'options' => [
                            'windowOpenParameters' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
                        ],
                    ],
                    'addRecord' => [
                        'disabled' => true,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
    );
    ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns, 1);
    ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '--palette--;Personnel;personnelInPages',
        '1',
        'after:subtitle'
    );
    $GLOBALS['TCA']['pages']['palettes']['personnelInPages']['showitem'] = 'tx_personnel,';
}
