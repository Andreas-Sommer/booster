<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 19:45
 */

namespace Belsignum\Booster\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class PageRepository extends Repository
{
	public function __construct(
		\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	) {
		parent::__construct($objectManager);
	}
}
