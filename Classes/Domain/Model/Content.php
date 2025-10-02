<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 19:33
 */

namespace Belsignum\Booster\Domain\Model;

use DateTime;
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
     * @var DateTime
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
     * @var Content
     */
    protected $brand;

    /**
     * @var ObjectStorage<Content>
     * @Extbase\ORM\Cascade("remove")
     */
    protected $offers;

    /**
     * @var Content
     */
    protected $priceValidUntil;

    /**
     * @var Content
     */
    protected $aggregateRating;

    /**
     * @var Content
     */
    protected $review;

    /**
     * @var Content
     */
    protected $reviewRating;

    /**
     * @var Content
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

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
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

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getCurrency(): string
    {
        return strtoupper($this->currency);
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getSelect(): string
    {
        return $this->select;
    }

    public function setSelect(string $select): void
    {
        $this->select = $select;
    }

    public function getDoubleValue(): float
    {
        return $this->doubleValue;
    }

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

    public function setCount(float $count): void
    {
        $this->count = $count;
    }

    public function getBrand(): ?Content
    {
        return $this->brand;
    }

    public function setBrand(
        Content $brand
    ): void {
        $this->brand = $brand;
    }

    /**
     * @return null|ObjectStorage<Content>
     */
    public function getOffers(): ?ObjectStorage
    {
        return $this->offers;
    }

    /**
     * @param ObjectStorage<Content> $offers
     */
    public function setOffers(
        ObjectStorage $offers
    ): void {
        $this->offers = $offers;
    }

    public function getPriceValidUntil(
	): ?Content
    {
        return $this->priceValidUntil;
    }

    public function setPriceValidUntil(
        Content $priceValidUntil
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

    public function getAggregateRating(
	): ?Content
    {
        return $this->aggregateRating;
    }

    public function setAggregateRating(
        Content $aggregateRating
    ): void {
        $this->aggregateRating = $aggregateRating;
    }

    public function getReview(): ?Content
    {
        return $this->review;
    }

    public function setReview(
        Content $review
    ): void {
        $this->review = $review;
    }

    public function getReviewRating(): ?Content
    {
        return $this->reviewRating;
    }

    public function setReviewRating(
        Content $reviewRating
    ): void {
        $this->reviewRating = $reviewRating;
    }

    public function getAuthor(): ?Content
    {
        return $this->author;
    }

    public function setAuthor(
        Content $author
    ): void {
        $this->author = $author;
    }

    public function prependSchemaUri(string $value): string
    {
        return Constants::SCHEMA_ORG_URI . $value;
    }
}
