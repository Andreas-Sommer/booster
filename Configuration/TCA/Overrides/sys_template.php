<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
defined('TYPO3') || die('Access denied.');

call_user_func(function() {
    /**
     * Temporary variables
     */
    $extensionKey = 'booster';

    /**
     * Default TypoScript for Booster
     */
    ExtensionManagementUtility::addStaticFile(
        $extensionKey,
        'Configuration/TypoScript',
        'Booster - Main Template'
    );
});
