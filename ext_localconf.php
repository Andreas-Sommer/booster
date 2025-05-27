<?php
defined('TYPO3_MODE') || die('Access denied.');

(function () {
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('schema'))
    {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = \Belsignum\Booster\Hook\PageRenderer\PreProcessHook::class . '->execute';
    }

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'booster',
        'faq',
        [
            \Belsignum\Booster\Controller\FaqController::class => 'list',
        ]
    );
})();

