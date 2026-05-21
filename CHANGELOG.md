# Changelog

All notable changes to this project will be documented in this file.
The format is based on Keep a Changelog, and this project adheres to Semantic Versioning.

## Unreleased
### Added
- Page field `tx_booster_custom_jsonld` (`pages` table, `mediumtext`) for free-form JSON-LD per page, rendered in the `Structured Data` page-properties tab as a `t3editor` with JSON syntax highlighting.
- DataHandler hook `Belsignum\Booster\Hook\DataHandler\CustomJsonLdValidator` (`processDatamapClass`): rejects invalid JSON on save (previous value restored, error FlashMessage), pretty-prints valid JSON before storing, emits a soft warning when `@context` or `@type` is missing.
- Frontend rendering in `PreProcessHook::emitCustomJsonLd`: emits one minified `<script type="application/ld+json">` per top-level entity via `PageRenderer::addHeaderData`, neutralizes embedded `</script>` sequences with `JSON_HEX_TAG`, and respects `no_index`.
- Custom FormEngine node `boosterRichResultsLink` (`Belsignum\Booster\Backend\FormEngine\Element\RichResultsLinkElement`) rendering a `Google Rich Results Test` button in the `Structured Data` tab. The button targets `https://search.google.com/test/rich-results` with the current page's frontend URL (language-aware via `PreviewUriBuilder::withLanguage`).
- Language labels for the new fields and FlashMessages in `Resources/Private/Language/locallang_db.xlf`.

### Changed
- Forward-compatibility preparation for TYPO3 v13/v14 without breaking v12:
  - Replaced `\PDO::PARAM_INT` / `\PDO::PARAM_STR` with `\TYPO3\CMS\Core\Database\Connection::PARAM_INT` / `PARAM_STR` in `ContentRepository`, `RepairStructuredDataLocalizationCommand`, `PreProcessHook::emitCustomJsonLd`, and `CustomJsonLdValidator`. The TYPO3 Connection constants are available in v12.4 and remove the dependency on PDO constants that are being phased out in v13/v14 query builders.
  - Replaced the unused `Doctrine\DBAL\Connection` import in `ContentRepository` with the TYPO3 `Connection` alias.
  - Added `@todo v13/v14` anchor comments around `$GLOBALS['TSFE']` and `$controller->cObj->getRequest()` in `PreProcessHook` to mark the migration points for a future move to the PSR-14 `BeforePageRenderingEvent` listener (no behavior change in v12).

### Notes
- Run `vendor/bin/typo3 extension:setup` (or use the Install Tool's `Analyze Database Structure`) to create the new column `pages.tx_booster_custom_jsonld`.
- Flush TYPO3/PHP caches after deployment so the new TCA and FormEngine node are picked up.
- Composer and `ext_emconf.php` constraints remain locked to TYPO3 `^12.4`; widening to v13/v14 requires a separate, tested release.


## 12.2.0 - 2026-03-03
### Added
- CLI command `booster:structured-data:repair-localization` to repair localized Structured Data relations on translated pages.
- Command registration in `Configuration/Services.yaml`.
- Extended README documentation with plugin usage and legacy repair workflow.

### Changed
- Localization relation bug is fixed from Booster `12.2` onward.
- The repair command is now documented primarily as a legacy-data migration step.
- Fixed TCA localization parent mapping of `tx_booster_domain_model_content.l18n_parent` to use the correct table (`tx_booster_domain_model_content`).
- Improved page-level Structured Data TCA (`tx_booster_faqs`, `tx_booster_product`) with localization-aware configuration:
  - language-aware `foreign_table_where`
  - `behaviour.allowLanguageSynchronization = true`
  - `behaviour.localizeChildrenAtParentLocalization = true`

### Fixed
- FAQ retrieval now respects sorting from the MM relation when rendering structured data.

## 12.1.0 - 2026-02-04
### Added
- FAQ structured data (FAQPage) and FAQ accordion plugin.
- Product structured data with support for offers, aggregate rating, and reviews.
- Page-level Booster tab for managing FAQ and Product data.
