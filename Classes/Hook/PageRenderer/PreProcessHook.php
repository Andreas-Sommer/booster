<?php
/**
 * Created by PhpStorm.
 * User: Andreas Sommer
 * Date: 21.11.2019
 * Time: 16:51
 */

namespace Belsignum\Booster\Hook\PageRenderer;

use Belsignum\Booster\Domain\Model\AbstractPage;
use Belsignum\Booster\Domain\Repository\PageRepository;
use Belsignum\Booster\Domain\Repository\LanguagePageRepository;
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

	/** @var PageRepository|LanguagePageRepository */
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

		$languageRepository = $this->controller->sys_language_uid > 0 && preg_match('/^8\./', TYPO3_version)
			? LanguagePageRepository::class : PageRepository::class;
		$this->pageRepository = $this->objectManager->get($languageRepository);
	}
	public function execute(?array &$params, PageRenderer $pageRenderer): void
	{
		if (TYPO3_MODE !== 'FE' || $this->controller->page['no_index'] > 0) {
			return;
		}

		$page = $this->pageRepository->findByUid($this->controller->id);
		if(
			is_subclass_of($page , AbstractPage::class)
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
	}
}
