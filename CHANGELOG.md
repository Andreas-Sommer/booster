# Changelog

All notable changes to this project will be documented in this file.
The format is based on Keep a Changelog, and this project adheres to Semantic Versioning.

## Unreleased

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
