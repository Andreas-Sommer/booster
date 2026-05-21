<?php
declare(strict_types=1);

namespace Belsignum\Booster\Backend\FormEngine\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Backend\Routing\PreviewUriBuilder;

/**
 * Renders a button in the page properties "Structured Data" tab that opens the
 * Google Rich Results Test for the current page in a new browser tab.
 *
 * Renders an informational hint when the page has not been persisted yet
 * (no UID), as the FE URL is required by the external validator.
 */
final class RichResultsLinkElement extends AbstractFormElement
{
    private const VALIDATOR_URL = 'https://search.google.com/test/rich-results';
    private const LL = 'LLL:EXT:booster/Resources/Private/Language/locallang_db.xlf:';

    /**
     * @return array<string, mixed>
     */
    public function render(): array
    {
        $result = $this->initializeResultArray();

        $pageUid = (int)($this->data['vanillaUid'] ?? 0);
        $row = is_array($this->data['databaseRow'] ?? null) ? $this->data['databaseRow'] : [];
        $languageUid = (int)($row['sys_language_uid'][0] ?? $row['sys_language_uid'] ?? 0);

        $languageService = $this->getLanguageService();

        if ($pageUid <= 0 || ($this->data['command'] ?? '') === 'new') {
            $result['html'] = $this->wrap(
                '<p class="text-muted">'
                . htmlspecialchars($languageService->sL(self::LL . 'pages.tx_booster_richresults_link.unsaved'))
                . '</p>'
            );
            return $result;
        }

        $previewUri = null;
        try {
            $previewUri = (string)PreviewUriBuilder::create($pageUid)
                ->withLanguage($languageUid)
                ->buildUri();
        } catch (\Throwable) {
            $previewUri = null;
        }

        if ($previewUri === null || $previewUri === '') {
            $result['html'] = $this->wrap(
                '<p class="text-muted">'
                . htmlspecialchars($languageService->sL(self::LL . 'pages.tx_booster_richresults_link.unsaved'))
                . '</p>'
            );
            return $result;
        }

        $validatorUrl = self::VALIDATOR_URL . '?url=' . rawurlencode($previewUri);
        $buttonLabel = $languageService->sL(self::LL . 'pages.tx_booster_richresults_link.button');

        $html = '<a href="' . htmlspecialchars($validatorUrl) . '"'
            . ' target="_blank" rel="noopener noreferrer"'
            . ' class="btn btn-default">'
            . '<span class="t3js-icon icon icon-size-small icon-state-default icon-actions-link" aria-hidden="true"></span>'
            . ' ' . htmlspecialchars($buttonLabel)
            . '</a>'
            . '<div class="text-muted" style="margin-top:.5em;"><code>'
            . htmlspecialchars($previewUri)
            . '</code></div>';

        $result['html'] = $this->wrap($html);
        return $result;
    }

    private function wrap(string $inner): string
    {
        return '<div class="formengine-field-item t3js-formengine-field-item">'
            . '<div class="form-control-wrap">'
            . '<div class="form-wizards-wrap"><div class="form-wizards-element">'
            . $inner
            . '</div></div></div></div>';
    }
}
