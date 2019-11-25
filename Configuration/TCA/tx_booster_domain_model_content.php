<?php

$ll = 'LLL:EXT:booster/Resources/Private/Language/locallang_db.xlf';

return [
	'ctrl' => [
		'title' => $ll . ':tx_booster_domain_model_content',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField'  => 'l18n_diffsource',
		'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xlf:LGL.prependAtCopy',
		'copyAfterDuplFields' => 'sys_language_uid',
		'useColumnsForDefaultValues' => 'sys_language_uid',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden'
		],
		'iconfile' => 'EXT:booster/Resources/Public/Icons/default_data.svg'
	],
	'interface' => [
		'showRecordFieldList' => 'name,text'
	],
	'types' => [
		(string) \Belsignum\Booster\Constants::CONTENT_TYPE_FAQ => ['showitem' => 'name, text']
	],
	'palettes' => [
		'1' => ['showitem' => '']
	],
	'columns' => [
		'sys_language_uid' => [
			'exclude' => true,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.language',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'special' => 'languages',
				'items' => [
					[
						'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
						-1,
						'flags-multiple'
					],
				],
				'default' => 0,
			]
		],
		'l18n_parent' => [
			'exclude' => true,
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					[
						'',
						0
					]
				],
				'foreign_table' => 'tt_content',
				'foreign_table_where' => 'AND tt_content.pid=###CURRENT_PID### AND tt_content.sys_language_uid IN (-1,0)',
				'default' => 0
			]
		],
		'l18n_diffsource' => [
			'config' => [
				'type' => 'passthrough'
			]
		],
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type' => 'check'
			)
		),

		'name' => array(
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.name',
			'config'  => array(
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim,required',
				'max'  => 256
			)
		),
		'text' => array(
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.text',
			'config'  => array(
				'type' => 'text',
				'cols' => '40',
				'rows' => '5',
				'eval' => 'trim,required',
				'enableRichtext' => true,
			)
		),

	],
];
