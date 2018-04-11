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
	'version' => '0.2.0',
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
			'typo3' => '8.7.0 - 9.99.99',
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
);
