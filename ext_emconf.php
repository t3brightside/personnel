<?php
$EM_CONF[$_EXTKEY] = [
	'title' => 'Personnel',
	'description' => 'Persons contact lists with vCard support. Demo: microtemplate.t3brightside.com',
	'category' => 'fe',
	'version' => '4.1.0',
	'state' => 'stable',
	'clearcacheonload' => true,
	'author' => 'Tanel Põld, Nikolay Orlenko',
	'author_email' => 'tanel@brightside.ee',
	'author_company' => 'Brightside OÜ / t3brightside.com',
	'constraints' => [
		'depends' => [
			'typo3' => '12.4.0 - 13.9.99',
			'fluid_styled_content' => '12.4.0 - 13.9.99',
			'paginatedprocessors' => '1.6.0 - 1.99.99',
            'embedassets' => '1.3.0-1.99.99',
		],
	],
	'autoload' => [
		 'psr-4' => [
				'Brightside\\Personnel\\' => 'Classes'
		 ]
	],
];
