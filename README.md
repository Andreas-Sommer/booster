# Booster (EXT:booster)

Schema.org structured data for TYPO3 pages, plus an FAQ accordion plugin.

## Features
- Adds FAQ and Product structured data (JSON-LD) on the frontend
- FAQ records are managed per page with sortable ordering
- Product data can be attached to a page
- FAQ accordion plugin for frontend rendering

## Requirements
- TYPO3 12.4
- PHP with `ext-json`
- `brotkrueml/schema` (installed as a dependency)

## Installation
1) Add the extension to your project (composer-based):
   ```bash
   composer require belsignum/booster
   ```
2) Activate the extension in the TYPO3 Extension Manager.

## Configuration
Include the TypoScript from the extension and adjust template paths if needed:

```typoscript
plugin.tx_booster {
  view {
    templateRootPath = EXT:booster/Resources/Private/Templates/
    partialRootPath = EXT:booster/Resources/Private/Partials/
    layoutRootPath = EXT:booster/Resources/Private/Layouts/
  }
}
```

The FAQ plugin uses the paths below and can be overridden via TypoScript:

```typoscript
plugin.tx_booster_faq {
  view {
    templateRootPaths.0 = EXT:booster/Resources/Private/Templates/
    partialRootPaths.0 = EXT:booster/Resources/Private/Partials/
    layoutRootPaths.0 = EXT:booster/Resources/Private/Layouts/
  }
}
```

## Usage
### FAQ structured data
- Open the page properties and go to the “Booster” tab.
- Add FAQ entries in the “FAQs” field (records are sortable).
- On the frontend, the FAQ entries are rendered as Schema.org FAQPage JSON-LD.

### Product structured data
- In the same “Booster” tab, create/select a Product record.
- The product fields map to Schema.org Product, Offers, AggregateRating, Review, etc.

### FAQ accordion plugin
- Add a content element of type “Plugins → FAQ Accordions from Structured Data”.
- The plugin renders the FAQ list (it reads the FAQ data attached to the current page).

## Notes
- Structured data output is only rendered when the `schema` extension is loaded.
- FAQ ordering follows the sorting in the MM relation for the page.

## License
GPL-2.0-or-later
