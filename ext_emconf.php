<?php
$EM_CONF[$_EXTKEY] = array (
	'title' => 'Personnel',
	'description' => 'Personnel lists with vCard download.',
	'category' => 'fe',
	'version' => '2.4.1',
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
			'typo3' => '9.5.0 - 10.4.99',
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
