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
use Brotkrueml\Schema\Model\Type\Offer;
use Brotkrueml\Schema\Model\Type\Product;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Brotkrueml\Schema\Model\Type\Answer;
use Brotkrueml\Schema\Model\Type\FAQPage;
use Brotkrueml\Schema\Model\Type\Question;
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
			$product->addProperty('sku', $page->getProduct()->getSku());
			$product->addProperty('mpn', $page->getProduct()->getMpn());
			$brand = $page->getProduct()->getBrand();
			$product->addProperty('brand', $brand ? $brand->getName() : '');

			if(($offers = $page->getProduct()->getOffers()) instanceof Content)
			{
				$offerObj = new Offer();
				$offerObj->addProperty('priceCurrency', $offers->getCurrency());
				$offerObj->addProperty('price', $offers->getPrice());
				$offerObj->addProperty('availability', $offers->getAvailability());
				$priceValidUntil = $offers->getPriceValidUntil() ? $offers->getPriceValidUntil()->getDate()->format(Constants::DATETIME_FORMAT_8601) : NULL;
				$offerObj->addProperty('priceValidUntil', $priceValidUntil);
				$product->addProperty('offers', $offerObj);
			}
			$this->schemaManager->addType($product);
		}
	}
}
