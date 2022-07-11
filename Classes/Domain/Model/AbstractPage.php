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
	 * @var ObjectStorage<Content>
	 */
	protected $faqs;

	/**
	 * @var Content
	 */
	protected $product;

	public function __construct()
	{
		$this->faqs = new ObjectStorage();
	}

	/**
	 * @return ObjectStorage<Content>
	 */
	public function getFaqs(): ObjectStorage
	{
		return $this->faqs;
	}

	/**
	 * @param ObjectStorage<Content> $faqs
	 */
	public function setFaqs(ObjectStorage $faqs
	): void {
		$this->faqs = $faqs;
	}

	/**
	 * @return Content|null
	 */
	public function getProduct(): ?Content
	{
		return $this->product;
	}

	/**
	 * @param Content $product
	 */
	public function setProduct(Content $product
	): void {
		$this->product = $product;
	}
}
