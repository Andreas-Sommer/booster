<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Booster',
	'description' => 'Structured data for Typo3 and Google APIs',
	'category' => 'fe',
	'state' => 'beta',
	'author' => 'Andreas Sommer',
	'author_email' => 'sommer@belsignum.com',
	'version' => '2.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '10.4.0-10.4.99',
			'schema' => '2.5.1'
		],
		'conflicts' => [],
		'suggests' => [
			'setup' => '',
		],
	]
];
