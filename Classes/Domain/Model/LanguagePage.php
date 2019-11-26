<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 19:37
 */

namespace Belsignum\Booster\Domain\Model;

class LanguagePage extends AbstractPage
{
	/**
	 * @var int
	 */
	protected $parentUid;

	/**
	 * @return int
	 */
	public function getParentUid(): int
	{
		return $this->parentUid;
	}

	/**
	 * @param int $parentUid
	 */
	public function setParentUid(int $parentUid): void
	{
		$this->parentUid = $parentUid;
	}
}
