# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.2.5] - 2026-10-03

### Changed
- Switched plugin update mechanism from remote JSON to GitHub Releases API
- Plugin banners and icons now use `ENGAGIFII_ASSETS_URL` constant instead of hardcoded remote URLs
- Enabled profile feature for MHA

### Improved
- Added README.md with plugin description for GitHub repository
- Excluded `release_notes.md` from plugin zip build

## [2.2.4] - 2026-09-27

### Added
- GitHub Release workflow for plugin building and distribution

### Changed
- Updated plugin re-install URL to point to GitHub release download link
- Restructured plugin files to repository root

### Fixed
- Preserved shortcode organization status when applying filters
- Resolved SSO logout conflict by removing priority 10 logout hook
- Merged exhibitor fields into a single Overview box with a divider
- Refined organization detail sections and hidden empty card fields
- Displayed organization physical address inline on card view and detail header
- Fixed mobile filter alignment on organization card view

## [2.2.3] - 2026-06-12

- Organization filters enhanced

## [2.2.2] - 2026-05-04

- Organization Directory Added

## [2.2.1] - 2026-02-05

- Security Enhancements

## [2.2.0] - 2026-01-22

- Pages selection/creation for plugin modules
- Added session fallback for Legislation widgets
- Added post state after page name

## [2.1.0] - 2026-01-20

- Updated Page settings
- Fixed Minor security bugs

## [2.0.0] - 2026-01-13

- New improved UI for plugin settings
- Security enhancements

## [1.6.2] - 2025-09-19

- Bugs fixed
- Pages creation function optimized on plugin activation

## [1.6.1] - 2025-09-12

- Bugs fixed

## [1.6.0] - 2025-08-28

- Dashboard settings UI enhancement
- Groups members filters added
- Columns APIs optimized for all modules
- Manage Member Tabs Visibility

## [1.5.0] - 2025-06-27

- Org Directory
- Bill details page tabs settings
- Group members/Org directory admin settings UI enhancement

## [1.4.0] - 2025-05-27

- Group Members Module integrated
- MyPSBA Classes module integration
- Classes/Events visibility settings

## [1.3.0] - 2025-04-16

- Enable/Disable Fontawesome
- Added feature to update plugin from own server
