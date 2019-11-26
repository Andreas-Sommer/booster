<?php

namespace Belsignum\Booster\Domain\Repository;

use Belsignum\Booster\Domain\Model\LanguagePage;
use TYPO3\CMS\Extbase\Persistence\Repository;

class LanguagePageRepository extends Repository
{
	/**
	 * @param int $uid
	 *
	 * @return LanguagePage
	 */
	public function findByUid($uid)
	{
		$query = $this->createQuery();
		$querySettings = $query->getQuerySettings();
		$querySettings->setRespectStoragePage(FALSE);
		$this->setDefaultQuerySettings($querySettings);

		$page = $this->findOneByPid($uid);
		if($page instanceof LanguagePage)
		{
			$page->setParentUid($uid);
		}
		return $page;
	}
}
