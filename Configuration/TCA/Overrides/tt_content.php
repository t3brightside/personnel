<?php

defined('TYPO3_MODE') || defined('TYPO3') || die('Access denied.');

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use Brightside\Personnel\Preview\PersonnelPreviewRenderer;

$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['personnel_selected'] =  'mimetypes-x-content-personnel';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['personnel_frompages'] =  'mimetypes-x-content-personnel';

// Get extension configuration
$extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
$extensionConfiguration = $extensionConfiguration->get('personnel');

// Content element type dropdown
ExtensionManagementUtility::addTcaSelectItem(
    "tt_content",
    "CType",
    [
        'Personnel: selected',
        'personnel_selected',
        'mimetypes-x-content-personnel'
    ],
    'textmedia',
    'after'
);

ExtensionManagementUtility::addTcaSelectItem(
    "tt_content",
    "CType",
    [
        'Personnel: from pages',
        'personnel_frompages',
        'mimetypes-x-content-personnel'
    ],
    'textmedia',
    'after'
);

$tempColumns = array(
    'tx_personnel' => [
        'exclude' => 1,
        'label' => 'Selected Persons',
        'config' => [
            'type' => 'group',
            'internal_type' => 'db',
            'allowed' => 'tx_personnel_domain_model_person',
            'size' => 3,
            'autoSizeMax' => 30,
            'maxitems' => 9999,
            'multiple' => 0,
        ],
    ],
    'tx_personnel_template' => [
        'exclude' => 1,
        'label'   => 'Template',
        'config'  => [
            'type'     => 'select',
            'renderType' => 'selectSingle',
            'default' => 0,
            'items'    => array(), /* items set in page TsConfig */
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ],
    ],
    'tx_personnel_orderby' => [
        'exclude' => 1,
        'label'   => 'Order by',
        'config'  => [
            'type'     => 'select',
            'renderType' => 'selectSingle',
            'default' => 0,
            'items' => [
                ['Manual (default)', '0'],
                ['By the sort order', 'sorting ASC'],
                ['Lastname (a → z)', 'lastname ASC'],
                ['Lastname (z → a)', 'lastname DESC'],
                ['First name (a → z)', 'firstname ASC'],
                ['First name (z → a)', 'firstname DESC'],
                ['Last updated (now → past)', 'tstamp DESC'],
                ['Last updated (past → now)', 'tstamp ASC'],
            ],
        ],
    ],
    'tx_personnel_startfrom' => [
        'exclude' => 1,
        'label' => 'Start from Person',
        'config' => [
            'type' => 'input',
            'eval' => 'num',
            'size' => '1',
        ],
    ],
    'tx_personnel_limit' => [
        'exclude' => 1,
        'label' => 'Persons Shown',
        'config' => [
            'type' => 'input',
            'eval' => 'num',
            'size' => '1',
        ],
    ],
    'tx_personnel_images' => [
        'exclude' => 1,
        'label' => 'Images',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [
                [
                    0 => '',
                    1 => '',
                    'invertStateDisplay' => true
                ]
            ],
        ]
    ],
    'tx_personnel_vcard' => [
        'exclude' => 1,
        'label' => 'vCard Download',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [
                [
                    0 => '',
                    1 => '',
                    'invertStateDisplay' => true
                ]
            ],
        ]
    ],
    'tx_personnel_information' => [
        'exclude' => 1,
        'label' => 'Information',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [
                [
                    0 => '',
                    1 => '',
                    'invertStateDisplay' => true
                ]
            ],
        ]
    ],
);

ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
$GLOBALS['TCA']['tt_content']['types']['personnel_selected']['previewRenderer'] = PersonnelPreviewRenderer::class;
$GLOBALS['TCA']['tt_content']['types']['personnel_selected']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        --palette--;;headers,
        --palette--;Data;personnelSelectedData,
    	--palette--;Layout;personnelLayout,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;;frames,
        --palette--;;appearanceLinks,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;;access,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
        categories,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        rowDescription,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
';
if ($extensionConfiguration['personnelEnablePagination']) {
    $GLOBALS['TCA']['tt_content']['types']['personnel_selected']['showitem'] = str_replace(
        ';personnelLayout,',
        ';personnelLayout,
		--palette--;Pagination;paginatedprocessors,',
        $GLOBALS['TCA']['tt_content']['types']['personnel_selected']['showitem']
    );
}

$GLOBALS['TCA']['tt_content']['types']['personnel_frompages']['previewRenderer'] = PersonnelPreviewRenderer::class;
$GLOBALS['TCA']['tt_content']['types']['personnel_frompages']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        --palette--;;headers,
        --palette--;Data;personnelFrompagesData,
    	--palette--;Layout;personnelLayout,
    	--palette--;Filter;personnelFilters,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;;frames,
        --palette--;;appearanceLinks,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;;access,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
        categories,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        rowDescription,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
';
if ($extensionConfiguration['personnelEnablePagination']) {
    $GLOBALS['TCA']['tt_content']['types']['personnel_frompages']['showitem'] = str_replace(
        ';personnelFilters,',
        ';personnelFilters,
		--palette--;Pagination;paginatedprocessors,',
        $GLOBALS['TCA']['tt_content']['types']['personnel_frompages']['showitem']
    );
}

$GLOBALS['TCA']['tt_content']['palettes']['personnelSelectedData']['showitem'] = '
	tx_personnel,
	--linebreak--,
	tx_personnel_orderby,
	tx_personnel_startfrom,
	tx_personnel_limit,
';
$GLOBALS['TCA']['tt_content']['palettes']['personnelFrompagesData']['showitem'] = '
	pages,
	--linebreak--,
	tx_personnel_orderby,
	tx_personnel_startfrom,
	tx_personnel_limit,
';
$GLOBALS['TCA']['tt_content']['palettes']['personnelFilters']['showitem'] = '
	selected_categories;by Category,
';
$GLOBALS['TCA']['tt_content']['palettes']['personnelLayout']['showitem'] = '
	tx_personnel_template,
	tx_personnel_images,
	tx_personnel_information,
	tx_personnel_vcard,
';
