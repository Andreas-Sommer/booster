<?php
// Prevent Script from beeing called directly
defined('TYPO3_MODE') or die();

// encapsulate all locally defined variables
(function ()
{
	if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('schema'))
	{
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = \Belsignum\Booster\Hook\PageRenderer\PreProcessHook::class . '->execute';

		// Typo3 8.7 backward compatibility fix
		if(preg_match('/^8\./', TYPO3_version))
		{
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['Brotkrueml\\Schema\\Hook\\PageRenderer\\PostProcessHook'] = array(
				'className' => 'Belsignum\\Booster\\Xclass\\Hook\\PageRenderer\\PostProcessHook'
			);
		}
	}
})();

