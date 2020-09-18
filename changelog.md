# Change log

## [1.2.0] - 2020-09-18

### Added
- QR Code scan using live stream when a camera is detected. Previous QR Code scanner remains available as fallback method or can be forced in Settings.
- New alternative layouts: List or Grid
- Accounts can be reordered

### Changed
- Notification banner (when saving settings) now has a fixed position

## [1.1.0] - 2020-03-23

### Added
- Demonstration mode with restricted features and ability to reset content with an artisan command
- Option to close token popup when the code is pasted (by clicking/taping on it)

### Changed
- Options default values can now be set in config/app
- Generated assets are now part of the repo to ease deployement

### Fixed
- Option labels attached to wrong checkboxes