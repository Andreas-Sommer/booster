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
- Custom JSON-LD (free-form, per page)
- FAQ accordion plugin (`plugin.tx_booster_faq`)

It also includes frontend JSON-LD rendering via `PreProcessHook`.

## Editor (Backend)

### Creating Structured Data:

- Open the target page and go to `Page Properties` -> `Structured Data`.
- Maintain FAQ entries in `tx_booster_faqs`.
- Maintain Product entry in `tx_booster_product`.
- Maintain free-form JSON-LD in `tx_booster_custom_jsonld` (see below).
- Use the `Google Rich Results Test` button to validate the current page in Google's external tool (new browser tab).
- For localized pages, use TYPO3 localization/synchronization actions in the same tab.

### Custom JSON-LD

The `Custom JSON-LD` field accepts a single JSON-LD object or an array of objects. It is rendered as a `t3editor` instance with JSON syntax highlighting.

To register **multiple** entities at once, wrap them in square brackets and separate them with commas:

```json
[
  { "@context": "https://schema.org", "@type": "Organization", "name": "Example" },
  { "@context": "https://schema.org", "@type": "WebSite", "url": "https://example.com" }
]
```

Each top-level entity in the array will be emitted as its own `<script type="application/ld+json">` tag in the page head, which is the form Google recommends.

Validation on save (DataHandler hook `CustomJsonLdValidator`):
- Empty value is accepted.
- Invalid JSON is rejected; the previous value is restored and an error FlashMessage is shown.
- Valid JSON is canonicalized to a pretty-printed (unminified) representation before being stored, so the field stays readable in the backend.
- A soft warning FlashMessage is emitted if any top-level entity is missing `@context` or `@type`. The value is still saved.

Frontend rendering (`PreProcessHook::emitCustomJsonLd`):
- Reads the stored field for the current (language-resolved) page UID.
- Splits a top-level JSON array into one `<script type="application/ld+json">` tag per entity (Google recommends separate tags per entity).
- Emits minified JSON via `json_encode($entity, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)`. `JSON_HEX_TAG` neutralizes any `<` / `>` inside string values, so a stray `</script>` substring in user content cannot break out of the surrounding tag.
- Suppressed when the page has `no_index > 0`, identical to the existing FAQ/Product output.
- Bypasses `brotkrueml/schema`'s `SchemaManager` (which only supports typed schema models) and writes directly via `PageRenderer::addHeaderData`.

### Google Rich Results validator button

The button is rendered by the custom FormEngine node `boosterRichResultsLink` (registered in `ext_localconf.php`). It builds the frontend URL via `\TYPO3\CMS\Backend\Routing\PreviewUriBuilder` with the current `sys_language_uid`, URL-encodes it, and links to `https://search.google.com/test/rich-results?url=<encoded-fe-url>` in a new tab.

For new (unsaved) pages or pages without a resolvable preview URL, the button is replaced with a hint asking the editor to save first. The validator only accepts publicly reachable URLs.

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
