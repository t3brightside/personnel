<?php
defined('TYPO3_MODE') || die('Access denied.');
$tx_personnel_domain_model_person = [
  'ctrl' => [
    'title' => 'Personnel',
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
    'showRecordFieldList' => 'cruser_id,pid,sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,firstname,lastname,profession,phone,email,images,info'
  ],
	'types' => array(
		'0' => array(
      'showitem' => '
        --palette--;Person;person,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
				--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;paletteAccess,
        --div--;Language,
        --palette--;;paletteLanguage,
        --div--;LLL:EXT:lang/locallang_tca.xlf:sys_category.tabs.category, categories,
			'
    )
	),
	'palettes' => array(
		'person' => array('showitem' => '
      title,
      firstname,
      lastname,--linebreak--,
      profession,
      phone,
      email,--linebreak--,
      images,--linebreak--,
      info'
    ),

		'paletteAccess' => [
      'showitem' => 'hidden,--linebreak--,starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,
				endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel,
				--linebreak--, fe_group;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:fe_group_formlabel,
				--linebreak--,editlock,
			',
    ],
    'paletteLanguage' => [
      'showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource,',
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
    'categories' => [
      'exclude' => 1,
      'label' => 'LLL:EXT:lang/locallang_tca.xlf:sys_category.categories',
      'config' => \TYPO3\CMS\Core\Category\CategoryRegistry::getTcaFieldConfiguration('tt_address')
    ],
    'title' => [
      'exclude' => false,
      'label' => 'Title',
      'config' => [
        'type' => 'input',
        'size' => 20,
        'eval' => 'trim',
        'behaviour' => [
          'allowLanguageSynchronization' => true,
        ],
      ]
    ],
    'firstname' => [
      'exclude' => false,
      'label' => 'First Name',
      'config' => [
        'type' => 'input',
        'size' => 20,
        'eval' => 'trim',
        'behaviour' => [
          'allowLanguageSynchronization' => true,
        ],
      ]
    ],
    'lastname' => [
      'exclude' => false,
      'label' => 'Last Name',
      'config' => [
        'type' => 'input',
        'size' => 20,
        'eval' => 'trim',
        'behaviour' => [
          'allowLanguageSynchronization' => true,
        ],
      ]
    ],
    'profession' => [
      'exclude' => false,
      'l10n_mode' => 'prefixLangTitle',
      'label' => 'Profession',
      'config' => [
        'type' => 'input',
        'eval' => 'trim',
        'size' => 20,
      ]
    ],
    'phone' => [
      'exclude' => false,
      'label' => 'Phone',
      'config' => [
        'type' => 'input',
        'size' => 20,
        'eval' => 'trim',
        'behaviour' => [
          'allowLanguageSynchronization' => true,
        ],
      ]
    ],
    'email' => [
      'exclude' => false,
      'label' => 'E-mail',
      'config' => [
        'type' => 'input',
        'size' => 20,
        'eval' => 'trim,email',
        'behaviour' => [
          'allowLanguageSynchronization' => true,
        ],
      ],
    ],
    'images' => [
      'exclude' => 1,
      'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.images',
      'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
        'images',
        [
          'behaviour' => [
            'allowLanguageSynchronization' => true,
          ],
          'appearance' => [
            'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
          ],
          // custom configuration for displaying fields in the overlay/reference table
          // to use the image overlay palette instead of the basic overlay palette
          'overrideChildTca' => [
            'types' => [
              '0' => [
                'showitem' => '
                  --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                  --palette--;;filePalette'
              ],
              \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                'showitem' => '
                  --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                  --palette--;;filePalette'
              ],
            ],
          ],
        ],
        $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
      ),
    ],
    'info' => [
      'exclude' => false,
      'l10n_mode' => 'prefixLangTitle',
      'label' => 'Additional Information',
      'config' => [
        'type' => 'text',
        'enableRichtext' => true,
        'cols' => 60,
        'rows' => 6
      ]
    ],
  ],
];
return $tx_personnel_domain_model_person;
