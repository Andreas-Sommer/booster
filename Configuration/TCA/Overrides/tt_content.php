<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

(static function (): void {
    ExtensionUtility::registerPlugin(
        'Booster',
        'faq',
        'FAQ Accordions from Structured Data'
    );

    $extensionName = GeneralUtility::underscoredToUpperCamelCase('booster');
    $pluginSignature = strtolower($extensionName) . '_faq';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,recursive,select_key,pages';
})();
