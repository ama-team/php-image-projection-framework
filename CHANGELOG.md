# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
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

### Added
- BilinearReader that produces smoother images in exchange for extra
computing resources (previous reader has been renamed to 
`NearestNeighbourReader`). It is used by default since nearest 
neighbour algorithm doesn't provide any decent quality$.

## [0.1.0] - 2017-10-06
- Initial implementation with equirectangular and cube map projections
