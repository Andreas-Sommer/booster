<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 19:37
 */

namespace Belsignum\Booster\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

abstract class AbstractPage extends AbstractEntity
{

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Belsignum\Booster\Domain\Model\Content>
	 */
	protected $faqs;

	/**
	 * @var \Belsignum\Booster\Domain\Model\Content
	 */
	protected $product;

	public function __construct()
	{
		$this->faqs = new ObjectStorage();
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Belsignum\Booster\Domain\Model\Content>
	 */
	public function getFaqs(): ObjectStorage
	{
		return $this->faqs;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Belsignum\Booster\Domain\Model\Content> $faqs
	 */
	public function setFaqs(ObjectStorage $faqs
	): void {
		$this->faqs = $faqs;
	}

	/**
	 * @return \Belsignum\Booster\Domain\Model\Content|null
	 */
	public function getProduct(): ?\Belsignum\Booster\Domain\Model\Content
	{
		return $this->product;
	}

	/**
	 * @param \Belsignum\Booster\Domain\Model\Content $product
	 */
	public function setProduct(\Belsignum\Booster\Domain\Model\Content $product
	): void {
		$this->product = $product;
	}
}
