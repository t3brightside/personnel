<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "personnel".
 *
 * Auto generated | Identifier: c27e33f8e62e652b9536646ad796641e
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Personnel',
	'description' => 'Personnel records list with vCard download.',
	'category' => 'fe',
	'version' => '0.1.1',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearcacheonload' => true,
	'author' => 'Tanel Põld, Nikolay Orlenko',
	'author_email' => 'tanel@brightside.ee',
	'author_company' => 'Brightside OÜ / t3brightside.com',
	'constraints' =>
	array (
		'depends' =>
		array (
			'typo3' => '8.7.0 - 8.7.99',
			'fluid_styled_content' => '',
		),
		'conflicts' =>
		array (
		),
		'suggests' =>
		array (
		),
	),
	'autoload' =>
	array (
		'classmap' =>
		array (
			0 => 'Classes',
		),
	),
	'_md5_values_when_last_written' => 'a:96:{s:14:"Configuration/";s:4:"d41d";s:18:"Configuration/TCA/";s:4:"d41d";s:28:"Configuration/TCA/Overrides/";s:4:"d41d";s:44:"Configuration/TCA/Overrides/sys_template.php";s:4:"9b43";s:9:"__MACOSX/";s:4:"d41d";s:23:"__MACOSX/Configuration/";s:4:"d41d";s:27:"__MACOSX/Configuration/TCA/";s:4:"d41d";s:37:"__MACOSX/Configuration/TCA/Overrides/";s:4:"d41d";s:55:"__MACOSX/Configuration/TCA/Overrides/._sys_template.php";s:4:"30fa";s:42:"Configuration/TCA/Overrides/tt_content.php";s:4:"a28d";s:53:"__MACOSX/Configuration/TCA/Overrides/._tt_content.php";s:4:"30fa";s:38:"__MACOSX/Configuration/TCA/._Overrides";s:4:"30fa";s:54:"Configuration/TCA/tx_personnel_domain_model_person.php";s:4:"c461";s:65:"__MACOSX/Configuration/TCA/._tx_personnel_domain_model_person.php";s:4:"30fa";s:28:"__MACOSX/Configuration/._TCA";s:4:"30fa";s:25:"Configuration/TypoScript/";s:4:"d41d";s:33:"Configuration/TypoScript/setup.ts";s:4:"44df";s:34:"__MACOSX/Configuration/TypoScript/";s:4:"d41d";s:44:"__MACOSX/Configuration/TypoScript/._setup.ts";s:4:"30fa";s:35:"__MACOSX/Configuration/._TypoScript";s:4:"30fa";s:21:"Configuration/PageTS/";s:4:"d41d";s:29:"Configuration/PageTS/setup.ts";s:4:"58e8";s:30:"__MACOSX/Configuration/PageTS/";s:4:"d41d";s:40:"__MACOSX/Configuration/PageTS/._setup.ts";s:4:"30fa";s:31:"__MACOSX/Configuration/._PageTS";s:4:"30fa";s:24:"__MACOSX/._Configuration";s:4:"30fa";s:14:"ext_tables.php";s:4:"d784";s:25:"__MACOSX/._ext_tables.php";s:4:"30fa";s:8:"Classes/";s:4:"d41d";s:23:"Classes/DataProcessing/";s:4:"d41d";s:55:"Classes/DataProcessing/DatabaseCustomQueryProcessor.php";s:4:"ecee";s:17:"__MACOSX/Classes/";s:4:"d41d";s:32:"__MACOSX/Classes/DataProcessing/";s:4:"d41d";s:66:"__MACOSX/Classes/DataProcessing/._DatabaseCustomQueryProcessor.php";s:4:"30fa";s:33:"__MACOSX/Classes/._DataProcessing";s:4:"30fa";s:20:"Classes/ViewHelpers/";s:4:"d41d";s:39:"Classes/ViewHelpers/ImageViewHelper.php";s:4:"ed79";s:29:"__MACOSX/Classes/ViewHelpers/";s:4:"d41d";s:50:"__MACOSX/Classes/ViewHelpers/._ImageViewHelper.php";s:4:"30fa";s:30:"__MACOSX/Classes/._ViewHelpers";s:4:"30fa";s:14:"Classes/Hooks/";s:4:"d41d";s:38:"Classes/Hooks/ContentPostProcessor.php";s:4:"c32a";s:23:"__MACOSX/Classes/Hooks/";s:4:"d41d";s:49:"__MACOSX/Classes/Hooks/._ContentPostProcessor.php";s:4:"30fa";s:29:"Classes/Hooks/PageLayoutView/";s:4:"d41d";s:71:"Classes/Hooks/PageLayoutView/PersonnelContentElementPreviewRenderer.php";s:4:"5d05";s:38:"__MACOSX/Classes/Hooks/PageLayoutView/";s:4:"d41d";s:82:"__MACOSX/Classes/Hooks/PageLayoutView/._PersonnelContentElementPreviewRenderer.php";s:4:"30fa";s:39:"__MACOSX/Classes/Hooks/._PageLayoutView";s:4:"30fa";s:24:"__MACOSX/Classes/._Hooks";s:4:"30fa";s:18:"__MACOSX/._Classes";s:4:"30fa";s:10:"Resources/";s:4:"d41d";s:17:"Resources/Public/";s:4:"d41d";s:24:"Resources/Public/Images/";s:4:"d41d";s:30:"Resources/Public/Images/Icons/";s:4:"d41d";s:63:"Resources/Public/Images/Icons/mimetypes-x-content-personnel.svg";s:4:"4b4d";s:19:"__MACOSX/Resources/";s:4:"d41d";s:26:"__MACOSX/Resources/Public/";s:4:"d41d";s:33:"__MACOSX/Resources/Public/Images/";s:4:"d41d";s:39:"__MACOSX/Resources/Public/Images/Icons/";s:4:"d41d";s:74:"__MACOSX/Resources/Public/Images/Icons/._mimetypes-x-content-personnel.svg";s:4:"30fa";s:40:"__MACOSX/Resources/Public/Images/._Icons";s:4:"30fa";s:37:"Resources/Public/Images/typo3Logo.svg";s:4:"82ad";s:48:"__MACOSX/Resources/Public/Images/._typo3Logo.svg";s:4:"30fa";s:34:"__MACOSX/Resources/Public/._Images";s:4:"30fa";s:24:"Resources/Public/Styles/";s:4:"d41d";s:37:"Resources/Public/Styles/personnel.css";s:4:"eff4";s:33:"__MACOSX/Resources/Public/Styles/";s:4:"d41d";s:48:"__MACOSX/Resources/Public/Styles/._personnel.css";s:4:"30fa";s:34:"__MACOSX/Resources/Public/._Styles";s:4:"30fa";s:27:"__MACOSX/Resources/._Public";s:4:"30fa";s:18:"Resources/Private/";s:4:"d41d";s:27:"Resources/Private/Language/";s:4:"d41d";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"7245";s:27:"__MACOSX/Resources/Private/";s:4:"d41d";s:36:"__MACOSX/Resources/Private/Language/";s:4:"d41d";s:54:"__MACOSX/Resources/Private/Language/._locallang_db.xml";s:4:"30fa";s:37:"__MACOSX/Resources/Private/._Language";s:4:"30fa";s:28:"Resources/Private/Templates/";s:4:"d41d";s:38:"Resources/Private/Templates/Vcard.html";s:4:"2e45";s:37:"__MACOSX/Resources/Private/Templates/";s:4:"d41d";s:49:"__MACOSX/Resources/Private/Templates/._Vcard.html";s:4:"30fa";s:39:"Resources/Private/Templates/Person.html";s:4:"cd39";s:50:"__MACOSX/Resources/Private/Templates/._Person.html";s:4:"30fa";s:38:"__MACOSX/Resources/Private/._Templates";s:4:"30fa";s:28:"__MACOSX/Resources/._Private";s:4:"30fa";s:20:"__MACOSX/._Resources";s:4:"30fa";s:12:"ext_icon.png";s:4:"8e4a";s:23:"__MACOSX/._ext_icon.png";s:4:"30fa";s:9:"README.md";s:4:"1798";s:20:"__MACOSX/._README.md";s:4:"30fa";s:25:"__MACOSX/._ext_emconf.php";s:4:"30fa";s:14:"ext_tables.sql";s:4:"71db";s:25:"__MACOSX/._ext_tables.sql";s:4:"30fa";s:17:"ext_localconf.php";s:4:"5589";s:28:"__MACOSX/._ext_localconf.php";s:4:"30fa";}',
);
