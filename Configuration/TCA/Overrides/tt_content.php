<?php
	defined('TYPO3_MODE') || die('Access denied.');

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
				'tx_personnel_template' => array(
	        'exclude' => 1,
	        'label'   => 'Template',
	        'config'  => array(
	          'type'     => 'select',
	          'renderType' => 'selectSingle',
	          'default' => 0,
	          'items'    => array(), /* items set in page TsConfig */
	          'behaviour' => [
	            'allowLanguageSynchronization' => true,
	          ],
	        ),
	      ),
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

			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);

			$GLOBALS['TCA']['tt_content']['types']['personnel_selected']['showitem'] = $GLOBALS['TCA']['tt_content']['types']['header']['showitem'];
			$GLOBALS['TCA']['tt_content']['types']['personnel_selected']['showitem'] = str_replace(
					';headers,',
					';headers,
					--palette--;Data;personnelSelectedData,
					--palette--;Layout;personnelLayout,
					--palette--;Pagination;paginatedprocessors,',
					$GLOBALS['TCA']['tt_content']['types']['personnel_selected']['showitem']
			);

			$GLOBALS['TCA']['tt_content']['palettes']['personnelSelectedData']['showitem'] = '
			  tx_personnel,
				--linebreak--,
				tx_personnel_orderby,
				tx_personnel_startfrom,
				tx_personnel_limit,
			';

			$GLOBALS['TCA']['tt_content']['types']['personnel_frompages']['showitem'] = $GLOBALS['TCA']['tt_content']['types']['header']['showitem'];
			$GLOBALS['TCA']['tt_content']['types']['personnel_frompages']['showitem'] = str_replace(
					';headers,',
					';headers,
					--palette--;Data;personnelFrompagesData,
					--palette--;Layout;personnelLayout,
					--palette--;Filter;personnelFilters,
					--palette--;Pagination;paginatedprocessors,',
					$GLOBALS['TCA']['tt_content']['types']['personnel_frompages']['showitem']
			);
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
		}
	);
