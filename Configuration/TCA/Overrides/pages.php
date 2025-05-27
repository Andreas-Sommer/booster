<?php

use Belsignum\Booster\Constants;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

call_user_func(
    function ($extKey, $table) {
        $types = $GLOBALS['TCA']['tx_booster_domain_model_content']['types'];
        $ll = 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf';

        $pagesBoosterFields = [
        	'tx_booster_faqs' => [
        		'exclude' => true,
        		'label' => $ll . ':pages.tx_booster_faqs',
        		'config' => [
        			'type' => 'inline',
        			'foreign_table' => 'tx_booster_domain_model_content',
        			'MM' => 'tx_booster_pages_content_mm',
        			'MM_match_fields' => [
        				'fieldname' => 'tx_booster_faq'
        			],
        			'maxitems' => 999,
        			'appearance' => [
        				'collapseAll' => TRUE,
        				'useSortable' => TRUE,
        				'newRecordLinkTitle' => $ll . ':pages.tx_booster_faqs.add',
        				'showPossibleLocalizationRecords' => TRUE,
        				'showRemovedLocalizationRecords' => TRUE,
        				'showAllLocalizationLink' => TRUE,
        				'showSynchronizationLink' => TRUE,
        			],
        			'overrideChildTca' => [
        				'ctrl' => [
        					'iconfile' => 'EXT:booster/Resources/Public/Icons/faq.svg',
        				],
        				'types' => [
        					'1' => $types[Constants::CONTENT_TYPE_FAQ]
        				],
        				'columns' => [
        					'name' => [
        						'label' => $ll . ':tx_booster_domain_model_content.question',
        					],
        					'text' => [
        						'label' => $ll . ':tx_booster_domain_model_content.answer',
        					],
        				],
        			],
        		],
        	],
        	'tx_booster_product' => [
        		'exclude' => true,
        		'label' => $ll . ':pages.tx_booster_product',
        		'config' => [
        			'type' => 'inline',
        			'foreign_table' => 'tx_booster_domain_model_content',
        			'maxitems' => 1,
        			'appearance' => [
        				'collapseAll' => TRUE,
        				'useSortable' => TRUE,
        				'newRecordLinkTitle' => $ll . ':pages.tx_booster_product.create',
        				'showPossibleLocalizationRecords' => TRUE,
        				'showRemovedLocalizationRecords' => TRUE,
        				'showAllLocalizationLink' => TRUE,
        				'showSynchronizationLink' => TRUE,
        			],
        			'overrideChildTca' => [
        				'ctrl' => [
        					'iconfile' => 'EXT:booster/Resources/Public/Icons/product.svg',
        				],
        				'types' => [
        					'1' => $types[Constants::CONTENT_TYPE_PRODUCT]
        				],
        				'columns' => [
        					'text' => [
        						'label' => $ll . ':tx_booster_domain_model_content.description',
        					],
        				],
        			],
        		],
        	],
        ];

        ExtensionManagementUtility::addTCAcolumns(
            $table,
            $pagesBoosterFields
        );
        ExtensionManagementUtility::addToAllTCAtypes(
            $table,
            '--div--;' . $ll . ':pages.tabs.booster, tx_booster_faqs, tx_booster_product',
            (string) Constants::CONTENT_TYPE_DEFAULT,
            'after:endtime'
        );
    },
    'booster',
    'pages'
);
