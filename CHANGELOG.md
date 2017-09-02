Changelog
=========
## 1.0.0-beta.5 - 2017-09-02
### Changed
- Moved flipbox\transformer\behaviors\Transform behavior flipbox\transformer\filters\Transform

### Added
- AccessTransform filter for access based data transforming

## 1.0.0-beta.4 - 2017-08-29
### Changed
- Transformer::resolveTransformer is now a public method.

### Fixed
- Issue where FieldTransformer trait was trying to get a class of a non-object
- Issue where ModelTransformer trait was trying to get a class of a non-object
- Incorrect exceptions being thrown when getting transformers

## Added
- Twig collection function now supports 'context' property
- A transform behavior which applies an afterEvent to controllers

## 1.0.0-beta.3 - 2017-06-21
### Fixed
- Issue where ElementTransformer trait was trying to get a class of a non-object

## 1.0.0-beta.2 - 2017-06-12
### Changed
- Major refactoring

## 1.0.0-beta.1 - 2017-05-22
### Changed
- Move all transformers into base transformer service

### Added
- Admin panel UI (incomplete)
- Scopes logic to transformer service

### Removed
- Element/Field/Model sub modules

## 1.0.0-beta - 2017-04-26
Initial release.
