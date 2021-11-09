<?php
$EM_CONF[$_EXTKEY] = [
	'title' => 'Personnel',
	'description' => 'Personnel lists with vCard download.',
	'category' => 'fe',
	'version' => '3.0.0',
	'state' => 'stable',
	'clearcacheonload' => true,
	'author' => 'Tanel Põld, Nikolay Orlenko',
	'author_email' => 'tanel@brightside.ee',
	'author_company' => 'Brightside OÜ / t3brightside.com',
	'constraints' => [
		'depends' => [
			'typo3' => '11.5.0 - 11.5.99',
			'fluid_styled_content' => '11.5.0 - 11.5.99',
			'paginatedprocessors' => '1.0.0 - 1.99.99',
		],
	],
	'autoload' => [
		 'psr-4' => [
				'Brightside\\Personnel\\' => 'Classes'
		 ]
	],
];
