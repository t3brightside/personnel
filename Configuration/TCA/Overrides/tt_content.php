<?php
	defined('TYPO3_MODE') || die('Access denied.');
	use \TYPO3\CMS\Core\Utility\VersionNumberUtility;

	$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['personnel_selected'] =  'mimetypes-x-content-personnel';
	$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['personnel_frompages'] =  'mimetypes-x-content-personnel';

	array_splice(
		$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'],
		6,
		0,
		array(
			array(
				'Personnel: selected',
				'personnel_selected',
				'mimetypes-x-content-personnel'
			),

		)
	);
	array_splice(
		$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'],
		6,
		0,
		array(
			array(
				'Personnel: from pages',
				'personnel_frompages',
				'mimetypes-x-content-personnel'
			),
		)
	);
	call_user_func(
    	function () {
			$tempColumns = array(
		    'tx_personnel' => [
					'exclude' => 1,
					'label' => 'Selected records or pages',
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
					],
				],
				'tx_personnel_orderby' => array(
					'exclude' => 1,
					'label'   => 'Order by',
					'config'  => array(
						'type'     => 'select',
					  'renderType' => 'selectSingle',
						'default' => 0,
						'items' => array(
			      			array('Manual (default)', '0'),
							array('By the sort order', 'sorting ASC'),
				    		array('Lastname (a → z)', 'lastname ASC'),
							array('Lastname (z → a)', 'lastname DESC'),
							array('First name (a → z)', 'firstname ASC'),
							array('First name (z → a)', 'firstname DESC'),
							array('Last updated (now → past)', 'tstamp DESC'),
							array('Last updated (past → now)', 'tstamp ASC'),
						),
					),
				),
				'tx_personnel_startfrom' => [
					'exclude' => 1,
					'label' => 'Start from item',
					'config' => [
						'type' => 'input',
						'eval' => 'num',
						'size' => '1',
					],
				],
				'tx_personnel_limit' => [
					'exclude' => 1,
					'label' => 'Items shown',
					'config' => [
						'type' => 'input',
						'eval' => 'num',
						'size' => '1',
					],
				],
				'tx_personnel_paginateitems' => [
					'exclude' => 1,
					'label' => 'Items per page',
					'config' => array(
						'type' => 'input',
						'eval' => 'num,trim',
						'size' => '1',
					),
				],
			);
			if (VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) <= 8007999) {
				$tempColumnsCheck = array(
					'tx_personnel_images' => [
						'exclude' => 1,
						'label' => 'Images',
						'config' => [
							 'type' => 'check',
							 'renderType' => 'check',
							 'items' => [
								 ['Disabled', '1'],
							 ],
						],
					],
					'tx_personnel_vcard' => [
						'exclude' => 1,
						'label' => 'vCard download',
						'config' => [
							 'type' => 'check',
							 'renderType' => 'check',
							 'items' => [
								 ['Disabled', '1'],
							 ],
						],
					],
					'tx_personnel_information' => [
						'exclude' => 1,
						'label' => 'Information',
						'config' => [
							 'type' => 'check',
							 'renderType' => 'check',
							 'items' => [
								 ['Disabled', '1'],
							 ],
						],
					],
					'tx_personnel_paginate' => [
						'exclude' => 1,
						'label' => 'Pagination',
						'config' => [
							 'type' => 'check',
							 'renderType' => 'check',
							 'items' => [
								 ['Enabled', '1'],
							 ],
						],
					],
				);
			} else {
				$tempColumnsCheck = array(
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
						'label' => 'vCard download',
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
					'tx_personnel_paginate' => [
						'exclude' => 1,
						'label' => 'Pagination',
						'config' => [
			                'type' => 'check',
			                'renderType' => 'checkboxToggle',
			                'items' => [
			                    [
			                        0 => '',
			                        1 => '',
			                    ]
			                ],
			            ]
					],
				);
			}
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumnsCheck);

			$GLOBALS['TCA']['tt_content']['types']['personnel_selected'] = array(
				'showitem' => '
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
			        	--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
			        	--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,tx_personnel,
			        	--palette--;;personnelSettings,
					--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
						--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
						--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
						--palette--;;language,
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
						--palette--;;hidden,
						--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,categories,
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,rowDescription,
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
					--div--;LLL:EXT:gridelements/Resources/Private/Language/locallang_db.xlf:gridElements, tx_gridelements_container, tx_gridelements_columns
		    	'
			);

			$GLOBALS['TCA']['tt_content']['palettes']['personnelSettings']['showitem'] = '
				tx_personnel_template,
				tx_personnel_limit,
				tx_personnel_images,
				tx_personnel_vcard,
				tx_personnel_information,
				tx_personnel_paginate,
				tx_personnel_paginateitems,
			';

			$GLOBALS['TCA']['tt_content']['types']['personnel_frompages'] = array(
				'showitem' => '
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
						--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
						--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,pages,
						--palette--;;personnelSettings,
						selected_categories;Category Filter,
					--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
						--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
						--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
					--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.accessibility,
						--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.menu_accessibility;menu_accessibility,
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
						--palette--;;language,
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
						--palette--;;hidden,
						--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,categories,
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,rowDescription,
					--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
					--div--;LLL:EXT:gridelements/Resources/Private/Language/locallang_db.xlf:gridElements, tx_gridelements_container, tx_gridelements_columns
		    	'
			);

			$GLOBALS['TCA']['tt_content']['palettes']['personnelSettings']['showitem'] = '
				tx_personnel_template,
				tx_personnel_orderby,
				tx_personnel_limit,
				tx_personnel_startfrom,
				tx_personnel_images,
				tx_personnel_vcard,
				tx_personnel_information,
				tx_personnel_paginate,
				tx_personnel_paginateitems,
			';
		}
	);
