<?php

use Belsignum\Booster\Userfuncs\ContentTca;
use Belsignum\Booster\Constants;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Resource\File;

$ll = 'LLL:EXT:booster/Resources/Private/Language/locallang_db.xlf';
$types = [
	(string) Constants::CONTENT_TYPE_DEFAULT => ['showitem' => 'hidden, name, text, price, double_value, count, select, url, slogan, condition, award, --palette--;numbers;numbers, brand, offers, aggregate_rating, images'],
	(string) Constants::CONTENT_TYPE_FAQ => ['showitem' => 'name, text'],
	(string) Constants::CONTENT_TYPE_PRODUCT => ['showitem' => 'name, text, url, slogan, condition, award, --palette--;numbers;numbers, brand, offers, aggregate_rating, review, images'],
	(string) Constants::CONTENT_TYPE_BRAND => ['showitem' => 'name'],
	(string) Constants::CONTENT_TYPE_DATE => ['showitem' => 'date'],
	(string) Constants::CONTENT_TYPE_OFFERS => ['showitem' => 'price, double_value, count, currency, price_valid_until, url, select, condition, brand'],
	(string) Constants::CONTENT_TYPE_AGGREGATE_RATING => ['showitem' => 'double_value, count'],
	(string) Constants::CONTENT_TYPE_REVIEW => ['showitem' => 'review_rating, author'],
	(string) Constants::CONTENT_TYPE_REVIEW_RATING => ['showitem' => 'double_value, count'],
	(string) Constants::CONTENT_TYPE_AUTHOR => ['showitem' => 'name'],
];
return [
	'ctrl' => [
		'title' => $ll . ':tx_booster_domain_model_content',
		'label' => 'name',
		'formattedLabel_userFunc' => ContentTca::class . '->dynamicTitleByType',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField'  => 'l18n_diffsource',
		'prependAtCopy' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.prependAtCopy',
		'copyAfterDuplFields' => 'sys_language_uid',
		'useColumnsForDefaultValues' => 'sys_language_uid',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden'
		],
		'iconfile' => 'EXT:booster/Resources/Public/Icons/default_data.svg'
	],
	'types' => $types,
	'palettes' => [
		'numbers' => ['showitem' => 'sku, product_id, mpn']
	],
	'columns' => [
		'sys_language_uid' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'special' => 'languages',
				'items' => [
					[
						'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
						-1,
						'flags-multiple'
					],
				],
				'default' => 0,
			]
		],
		'l18n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
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
			'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
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
		'images' => [
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.images',
			'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
				'booster_product_image',
				[
					'appearance' => [
						'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
					],
					// custom configuration for displaying fields in the overlay/reference table
					// to use the image overlay palette instead of the basic overlay palette
					'overrideChildTca' => [
						'types' => [
							'0' => [
								'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
							],
							File::FILETYPE_TEXT => [
								'showitem' => '
                                --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
							],
							File::FILETYPE_IMAGE => [
								'showitem' => '
                                --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
							],
							File::FILETYPE_AUDIO => [
								'showitem' => '
                                --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.audioOverlayPalette;audioOverlayPalette,
                                --palette--;;filePalette'
							],
							File::FILETYPE_VIDEO => [
								'showitem' => '
                                --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.videoOverlayPalette;videoOverlayPalette,
                                --palette--;;filePalette'
							],
							File::FILETYPE_APPLICATION => [
								'showitem' => '
                                --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
							],
						],
					],
				],
				$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
			),
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
		'condition' => [
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
		'select' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.select',
			'config'  => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'maxItems' => 1,
				'items' => [
					['', ''],
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
					'newRecordLinkTitle' => $ll . ':tx_booster_domain_model_content.brand.add',
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
				'maxitems' => 999,
				'appearance' => [
					'collapseAll' => TRUE,
					'useSortable' => TRUE,
					'newRecordLinkTitle' => $ll . ':tx_booster_domain_model_content.offers.add',
					'showPossibleLocalizationRecords' => TRUE,
					'showRemovedLocalizationRecords' => TRUE,
					'showAllLocalizationLink' => TRUE,
					'showSynchronizationLink' => TRUE,
				],
				'overrideChildTca' => [
					'ctrl' => [
						'label' => 'price',
						'label_alt' => 'double_value, count, currency',
						'label_alt_force' => TRUE,
						'iconfile' => 'EXT:booster/Resources/Public/Icons/offers.svg',
					],
					'types' => [
						(string) Constants::CONTENT_TYPE_DEFAULT => $types[Constants::CONTENT_TYPE_OFFERS],
					],
					'columns' => [
						'double_value' => [
							'label' => $ll . ':tx_booster_domain_model_content.low_price',
						],
						'count' => [
							'label' => $ll . ':tx_booster_domain_model_content.high_price',
						],
						'brand' => [
							'label' => $ll . ':tx_booster_domain_model_content.seller',
						],
						'select' => [
							'label' => $ll . ':tx_booster_domain_model_content.availability',
							'config' => [
								'type' => 'select',
								'renderType' => 'selectSingle',
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
						'condition' => [
							'label' => $ll . ':tx_booster_domain_model_content.item_condition',
							'config' => [
								'type' => 'select',
								'renderType' => 'selectSingle',
								'size' => 1,
								'items' => [
									['', ''],
									['DamagedCondition', 'DamagedCondition'],
									['NewCondition', 'NewCondition'],
									['RefurbishedCondition', 'RefurbishedCondition'],
									['UsedCondition', 'UsedCondition'],
								]
							]
						],
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
					'newRecordLinkTitle' => $ll . ':tx_booster_domain_model_content.price_valid_until.add',
					'showPossibleLocalizationRecords' => TRUE,
					'showRemovedLocalizationRecords' => TRUE,
					'showAllLocalizationLink' => TRUE,
					'showSynchronizationLink' => TRUE,
				],
				'overrideChildTca' => [
					'ctrl' => [
						'label' => 'date',
						'iconfile' => 'EXT:booster/Resources/Public/Icons/price_valid_until.svg',
					],
					'types' => [
						(string) Constants::CONTENT_TYPE_DEFAULT => $types[Constants::CONTENT_TYPE_DATE],
					],
				],
			],
		],
		'aggregate_rating' => [
			'exclude' => true,
			'label' => $ll . ':tx_booster_domain_model_content.aggregate_rating',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_booster_domain_model_content',
				'maxitems' => 1,
				'appearance' => [
					'collapseAll' => TRUE,
					'useSortable' => TRUE,
					'newRecordLinkTitle' => $ll . ':tx_booster_domain_model_content.aggregate_rating.add',
					'showPossibleLocalizationRecords' => TRUE,
					'showRemovedLocalizationRecords' => TRUE,
					'showAllLocalizationLink' => TRUE,
					'showSynchronizationLink' => TRUE,
				],
				'overrideChildTca' => [
					'ctrl' => [
						'label' => 'double_value',
						'label_alt' => 'count',
						'label_alt_force' => TRUE,
						'iconfile' => 'EXT:booster/Resources/Public/Icons/aggregate_rating.svg',
					],
					'types' => [
						(string) Constants::CONTENT_TYPE_DEFAULT => $types[Constants::CONTENT_TYPE_AGGREGATE_RATING],
					],
					'columns' => [
						'double_value' => [
							'label' => $ll . ':tx_booster_domain_model_content.rating_value',
						],
						'count' => [
							'label' => $ll . ':tx_booster_domain_model_content.review_count',
							'config' => [
								'eval' => 'int'
							]
						],
					],
				],
			],
		],
		'price' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.price',
			'config'  => [
				'type' => 'input',
				'size' => 10,
				'eval' => 'double2',
				'max'  => 10
			]
		],
		'double_value' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.double_value',
			'config'  => [
				'type' => 'input',
				'size' => 10,
				'eval' => 'double2',
				'max'  => 10
			]
		],
		'count' => [
			'exclude' => 0,
			'label'   => $ll . ':tx_booster_domain_model_content.count',
			'config'  => [
				'type' => 'input',
				'size' => 10,
				'eval' => 'double2',
				'max'  => 10
			]
		],
		'review' => [
			'exclude' => true,
			'label' => $ll . ':tx_booster_domain_model_content.review',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_booster_domain_model_content',
				'maxitems' => 1,
				'appearance' => [
					'collapseAll' => TRUE,
					'useSortable' => TRUE,
					'newRecordLinkTitle' => $ll . ':tx_booster_domain_model_content.review.add',
					'showPossibleLocalizationRecords' => TRUE,
					'showRemovedLocalizationRecords' => TRUE,
					'showAllLocalizationLink' => TRUE,
					'showSynchronizationLink' => TRUE,
				],
				'overrideChildTca' => [
					'ctrl' => [
						'label_static' => 'Review',
						'label' => 'review_rating',
						'label_alt' => 'author',
						'label_alt_force' => TRUE,
						'iconfile' => 'EXT:booster/Resources/Public/Icons/review.svg',
					],
					'types' => [
						(string) Constants::CONTENT_TYPE_DEFAULT => $types[Constants::CONTENT_TYPE_REVIEW],
					],
				],
			],
		],
		'review_rating' => [
			'exclude' => true,
			'label' => $ll . ':tx_booster_domain_model_content.review',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_booster_domain_model_content',
				'maxitems' => 1,
				'appearance' => [
					'collapseAll' => TRUE,
					'useSortable' => TRUE,
					'newRecordLinkTitle' => $ll . ':tx_booster_domain_model_content.review.add',
					'showPossibleLocalizationRecords' => TRUE,
					'showRemovedLocalizationRecords' => TRUE,
					'showAllLocalizationLink' => TRUE,
					'showSynchronizationLink' => TRUE,
				],
				'overrideChildTca' => [
					'ctrl' => [
						'label' => 'double_value',
						'label_alt'=> 'count',
						'label_alt_force' => TRUE,
						'iconfile'  => 'EXT:booster/Resources/Public/Icons/review.svg',
					],
					'types' => [
						(string) Constants::CONTENT_TYPE_DEFAULT => $types[Constants::CONTENT_TYPE_REVIEW_RATING],
					],
					'columns' => [
						'double_value' => [
							'label' => $ll . ':tx_booster_domain_model_content.rating_value',
						],
						'count' => [
							'label' => $ll . ':tx_booster_domain_model_content.best_rating',
						],
					],
				],
			],
		],
		'author' => [
			'exclude' => true,
			'label' => $ll . ':tx_booster_domain_model_content.author',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_booster_domain_model_content',
				'maxitems' => 1,
				'appearance' => [
					'collapseAll' => TRUE,
					'useSortable' => TRUE,
					'newRecordLinkTitle' => $ll . ':tx_booster_domain_model_content.author.add',
					'showPossibleLocalizationRecords' => TRUE,
					'showRemovedLocalizationRecords' => TRUE,
					'showAllLocalizationLink' => TRUE,
					'showSynchronizationLink' => TRUE,
				],
				'overrideChildTca' => [
					'ctrl' => [
						'iconfile' => 'EXT:booster/Resources/Public/Icons/author.svg',
					],
					'types' => [
						(string) Constants::CONTENT_TYPE_DEFAULT => $types[Constants::CONTENT_TYPE_AUTHOR],
					],
				],
			],
		],
	],
];
