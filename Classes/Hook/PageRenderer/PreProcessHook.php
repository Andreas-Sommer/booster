<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 16:51
 */

namespace Belsignum\Booster\Hook\PageRenderer;

use Belsignum\Booster\Domain\Repository\ContentRepository;
use Brotkrueml\Schema\Type\TypeFactory;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use Belsignum\Booster\Constants;
use Belsignum\Booster\Domain\Model\Content;
use Brotkrueml\Schema\Model\Type\AggregateOffer;
use Brotkrueml\Schema\Model\Type\AggregateRating;
use Brotkrueml\Schema\Model\Type\Brand;
use Brotkrueml\Schema\Model\Type\Organization;
use Brotkrueml\Schema\Model\Type\Person;
use Brotkrueml\Schema\Model\Type\Product;
use Brotkrueml\Schema\Model\Type\Rating;
use Brotkrueml\Schema\Model\Type\Review;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Brotkrueml\Schema\Manager\SchemaManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Page\PageRenderer;

class PreProcessHook
{
    protected ?TypoScriptFrontendController $controller;
    protected ContentRepository $contentRepository;
    protected ImageService $imageService;
    protected SchemaManager $schemaManager;

    public function __construct()
    {
        $this->controller = $GLOBALS['TSFE'] ?? null;
        if ($this->controller === null)
        {
            // missing frontendController indicates Backend mode
            return;
        }
        $this->schemaManager = GeneralUtility::makeInstance(SchemaManager::class);
        $this->contentRepository = GeneralUtility::makeInstance(ContentRepository::class);
        $this->imageService = GeneralUtility::makeInstance(ImageService::class);
    }

    public function execute(?array &$params, PageRenderer $pageRenderer): void
    {
        if ($this->controller === null || ApplicationType::fromRequest($this->controller->cObj->getRequest())->isFrontend() === false || $this->controller->page['no_index'] > 0)
        {
            return;
        }

        $pid = $this->controller->id;
        if ($this->controller->getLanguage()->getLanguageId() > 0)
        {
            $pid = $this->controller->page['_PAGES_OVERLAY_UID'] ?? $pid;
        }

        $faqs = $this->contentRepository->getFaqsByPid($pid);

        if (!empty($faqs))
        {
            $faqPage = TypeFactory::createType('FAQPage');

            /** @var Content $faq */
            foreach ($faqs as $_ => $faq)
            {
                $answer = TypeFactory::createType('Answer');
                $answer->setProperty('text', $faq['text']);

                $question = TypeFactory::createType('Question');
                $question->setProperty('name', $faq['name']);
                $question->setProperty('acceptedAnswer', $answer);

                $faqPage->addProperty('mainEntity', $question);
            }

            $this->schemaManager->addType($faqPage);
        }

        /** @var Content $pageProduct */
        $pageProduct = $this->contentRepository->findByUid($this->controller->page['tx_booster_product']);

        if ($pageProduct instanceof Content)
        {
            $product = new Product();
            $product->addProperty('name', $pageProduct->getName());
            $product->addProperty('description', $pageProduct->getText());
            $product->addProperty('slogan', $pageProduct->getSlogan());
            $product->addProperty('color', $pageProduct->getCondition());
            $product->addProperty('award', $pageProduct->getAward());
            $product->addProperty('sku', $pageProduct->getSku());
            $product->addProperty('mpn', $pageProduct->getMpn());
            $product->addProperty('productID', $pageProduct->getProductId());
            if ($brand = $pageProduct->getBrand())
            {
                $brandObj = new Brand();
                $brandObj->addProperty('name', $brand->getName());
                $product->addProperty('brand', $brandObj);
            }

            if ($images = $pageProduct->getImages())
            {
                $imageCollection = [];
                /** @var FileReference $image */
                foreach ($images as $_ => $image)
                {
                    $img = $image->getOriginalResource();
                    $cropVariantCollection = CropVariantCollection::create((string)$img->getProperty('crop'));
                    $cropArea = $cropVariantCollection->getCropArea();
                    $processingInstructions = ['crop' => $cropArea->makeAbsoluteBasedOnFile($img)];
                    $processedImage = $this->imageService->applyProcessingInstructions($img, $processingInstructions);
                    $imageUri = $this->imageService->getImageUri($processedImage, TRUE);
                    $imageCollection[] = $imageUri;
                }
                $product->addProperty('image', $imageCollection);
            }

            if (($offers = $pageProduct->getOffers()) instanceof ObjectStorage)
            {
                /**
                 * @var Content $offer
                 */
                foreach ($offers as $_ => $offer)
                {
                    $offerObj = new AggregateOffer();
                    $offerObj->addProperty('offerCount', $offers->count());
                    $offerObj->addProperty('priceCurrency', $offer->getCurrency());
                    if ($offer->getPrice() > 0)
                    {
                        $offerObj->addProperty('price', $offer->getPrice());
                    }
                    if ($offer->getDoubleValue() > 0)
                    {
                        $offerObj->addProperty('lowPrice', $offer->getDoubleValue());
                    }
                    if ($offer->getCount() > 0)
                    {
                        $offerObj->addProperty('highPrice', $offer->getCount());
                    }
                    $offerObj->addProperty('availability', $offer->prependSchemaUri($offer->getSelect()));
                    $offerObj->addProperty('itemCondition', $offer->prependSchemaUri($offer->getCondition()));
                    $offerObj->addProperty('url', $offer->getUrl());
                    $priceValidUntil = $offer->getPriceValidUntil() ? $offer->getPriceValidUntil()->getDate()->format(Constants::DATETIME_FORMAT_8601) : NULL;
                    $offerObj->addProperty('priceValidUntil', $priceValidUntil);
                    if ($seller = $offer->getBrand())
                    {
                        $organization = new Organization();
                        $organization->addProperty('name', $seller->getName());
                        $offerObj->addProperty('seller', $organization);
                    }
                    $product->addProperty('offers', $offerObj);
                }
            }

            if ($aggregateRating = $pageProduct->getAggregateRating())
            {
                $aggregateRatingObj = new AggregateRating();
                $aggregateRatingObj->addProperty('ratingValue', $aggregateRating->getDoubleValue());
                $aggregateRatingObj->addProperty('reviewCount', $aggregateRating->getCount());
                $product->addProperty('aggregateRating', $aggregateRatingObj);
            }
            if ($review = $pageProduct->getReview())
            {
                $reviewObj = new Review();
                if ($reviewRating = $review->getReviewRating())
                {
                    $rating = new Rating();
                    $rating->addProperty('ratingValue', $reviewRating->getDoubleValue());
                    $rating->addProperty('bestRating', $reviewRating->getCount());
                    $reviewObj->addProperty('reviewRating', $rating);
                }
                if ($author = $review->getAuthor())
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
