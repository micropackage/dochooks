# Changelog
All notable changes to this project will be documented in this file.

## 1.1.3

### Changed:
- License from GPL to MIT

## 1.1.2

### Changed
- Regex pattern to match hook name containing any character, except space

## 1.1.1

### Fixed
- Invalid context for objects hooked using Helper
- Missing objects for dumped hooks

## 1.1.0

### Added
- Central Hooks class storing hooked objects
- `dump-hooks` binary

## 1.0.2

### Added
- HookTrait Trait

### Changed
- HookAnnotations now uses HookTrait

## 1.0.1

### Fixed
- AnnotationTest class giving a fatal error because of a wrong file name, issue #1

## 1.0.0

Initial release
