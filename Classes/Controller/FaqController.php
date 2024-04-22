<?php

namespace Belsignum\Booster\Controller;

use Belsignum\Booster\Domain\Repository\ContentRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class FaqController extends ActionController
{
    protected ContentRepository $contentRepository;
    protected TypoScriptFrontendController $typoScriptFrontendController;

    public function __construct(
        ContentRepository $contentRepository
    ) {
        $this->contentRepository = $contentRepository;
        $this->typoScriptFrontendController = $GLOBALS['TSFE'];
    }

    public function listAction()
    {
        $cObj = $this->typoScriptFrontendController->cObj;
        $pid = $this->typoScriptFrontendController->id;
        if ($this->typoScriptFrontendController->getLanguage()->getLanguageId() > 0)
        {
            $pid = $this->typoScriptFrontendController->page['_PAGES_OVERLAY_UID'] ?? $pid;
        }
        $faqs = $this->contentRepository->getFaqsByPid($pid);
        $this->view->assignMultiple([
            'faqs' => $faqs,
            'data' => $cObj->data
        ]);
    }
}
