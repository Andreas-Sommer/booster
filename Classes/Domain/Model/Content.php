<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 19:33
 */

namespace Belsignum\Booster\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use Belsignum\Booster\Constants;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Content extends AbstractEntity
{

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $text;

	/**
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * images
	 *
	 * @var ObjectStorage<FileReference>
	 * @Extbase\ORM\Cascade("remove")
	 */
	protected $images = null;

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var string
	 */
	protected $slogan;

	/**
	 * @var string
	 */
	protected $condition;

	/**
	 * @var string
	 */
	protected $award;

	/**
	 * @var string
	 */
	protected $sku;

	/**
	 * @var string
	 */
	protected $mpn;

	/**
	 * @var string
	 */
	protected $gtin;

	/**
	 * @var string
	 */
	protected $productId;

	/**
	 * @var string
	 */
	protected $nsn;

	/**
	 * @var double
	 */
	protected $price;

	/**
	 * @var string
	 */
	protected $currency;

	/**
	 * @var string
	 */
	protected $select;

	/**
	 * @var float
	 */
	protected $doubleValue;

	/**
	 * @var float
	 */
	protected $count;

	/**
	 * @var \Belsignum\Booster\Domain\Model\Content
	 */
	protected $brand;

	/**
	 * @var ObjectStorage<\Belsignum\Booster\Domain\Model\Content>
	 * @Extbase\ORM\Cascade("remove")
	 */
	protected $offers;

	/**
	 * @var \Belsignum\Booster\Domain\Model\Content
	 */
	protected $priceValidUntil;

	/**
	 * @var \Belsignum\Booster\Domain\Model\Content
	 */
	protected $aggregateRating;

	/**
	 * @var \Belsignum\Booster\Domain\Model\Content
	 */
	protected $review;

	/**
	 * @var \Belsignum\Booster\Domain\Model\Content
	 */
	protected $reviewRating;

	/**
	 * @var \Belsignum\Booster\Domain\Model\Content
	 */
	protected $author;

	public function __construct()
	{
		$this->initializeObject();
	}

	public function initializeObject(): void
    {
		$this->setOffers(new ObjectStorage);
		$this->setImages(new ObjectStorage);
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->text;
	}

	/**
	 * @param string $text
	 */
	public function setText(string $text): void
	{
		$this->text = $text;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate(): \DateTime
	{
		return $this->date;
	}

	/**
	 * @param \DateTime $date
	 */
	public function setDate(\DateTime $date): void
	{
		$this->date = $date;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl(string $url): void
	{
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getSlogan(): string
	{
		return $this->slogan;
	}

	/**
	 * @param string $slogan
	 */
	public function setSlogan(string $slogan): void
	{
		$this->slogan = $slogan;
	}

	/**
	 * @return string
	 */
	public function getCondition(): string
	{
		return $this->condition;
	}

	/**
	 * @param string $condition
	 */
	public function setCondition(string $condition): void
	{
		$this->condition = $condition;
	}

	/**
	 * @return string
	 */
	public function getAward(): string
	{
		return $this->award;
	}

	/**
	 * @param string $award
	 */
	public function setAward(string $award): void
	{
		$this->award = $award;
	}

	/**
	 * @return string
	 */
	public function getSku(): string
	{
		return $this->sku;
	}

	/**
	 * @param string $sku
	 */
	public function setSku(string $sku): void
	{
		$this->sku = $sku;
	}

	/**
	 * @return string
	 */
	public function getMpn(): string
	{
		return $this->mpn;
	}

	/**
	 * @param string $mpn
	 */
	public function setMpn(string $mpn): void
	{
		$this->mpn = $mpn;
	}

	/**
	 * @return string
	 */
	public function getGtin(): string
	{
		return $this->gtin;
	}

	/**
	 * @param string $gtin
	 */
	public function setGtin(string $gtin): void
	{
		$this->gtin = $gtin;
	}

	/**
	 * @return string
	 */
	public function getProductId(): string
	{
		return $this->productId;
	}

	/**
	 * @param string $productId
	 */
	public function setProductId(string $productId): void
	{
		$this->productId = $productId;
	}

	/**
	 * @return string
	 */
	public function getNsn(): string
	{
		return $this->nsn;
	}

	/**
	 * @param string $nsn
	 */
	public function setNsn(string $nsn): void
	{
		$this->nsn = $nsn;
	}

	/**
	 * @return float
	 */
	public function getPrice(): float
	{
		return $this->price;
	}

	/**
	 * @param float $price
	 */
	public function setPrice(float $price): void
	{
		$this->price = $price;
	}

	/**
	 * @return string
	 */
	public function getCurrency(): string
	{
		return strtoupper($this->currency);
	}

	/**
	 * @param string $currency
	 */
	public function setCurrency(string $currency): void
	{
		$this->currency = $currency;
	}

	/**
	 * @return string
	 */
	public function getSelect(): string
	{
		return $this->select;
	}

	/**
	 * @param string $select
	 */
	public function setSelect(string $select): void
	{
		$this->select = $select;
	}

	/**
	 * @return float
	 */
	public function getDoubleValue(): float
	{
		return $this->doubleValue;
	}

	/**
	 * @param float $doubleValue
	 */
	public function setDoubleValue(float $doubleValue): void
	{
		$this->doubleValue = $doubleValue;
	}

	/**
	 * @return float
	 */
	public function getCount(): float
	{
		return $this->count;
	}

	/**
	 * @param float $count
	 */
	public function setCount(float $count): void
	{
		$this->count = $count;
	}

	/**
	 * @return \Belsignum\Booster\Domain\Model\Content|null
	 */
	public function getBrand(): ?\Belsignum\Booster\Domain\Model\Content
	{
		return $this->brand;
	}

	/**
	 * @param \Belsignum\Booster\Domain\Model\Content $brand
	 */
	public function setBrand(\Belsignum\Booster\Domain\Model\Content $brand
	): void {
		$this->brand = $brand;
	}

	/**
	 * @return null|ObjectStorage<\Belsignum\Booster\Domain\Model\Content>
	 */
	public function getOffers(): ?ObjectStorage
	{
		return $this->offers;
	}

	/**
	 * @param ObjectStorage<\Belsignum\Booster\Domain\Model\Content> $offers
	 */
	public function setOffers(ObjectStorage $offers
	): void {
		$this->offers = $offers;
	}

	/**
	 * @return \Belsignum\Booster\Domain\Model\Content|null
	 */
	public function getPriceValidUntil(
	): ?\Belsignum\Booster\Domain\Model\Content
	{
		return $this->priceValidUntil;
	}

	/**
	 * @param \Belsignum\Booster\Domain\Model\Content $priceValidUntil
	 */
	public function setPriceValidUntil(
		\Belsignum\Booster\Domain\Model\Content $priceValidUntil
	): void {
		$this->priceValidUntil = $priceValidUntil;
	}

	/**
	 * Returns the images
	 *
	 * @return ObjectStorage<FileReference> $images
	 */
	public function getImages(): ObjectStorage
	{
		return $this->images;
	}

	/**
	 * Sets the images
	 *
	 * @param ObjectStorage<FileReference> $images
	 * @return void
	 */
	public function setImages(
		ObjectStorage $images
	): void {
		$this->images = $images;
	}

	/**
	 * @return \Belsignum\Booster\Domain\Model\Content|null
	 */
	public function getAggregateRating(
	): ?\Belsignum\Booster\Domain\Model\Content
	{
		return $this->aggregateRating;
	}

	/**
	 * @param \Belsignum\Booster\Domain\Model\Content $aggregateRating
	 */
	public function setAggregateRating(
		\Belsignum\Booster\Domain\Model\Content $aggregateRating
	): void {
		$this->aggregateRating = $aggregateRating;
	}

	/**
	 * @return \Belsignum\Booster\Domain\Model\Content|null
	 */
	public function getReview(): ?\Belsignum\Booster\Domain\Model\Content
	{
		return $this->review;
	}

	/**
	 * @param \Belsignum\Booster\Domain\Model\Content $review
	 */
	public function setReview(\Belsignum\Booster\Domain\Model\Content $review
	): void {
		$this->review = $review;
	}

	/**
	 * @return \Belsignum\Booster\Domain\Model\Content|null
	 */
	public function getReviewRating(): ?\Belsignum\Booster\Domain\Model\Content
	{
		return $this->reviewRating;
	}

	/**
	 * @param \Belsignum\Booster\Domain\Model\Content $reviewRating
	 */
	public function setReviewRating(
		\Belsignum\Booster\Domain\Model\Content $reviewRating
	): void {
		$this->reviewRating = $reviewRating;
	}

	/**
	 * @return \Belsignum\Booster\Domain\Model\Content|null
	 */
	public function getAuthor(): ?\Belsignum\Booster\Domain\Model\Content
	{
		return $this->author;
	}

	/**
	 * @param \Belsignum\Booster\Domain\Model\Content $author
	 */
	public function setAuthor(\Belsignum\Booster\Domain\Model\Content $author
	): void {
		$this->author = $author;
	}

	/**
	 * Prepend Schema URI
	 * @param string $value
	 * @return string
	 */
	public function prependSchemaUri(string $value): string
	{
		return Constants::SCHEMA_ORG_URI . $value;
	}
}
