<?php

if(preg_match('/^8\./', TYPO3_version)) {
	call_user_func(
		function ($extKey, $table) {

			$ll = 'LLL:EXT:' . $extKey
				  . '/Resources/Private/Language/locallang_db.xlf';

			$pagesLangBoosterFields = [
				'tx_booster_faqs' => [
					'exclude' => true,
					'label'   => $ll . ':pages.tx_booster_faqs',
					'config'  => [
						'type'             => 'inline',
						'foreign_table'    => 'tx_booster_domain_model_content',
						'MM'               => 'tx_booster_pages_content_mm',
						'MM_match_fields'  => [
							'fieldname' => 'tx_booster_faq'
						],
						'maxitems'         => 999,
						'appearance'       => [
							'collapseAll'        => true,
							'useSortable'        => true,
							'newRecordLinkTitle' => $ll
													. ':pages.tx_booster_faqs.add',
						],
						'overrideChildTca' => [
							'ctrl'    => [
								'iconfile' => 'EXT:booster/Resources/Public/Icons/faq.svg',
							],
							'columns' => [
								'name' => [
									'label' => $ll
											   . ':tx_booster_domain_model_content.question',
								],
								'text' => [
									'label' => $ll
											   . ':tx_booster_domain_model_content.answer',
								],
							],
						],
					],
				],
			];

			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
				$table,
				$pagesLangBoosterFields
			);
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
				$table,
				'--div--;' . $ll . ':pages.tabs.booster, tx_booster_faqs',
				\Belsignum\Booster\Constants::DOCTYPE_DEFAULT
			);
		},
		'booster',
		'pages_language_overlay'
	);
}
