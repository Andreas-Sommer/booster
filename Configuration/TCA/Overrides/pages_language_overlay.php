<?php
use Belsignum\Booster\Constants;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if(preg_match('/^8\./', TYPO3_version)) {
	call_user_func(
		function ($extKey, $table) {

			$ll = 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf';

			$pagesLangBoosterFields = [
				'tx_booster_faqs' => $GLOBALS['TCA']['pages']['columns']['tx_booster_faqs'],
				'tx_booster_product' => $GLOBALS['TCA']['pages']['columns']['tx_booster_product'],
			];

			ExtensionManagementUtility::addTCAcolumns(
				$table,
				$pagesLangBoosterFields
			);
			ExtensionManagementUtility::addToAllTCAtypes(
				$table,
				'--div--;' . $ll . ':pages.tabs.booster, tx_booster_faqs',
				(string) Constants::CONTENT_TYPE_DEFAULT,
				'after:endtime'
			);
		},
		'booster',
		'pages_language_overlay'
	);
}
