<?php

use Belsignum\Booster\Constants;

$ll = 'LLL:EXT:booster/Resources/Private/Language/locallang_db.xlf';
$types = [
	(string) Constants::CONTENT_TYPE_DEFAULT => ['showitem' => 'hidden, name, text, url, slogan, color, award, --palette--;numbers;numbers, brand, offers'],
	(string) Constants::CONTENT_TYPE_FAQ => ['showitem' => 'name, text'],
	(string) Constants::CONTENT_TYPE_PRODUCT => ['showitem' => 'name, text, url, slogan, color, award, --palette--;numbers;numbers, brand, offers'],
	(string) Constants::CONTENT_TYPE_BRAND => ['showitem' => 'name'],
	(string) Constants::CONTENT_TYPE_DATE => ['showitem' => 'date'],
	(string) Constants::CONTENT_TYPE_OFFERS => ['showitem' => 'price, currency, price_valid_until, availability, url'],
];
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
		'showRecordFieldList' => 'hidden, name, text, gtin, product_id, nsn, mpn, sku, brand'
	],
	'types' => $types,
	'palettes' => [
		'numbers' => ['showitem' => 'sku, gtin, product_id, nsn, mpn']
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
		'hidden' => [
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => [
				'type' => 'check'
			]
		],
		'name' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.name',
			'config'  => [
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim,required',
				'max'  => 256
			]
		],
		'text' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.text',
			'config'  => [
				'type' => 'text',
				'cols' => '40',
				'rows' => '5',
				'eval' => 'trim,required',
				'enableRichtext' => true,
			]
		],
		'date' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.date',
			'config'  => [
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'eval' => 'datetime',
			]
		],
		'award' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.award',
			'config'  => [
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim',
				'max'  => 256
			]
		],
		'color' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.color',
			'config'  => [
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim',
				'max'  => 256
			]
		],
		'sku' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.sku',
			'config'  => [
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim',
				'max'  => 256
			]
		],
		'mpn' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.mpn',
			'config'  => [
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim',
				'max'  => 256
			]
		],
		'nsn' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.nsn',
			'config'  => [
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim',
				'max'  => 256
			]
		],
		'product_id' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.product_id',
			'config'  => [
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim',
				'max'  => 256
			]
		],
		'slogan' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.slogan',
			'config'  => [
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim',
				'max'  => 256
			]
		],
		'gtin' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.gtin',
			'config'  => [
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim',
				'max'  => 14,
				'min' => 8
			]
		],
		'url' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.url',
			'config'  => [
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim',
				'max'  => 256
			]
		],
		'price' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.price',
			'config'  => [
				'type' => 'input',
				'size' => 10,
				'eval' => 'trim,double2',
				'max'  => 10
			]
		],
		'currency' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.currency',
			'config'  => [
				'type' => 'input',
				'size' => 5,
				'eval' => 'trim',
				'max'  => 3
			]
		],
		'availability' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.availability',
			'config'  => [
				'type' => 'select',
				'items' => [
					['', ''],
					['Discontinued', 'Discontinued'],
					['InStock', 'InStock'],
					['InStoreOnly', 'InStoreOnly'],
					['LimitedAvailability', 'LimitedAvailability'],
					['OnlineOnly', 'OnlineOnly'],
					['OutOfStock', 'OutOfStock'],
					['PreOrder', 'PreOrder'],
					['PreSale', 'PreSale'],
					['SoldOut', 'SoldOut'],
				]
			]
		],
		'brand' => [
			'exclude' => true,
			'label' => $ll . ':tx_booster_domain_model_content.brand',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_booster_domain_model_content',
				'maxitems' => 1,
				'appearance' => [
					'collapseAll' => TRUE,
					'useSortable' => TRUE,
					'newRecordLinkTitle' => $ll . ':pages.tx_booster_brand.add',
					'showPossibleLocalizationRecords' => TRUE,
					'showRemovedLocalizationRecords' => TRUE,
					'showAllLocalizationLink' => TRUE,
					'showSynchronizationLink' => TRUE,
				],
				'overrideChildTca' => [
					'ctrl' => [
						'iconfile' => 'EXT:booster/Resources/Public/Icons/brand.svg',
					],
					'types' => [
						(string) Constants::CONTENT_TYPE_DEFAULT => $types[Constants::CONTENT_TYPE_BRAND],
					],
				],
			],
		],
		'offers' => [
			'exclude' => true,
			'label' => $ll . ':tx_booster_domain_model_content.offers',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_booster_domain_model_content',
				'maxitems' => 1,
				'appearance' => [
					'collapseAll' => TRUE,
					'useSortable' => TRUE,
					'newRecordLinkTitle' => $ll . ':pages.tx_booster_offers.add',
					'showPossibleLocalizationRecords' => TRUE,
					'showRemovedLocalizationRecords' => TRUE,
					'showAllLocalizationLink' => TRUE,
					'showSynchronizationLink' => TRUE,
				],
				'overrideChildTca' => [
					'ctrl' => [
						'label' => 'price',
						'label_alt' => 'currency',
						'label_alt_force' => TRUE,
						'iconfile' => 'EXT:booster/Resources/Public/Icons/offers.svg',
					],
					'types' => [
						(string) Constants::CONTENT_TYPE_DEFAULT => $types[Constants::CONTENT_TYPE_OFFERS],
					],
				],
			],
		],
		'price_valid_until' => [
			'exclude' => true,
			'label' => $ll . ':tx_booster_domain_model_content.price_valid_until',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_booster_domain_model_content',
				'maxitems' => 1,
				'appearance' => [
					'collapseAll' => TRUE,
					'useSortable' => TRUE,
					'newRecordLinkTitle' => $ll . ':pages.tx_booster_price_valid_until.add',
					'showPossibleLocalizationRecords' => TRUE,
					'showRemovedLocalizationRecords' => TRUE,
					'showAllLocalizationLink' => TRUE,
					'showSynchronizationLink' => TRUE,
				],
				'overrideChildTca' => [
					'ctrl' => [
						'label' => 'date',
						'iconfile' => 'EXT:booster/Resources/Public/Icons/date.svg',
					],
					'types' => [
						(string) Constants::CONTENT_TYPE_DEFAULT => $types[Constants::CONTENT_TYPE_DATE],
					],
				],
			],
		],

	],
];
