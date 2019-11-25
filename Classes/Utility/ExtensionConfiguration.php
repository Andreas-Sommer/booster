<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 16:38
 */

namespace Belsignum\Booster\Utility;

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
		$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extKey]);
		return $extensionConfiguration[$configKey];
	}
}
