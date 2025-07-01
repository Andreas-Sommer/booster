<?php

use Belsignum\Booster\Controller\FaqController;
use Belsignum\Booster\Hook\PageRenderer\PreProcessHook;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

(function () {
    if (ExtensionManagementUtility::isLoaded('schema'))
    {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = PreProcessHook::class . '->execute';
    }

    ExtensionUtility::configurePlugin(
        'booster',
        'faq',
        [
            FaqController::class => 'list',
        ]
    );
})();

