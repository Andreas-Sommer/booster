<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 16:38
 */

namespace Belsignum\Booster\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
class ExtensionConfiguration
{
	/**
	 * @param $extKey
	 * @param $configKey
	 *
	 * @return mixed
	 */
	public function get($extKey, $configKey)
	{
		$extensionConfiguration = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get($extKey);
		return $extensionConfiguration[$configKey];
	}
}
