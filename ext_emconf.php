<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Booster',
	'description' => 'Structured data for Typo3 and Google APIs',
	'category' => 'fe',
	'state' => 'beta',
	'author' => 'Andreas Sommer',
	'author_email' => 'sommer@belsignum.com',
	'version' => '12.1.0',
	'constraints' => [
		'depends' => [
			'typo3' => '12.4.0-12.4.99',
			'schema' => '3.13.0'
		],
		'conflicts' => [],
		'suggests' => [
			'setup' => '',
		],
	]
];
