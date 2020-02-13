<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 19:33
 */

namespace Belsignum\Booster\Domain\Model;

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
	protected $color;

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
	protected $availability;

	/**
	 * @var \Belsignum\Booster\Domain\Model\Content
	 */
	protected $brand;

	/**
	 * @var \Belsignum\Booster\Domain\Model\Content
	 */
	protected $offers;

	/**
	 * @var \Belsignum\Booster\Domain\Model\Content
	 */
	protected $priceValidUntil;

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
	public function getColor(): string
	{
		return $this->color;
	}

	/**
	 * @param string $color
	 */
	public function setColor(string $color): void
	{
		$this->color = $color;
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
	public function getAvailability(): string
	{
		return Constants::SCHEMA_ORG_URI . $this->availability;
	}

	/**
	 * @param string $availability
	 */
	public function setAvailability(string $availability): void
	{
		$this->availability = $availability;
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
	 * @return \Belsignum\Booster\Domain\Model\Content|null
	 */
	public function getOffers(): ?\Belsignum\Booster\Domain\Model\Content
	{
		return $this->offers;
	}

	/**
	 * @param \Belsignum\Booster\Domain\Model\Content $offers
	 */
	public function setOffers(\Belsignum\Booster\Domain\Model\Content $offers
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


}
