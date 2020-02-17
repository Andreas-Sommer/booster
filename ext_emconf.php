<?php

$dependencies['typo3'] = '8.7.0-9.5.99';
if(preg_match('/^(9|10)\./', TYPO3_version))
{
	$dependencies['schema'] = '1.4.1';
}

$EM_CONF[$_EXTKEY] = [
	'title' => 'Booster',
	'description' => 'Structured data for Typo3 and Google APIs',
	'category' => 'fe',
	'state' => 'beta',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'author' => 'Andreas Sommer',
	'author_email' => 'sommer@belsignum.com',
	'version' => '1.1.1',
	'constraints' => [
		'depends' => $dependencies,
		'conflicts' => [],
		'suggests' => [
			'setup' => '',
		],
	],
	'autoload' => [
		'psr-4' => ['Belsignum\\Booster\\' => 'Classes']
	],
];
