<?php
	$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['personnel_selected'] =  'mimetypes-x-content-personnel';
	$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['personnel_frompages'] =  'mimetypes-x-content-personnel';

	array_splice(
		$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'],
		6,
		0,
		array(
			array(
				'Selected Persons',
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
				'Persons from Page',
				'personnel_frompages',
				'mimetypes-x-content-personnel'
			),
		)
	);

	$tempColumns = array(
    'tx_personnel' => array(
			'exclude' => 1,
			'label' => 'Selected personnel or page',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_personnel_domain_model_person',
				'size' => 3,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
			),
		),
		'tx_personnel_template' => array(
			'exclude' => 1,
			'label'   => 'Template',
			'config'  => array(
				'type'     => 'select',
				'renderType' => 'selectSingle',
				'default' => 0,
				'items'    => array(), /* items set in page TsConfig */
			),
		),
		'tx_personnel_orderby' => array(
			'exclude' => 1,
			'label'   => 'Sort by',
			'config'  => array(
				'type'     => 'select',
			  'renderType' => 'selectSingle',
				'default' => 0,
				'items' => array(
	      	array('Manual (default)', '0'),
					array('By the sort order', 'sorting ASC'),
		    	array('Lastname (a → z)', 'lastname ASC'),
					array('Lastname (z → a)', 'lastname DESC'),
					array('Last updated (now → past)', 'tstamp ASC'),
					array('Last updated (past → now)', 'tstamp DESC'),
				),
			),
		),
	);

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tt_content', 'EXT:personnel/Resources/Private/Language/locallang_db.xml');

	$GLOBALS['TCA']['tt_content']['types']['personnel_selected'] = array(
		'showitem' => '
			--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
        tx_personnel,
        --palette--;;personnelSettings,
      --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
          --palette--;;language,
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
          --palette--;;hidden,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
          categories,
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
          rowDescription,
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
      --div--;LLL:EXT:gridelements/Resources/Private/Language/locallang_db.xlf:gridElements, tx_gridelements_container, tx_gridelements_columns
    '
	);

	$GLOBALS['TCA']['tt_content']['palettes']['personnelSettings']['showitem'] = '
		tx_personnel_template,
		tx_personnel_orderby,
	';

	$GLOBALS['TCA']['tt_content']['types']['personnel_frompages'] = array(
		'showitem' => '
			--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
          pages,
          --palette--;;personnelSettings,
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
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
          categories,
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
          rowDescription,
      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
      --div--;LLL:EXT:gridelements/Resources/Private/Language/locallang_db.xlf:gridElements, tx_gridelements_container, tx_gridelements_columns
    '
	);

	$GLOBALS['TCA']['tt_content']['palettes']['personnelSettings']['showitem'] = '
		tx_personnel_template,
		tx_personnel_orderby,
	';
