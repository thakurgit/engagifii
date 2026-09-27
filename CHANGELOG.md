# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
