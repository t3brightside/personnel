<?php
defined('TYPO3_MODE') || die('Access denied.');
$tx_personnel_domain_model_person = [
    'ctrl' => [
        'title' => 'Person',
        'label' => 'lastname',
        'label_alt' => 'firstname, profession',
        'label_alt_force' => 1,
        'prependAtCopy' => true,
        'hideAtCopy' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'editlock' => 'editlock',
        'type' => '0',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'default_sortby' => 'ORDER BY sorting ASC',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group',
        ],
        'iconfile' => 'EXT:personnel/Resources/Public/Images/Icons/mimetypes-x-content-personnel.svg',
        'searchFields' => 'uid,firstname,lastname,email,phone,profession,info',
    ],
    'interface' => [
        'showRecordFieldList' => 'cruser_id,pid,sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,firstname,lastname,profession,info,phone,email,images'
    ],
	'types' => array(
		'0' => array(
      'showitem' => '
        --palette--;Person;person,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
				--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;paletteAccess,
        --div--;Language,
        --palette--;;paletteLanguage,
			'
    )
	),
	'palettes' => array(
		'person' => array('showitem' => 'firstname,--linebreak--,lastname,--linebreak--,profession,--linebreak--,info,--linebreak--,phone,--linebreak--,email,--linebreak--,images'),
		'paletteLanguage' => [
        'showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource,',
    ],
		'paletteAccess' => [
      'showitem' => 'hidden,--linebreak--,starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,
				endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel,
				--linebreak--, fe_group;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:fe_group_formlabel,
				--linebreak--,editlock,
			',
    ],
	),
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0,
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_personnel_domain_model_person',
                'foreign_table_where' => 'AND tx_personnel_domain_model_person.pid=###CURRENT_PID### AND tx_personnel_domain_model_person.sys_language_uid IN (-1,0)',
                'default' => 0,
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'cruser_id' => [
            'label' => 'cruser_id',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'pid' => [
            'label' => 'pid',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'crdate' => [
            'label' => 'crdate',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'sorting' => [
            'label' => 'sorting',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel',
            'config' => [
                'behaviour' => [
										  	'allowLanguageSynchronization' => true,
										],
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 16,
                'eval' => 'datetime',
                'default' => 0,
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
            'config' => [
                'behaviour' => [
										  	'allowLanguageSynchronization' => true,
										],
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 16,
                'eval' => 'datetime',
                'default' => 0,
            ]
        ],
        'fe_group' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 20,
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.hide_at_login',
                        -1,
                    ],
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.any_login',
                        -2,
                    ],
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.usergroups',
                        '--div--',
                    ],
                ],
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => 'ORDER BY fe_groups.title',
            ],
        ],
        'firstname' => [
            'exclude' => false,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'First Name',
            'config' => [
                'type' => 'input',
                'size' => 160,
                'eval' => 'trim,required',
            ]
        ],
        'lastname' => [
            'exclude' => false,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'Last Name',
            'config' => [
                'type' => 'input',
                'size' => 160,
                'eval' => 'trim,required',
            ]
        ],
        'profession' => [
            'exclude' => false,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'Profession',
            'config' => [
                'type' => 'input',
                'size' => 160,
                'eval' => 'trim',
            ]
        ],
        'info' => [
            'exclude' => false,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'Additional Information',
            'config' => [
                'type' => 'text',
		        'cols' => 60,
		        'rows' => 6
            ]
        ],
        'phone' => [
            'exclude' => false,
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'Phone',
            'config' => [
                'type' => 'input',
                'size' => 160,
                'eval' => 'trim',
            ]
        ],
        'email' => [
            'exclude' => false,
            'l10n_mode' => 'trim,prefixLangTitle',
            'label' => 'E-mail',
            'config' => [
                'type' => 'input',
                'size' => 160,
                'eval' => 'trim',
            ],
        ],
        'images' => [
            'exclude' => true,
            'label' => 'Images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'behaviour' => [
										  	'allowLanguageSynchronization' => true,
										],
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'Add image',
                        'showPossibleLocalizationRecords' => true,
                        'showRemovedLocalizationRecords' => true,
                        'showAllLocalizationLink' => true,
                        'showSynchronizationLink' => true
                    ],
                    'inline' => [
                        'inlineOnlineMediaAddButtonStyle' => 'display:none'
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'images',
                        'tablenames' => 'tx_personnel_domain_model_person',
                        'table_local' => 'sys_file',
                    ],
                ]
            )
        ],
    ],
];
return $tx_personnel_domain_model_person;