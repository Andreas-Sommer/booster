<?php

use Belsignum\Booster\Backend\FormEngine\Element\RichResultsLinkElement;
use Belsignum\Booster\Controller\FaqController;
use Belsignum\Booster\Hook\DataHandler\CustomJsonLdValidator;
use Belsignum\Booster\Hook\PageRenderer\PreProcessHook;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

(function () {
    if (ExtensionManagementUtility::isLoaded('schema'))
    {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = PreProcessHook::class . '->execute';
    }

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][]
        = CustomJsonLdValidator::class;

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1716200000] = [
        'nodeName' => 'boosterRichResultsLink',
        'priority' => 40,
        'class' => RichResultsLinkElement::class,
    ];

    ExtensionUtility::configurePlugin(
        'booster',
        'faq',
        [
            FaqController::class => 'list',
        ]
    );
})();
