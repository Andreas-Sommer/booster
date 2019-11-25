<?php
$EM_CONF[$_EXTKEY] = [
	'title' => 'Booster',
	'description' => 'Structured data for Typo3 and Google APIs',
	'category' => 'fe',
	'state' => 'beta',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'author' => 'Andreas Sommer',
	'author_email' => 'sommer@belsignum.com',
	'version' => '1.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '8.7.0-9.5.99',
			'schema' => '1.4.0'
		],
		'conflicts' => [],
		'suggests' => [
			'setup' => '',
		],
	],
	'autoload' => [
		'psr-4' => ['Belsignum\\Booster\\' => 'Classes']
	],
];
