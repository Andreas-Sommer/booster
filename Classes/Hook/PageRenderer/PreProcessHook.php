<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 16:51
 */

namespace Belsignum\Booster\Hook\PageRenderer;

use Belsignum\Booster\Constants;
use Belsignum\Booster\Domain\Model\Content;
use Belsignum\Booster\Domain\Model\Page;
use Belsignum\Booster\Domain\Repository\PageRepository;
use Brotkrueml\Schema\Model\Type\AggregateOffer;
use Brotkrueml\Schema\Model\Type\AggregateRating;
use Brotkrueml\Schema\Model\Type\Brand;
use Brotkrueml\Schema\Model\Type\Offer;
use Brotkrueml\Schema\Model\Type\Organization;
use Brotkrueml\Schema\Model\Type\Person;
use Brotkrueml\Schema\Model\Type\Product;
use Brotkrueml\Schema\Model\Type\Rating;
use Brotkrueml\Schema\Model\Type\Review;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Brotkrueml\Schema\Model\Type\Answer;
use Brotkrueml\Schema\Model\Type\FAQPage;
use Brotkrueml\Schema\Model\Type\Question;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Brotkrueml\Schema\Manager\SchemaManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Page\PageRenderer;

class PreProcessHook
{
	/** @var TypoScriptFrontendController */
	protected $controller;

	/** @var ObjectManager */
	protected $objectManager;

	/** @var PageRepository */
	protected $pageRepository;

	/** @var \TYPO3\CMS\Extbase\Service\ImageService */
	protected $imageService;

	/**
	 * @var SchemaManager
	 */
	protected $schemaManager;

	public function __construct(
		TypoScriptFrontendController $controller = null,
		SchemaManager $schemaManager = null
	)
	{
		$this->controller    = $controller ?: $GLOBALS['TSFE'];
		$this->schemaManager = $schemaManager
			?: GeneralUtility::makeInstance(SchemaManager::class);
		$this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		$this->pageRepository = $this->objectManager->get(PageRepository::class);
		$this->imageService = $this->objectManager->get(ImageService::class);
	}
	public function execute(?array &$params, PageRenderer $pageRenderer): void
	{
		if (TYPO3_MODE !== 'FE' || $this->controller->page['no_index'] > 0) {
			return;
		}

		$page = $this->pageRepository->findByUid($this->controller->id);
		if(
			$page instanceof Page
		   	&& $page->getFaqs()->count()
		)
		{
			$faqPage = new FAQPage();

			/** @var \Belsignum\Booster\Domain\Model\Content $faq */
			foreach ($page->getFaqs() as $_ => $faq)
			{
				$answer = new Answer();
				$answer->setProperty('text', $faq->getText());

				$question = new Question();
				$question->setProperty('name', $faq->getName());
				$question->setProperty('acceptedAnswer', $answer);

				$faqPage->addProperty('mainEntity', $question);
			}

			$this->schemaManager->addType($faqPage);
		}
		if(
			$page instanceof Page
			&& $page->getProduct() instanceof Content
		)
		{
			$product = new Product();
			$product->addProperty('name', $page->getProduct()->getName());
			$product->addProperty('description', $page->getProduct()->getText());
			$product->addProperty('slogan', $page->getProduct()->getSlogan());
			$product->addProperty('color', $page->getProduct()->getCondition());
			$product->addProperty('award', $page->getProduct()->getAward());
			$product->addProperty('sku', $page->getProduct()->getSku());
			$product->addProperty('mpn', $page->getProduct()->getMpn());
			$product->addProperty('productID', $page->getProduct()->getProductId());
			if($brand = $page->getProduct()->getBrand())
			{
				$brandObj = new Brand();
				$brandObj->addProperty('name', $brand->getName());
				$product->addProperty('brand', $brandObj);
			}

			if($images = $page->getProduct()->getImages())
			{
				$imageCollection = [];
				/** @var \TYPO3\CMS\Extbase\Domain\Model\FileReference $image */
				foreach ($images as $_ => $image)
				{
					$img = $image->getOriginalResource();
					$cropVariantCollection = \TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection::create((string)$img->getProperty('crop'));
					$cropArea = $cropVariantCollection->getCropArea();
					$processingInstructions = array(
						'crop' => $cropArea->makeAbsoluteBasedOnFile($img),
					);
					$processedImage = $this->imageService->applyProcessingInstructions($img, $processingInstructions);
					$imageUri = $this->imageService->getImageUri($processedImage, TRUE);
					$imageCollection[] = $imageUri;
				}
				$product->addProperty('image', $imageCollection);
			}

			if(($offers = $page->getProduct()->getOffers()) instanceof ObjectStorage)
			{
				/**
				 * @var Content $offer
				 */
				foreach ($offers as $_ => $offer)
				{
					$offerObj = new AggregateOffer();
					$offerObj->addProperty('offerCount', $offers->count());
					$offerObj->addProperty('priceCurrency', $offer->getCurrency());
					if($offer->getPrice() > 0)
					{
						$offerObj->addProperty('price', $offer->getPrice());
					}
					if($offer->getDoubleValue() > 0)
					{
						$offerObj->addProperty('lowPrice', $offer->getDoubleValue());
					}
					if($offer->getCount() > 0)
					{
						$offerObj->addProperty('highPrice', $offer->getCount());
					}
					$offerObj->addProperty('availability', $offer->prependSchemaUri($offer->getSelect()));
					$offerObj->addProperty('itemCondition', $offer->prependSchemaUri($offer->getCondition()));
					$offerObj->addProperty('url', $offer->getUrl());
					$priceValidUntil = $offer->getPriceValidUntil() ? $offer->getPriceValidUntil()->getDate()->format(Constants::DATETIME_FORMAT_8601) : NULL;
					$offerObj->addProperty('priceValidUntil', $priceValidUntil);
					if($seller = $offer->getBrand())
					{
						$organization = new Organization();
						$organization->addProperty('name', $seller->getName());
						$offerObj->addProperty('seller', $organization);
					}
					$product->addProperty('offers', $offerObj);
				}
			}

			if($aggregateRating = $page->getProduct()->getAggregateRating())
			{
				$aggregateRatingObj = new AggregateRating();
				$aggregateRatingObj->addProperty('ratingValue', $aggregateRating->getDoubleValue());
				$aggregateRatingObj->addProperty('reviewCount', $aggregateRating->getCount());
				$product->addProperty('aggregateRating', $aggregateRatingObj);
			}
			if($review = $page->getProduct()->getReview())
			{
				$reviewObj = new Review();
				if($reviewRating = $review->getReviewRating())
				{
					$rating = new Rating();
					$rating->addProperty('ratingValue', $reviewRating->getDoubleValue());
					$rating->addProperty('bestRating', $reviewRating->getCount());
					$reviewObj->addProperty('reviewRating', $rating);
				}
				if($author = $review->getAuthor())
				{
					$person = new Person();
					$person->addProperty('name', $author->getName());
					$reviewObj->addProperty('author', $person);
				}
				$product->addProperty('review', $reviewObj);
			}
			$this->schemaManager->addType($product);
		}
	}
}
