# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added
- CLI command `booster:structured-data:repair-localization` to repair localized Structured Data relations on translated pages.
- Command registration in `Configuration/Services.yaml`.

### Changed
- Fixed TCA localization parent mapping of `tx_booster_domain_model_content.l18n_parent` to use the correct table (`tx_booster_domain_model_content`).
- Improved page-level Structured Data TCA (`tx_booster_faqs`, `tx_booster_product`) with localization-aware configuration:
  - language-aware `foreign_table_where`
  - `behaviour.allowLanguageSynchronization = true`
  - `behaviour.localizeChildrenAtParentLocalization = true`

### Notes
- Existing broken relations can be repaired with:
  - `php typo3cms booster:structured-data:repair-localization --dry-run`
  - `php typo3cms booster:structured-data:repair-localization`
