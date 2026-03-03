# Booster Extension

Schema.org Structured Data extension for TYPO3.

## Requirements

- TYPO3 `^12.4`
- PHP version compatible with your TYPO3 v12 setup
- PHP extension `ext-json`
- Composer package `brotkrueml/schema` (`^3.13.0`)

## Installation

Install the extension via Composer and activate it in TYPO3.

```bash
composer require belsignum/booster
vendor/bin/typo3 database:updateschema
vendor/bin/typo3 cache:flush
```

For this project setup, `belsignum/booster` is loaded from `packages/booster`.

## Scope

The extension provides page-related Structured Data handling for:
- FAQ (`FAQPage`)
- Product (`Product`)
- FAQ accordion plugin (`plugin.tx_booster_faq`)

It also includes frontend JSON-LD rendering via `PreProcessHook`.

## Editor (Backend)

### Creating Structured Data:

- Open the target page and go to `Page Properties` -> `Structured Data`.
- Maintain FAQ entries in `tx_booster_faqs`.
- Maintain Product entry in `tx_booster_product`.
- For localized pages, use TYPO3 localization/synchronization actions in the same tab.

### FAQ Accordion Plugin

- Add a content element via `Plugins -> FAQ Accordions from Structured Data`.
- The plugin renders the FAQ list attached to the current page.
- FAQ ordering follows the MM sorting (`tx_booster_pages_content_mm`).


## Localization import bug with l10nmgr (from 12.2 fixed by default)

The localization relation issue is fixed from Booster version `12.2` onward:
- Correct `l18n_parent` table mapping for `tx_booster_domain_model_content`.
- Localization-aware TCA setup for page fields:
  - `tx_booster_faqs`
  - `tx_booster_product`
- Support for language synchronization/localization of inline children.

For existing installations with legacy data, run the repair command once.

## CLI Repair Command (Legacy Data)

To repair existing localized relations created before the fix:

```bash
vendor/bin/typo3 booster:structured-data:repair-localization --dry-run
```

Run actual repair:

```bash
vendor/bin/typo3 booster:structured-data:repair-localization
```

Optional filters:

```bash
vendor/bin/typo3 booster:structured-data:repair-localization --dry-run --page=3662
vendor/bin/typo3 booster:structured-data:repair-localization --dry-run --language=9
```
