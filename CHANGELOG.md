# Changelog

## 4.0.0

### Added
- Support for Laravel 12 and 13.
- Support for PHP 8.4 and 8.5.

### Changed (breaking)
- `intervention/image` upgraded from v2 to v3 (`^3.11`). Version 2 is abandoned and emits deprecations on
  PHP 8.4+. If your application also uses intervention/image directly (e.g. `ImageManagerStatic` or
  `Image::make()`), migrate that code to the v3 API: <https://image.intervention.io/v3/introduction/upgrade>.
- Image processing uses the GD driver; `ext-gd` is required.

### Upgrade notes
- The public API of this package is unchanged. Responsive images are still resized to the configured
  sizes with their aspect ratio preserved, and are stored in their original format.

### Internal
- Test suite migrated to PHPUnit attributes; runs on PHPUnit 10.5, 11 and 12.
- CI matrix covers PHP 8.1–8.5 against Laravel 10–13, with lowest and stable dependencies.
- Removed `minimum-stability: dev` so development installs resolve stable releases.

## 3.0.0

- Laravel 10 support.
