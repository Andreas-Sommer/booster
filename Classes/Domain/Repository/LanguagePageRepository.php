<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 19:45
 */

namespace Belsignum\Booster\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class LanguagePageRepository extends Repository
{

	public function findByUid($uid)
	{
		$query = $this->createQuery();
		$querySettings = $query->getQuerySettings();
		$querySettings->setRespectStoragePage(FALSE);
		$this->setDefaultQuerySettings($querySettings);

		$page = $this->findOneByPid($uid);
		$page->setParentUid($uid);
		return $page;
	}
}
