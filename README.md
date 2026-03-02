# Booster Extension

Schema.org Structured Data extension for TYPO3.

## Requirements

- TYPO3 `^10.4`
- PHP version compatible with your TYPO3 v10 setup
- PHP extension `ext-json`
- Composer package `brotkrueml/schema` (`^2.7.1`)

## Installation

Install the extension via Composer and activate it in TYPO3.

```bash
composer require belsignum/booster
php typo3cms database:updateschema
php typo3cms cache:flush
```

For this project setup, `belsignum/booster` is loaded from `packages/booster`.

## Scope

The extension provides page-related Structured Data handling for:
- FAQ (`FAQPage`)
- Product (`Product`)

It also includes frontend JSON-LD rendering via `PreProcessHook`.

## TYPO3 v10 Localization Fixes

The following fixes are included to make localization of Structured Data relations reliable:
- Correct `l18n_parent` table mapping for `tx_booster_domain_model_content`.
- Localization-aware TCA setup for page fields:
  - `tx_booster_faqs`
  - `tx_booster_product`
- Support for language synchronization/localization of inline children.

## CLI Repair Command

To repair existing localized relations created before the fix:

```bash
php typo3cms booster:structured-data:repair-localization --dry-run
```

Run actual repair:

```bash
php typo3cms booster:structured-data:repair-localization
```

Optional filters:

```bash
php typo3cms booster:structured-data:repair-localization --dry-run --page=3662
php typo3cms booster:structured-data:repair-localization --dry-run --language=9
```

## What the Repair Command Updates

- `pages.tx_booster_product` on localized pages (maps default product to translated product record).
- `tx_booster_pages_content_mm` FAQ relations for localized pages (maps default FAQs to translated FAQs).
- `pages.tx_booster_faqs` counter for affected localized pages.

## Verification

After running the repair:
- Re-run with `--dry-run` and verify `pages_changed=0`.
- Check translated pages in backend tab `Structured Data`.
- Validate frontend JSON-LD output for translated pages (`FAQPage`/`Product`).

## Editor (Backend)

For editors working in TYPO3 backend:

- Open the target page and go to `Page Properties` -> `Structured Data`.
- Maintain FAQ entries in `tx_booster_faqs`.
- Maintain Product entry in `tx_booster_product`.
- For localized pages, use TYPO3 localization/synchronization actions in the same tab.
- If relations are missing after legacy imports, contact development/admin to run:
  - `php typo3cms booster:structured-data:repair-localization`
