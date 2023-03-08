<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Booster',
	'description' => 'Structured data for Typo3 and Google APIs',
	'category' => 'fe',
	'state' => 'beta',
	'author' => 'Andreas Sommer',
	'author_email' => 'sommer@belsignum.com',
	'version' => '10.0.1',
	'constraints' => [
		'depends' => [
			'typo3' => '10.4.0-10.4.99',
			'schema' => '2.7.x'
		],
		'conflicts' => [],
		'suggests' => [
			'setup' => '',
		],
	]
];
