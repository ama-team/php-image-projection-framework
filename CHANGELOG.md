# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [0.2.1] - 2018-04-03

Technical release, no functionality is altered

### Changed

- Added *some* compatibility with older HHVM releases

## [0.2.0] - 2017-11-14
### Changed
- UV is now measured in standard 0.0...1.0 range (rather than in 
actual pixels)
- (internal) cube map axis orientation is changed according to popular
examples
- `createConversion()` and friends now accept 
`ConversionOptionsInterface` for last argument
- `HandlerInterface::read()` is renamed to `::createdReader` and also
adopted `ReaderOptionsInterface` as last parameter
- `AbstractHandlerInterface::createMapping(Box $size)` was
modified to `::getMapping()`
- `ProcessorInterface` now may return a new tile to replace the one it
has been fed with

### Added
- BilinearReader that produces smoother images in exchange for extra
computing resources (previous reader has been renamed to 
`NearestNeighbourReader`). It is used by default since nearest 
neighbour algorithm doesn't provide any decent quality.
- FXAA processor

## [0.1.0] - 2017-10-06
- Initial implementation with equirectangular and cube map projections
