<?php
defined('TYPO3') || die('Access denied.');

(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_booster_domain_model_content');
})();
