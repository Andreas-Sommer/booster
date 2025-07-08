<?php

namespace Belsignum\Booster\Controller;

use Belsignum\Booster\Domain\Repository\ContentRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class FaqController extends ActionController
{
    public function __construct(
        protected readonly ContentRepository $contentRepository,
        protected readonly ContentObjectRenderer $contentObjectRenderer
    ) {}

    public function listAction(): ResponseInterface
    {
        $typoScriptFrontendController = $this->request->getAttribute('frontend.controller');
        $pid = $typoScriptFrontendController->id;
        if ($typoScriptFrontendController->getLanguage()->getLanguageId() > 0)
        {
            $pid = $typoScriptFrontendController->page['_PAGES_OVERLAY_UID'] ?? $pid;
        }
        $faqs = $this->contentRepository->getFaqsByPid($pid);
        $this->view->assignMultiple([
            'faqs' => $faqs,
            'data' => !empty($this->contentObjectRenderer->data) ? $this->contentObjectRenderer->data : $this->configurationManager->getContentObject()->data
        ]);
        return $this->htmlResponse();
    }
}
