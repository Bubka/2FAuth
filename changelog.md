# Change log

## [1.3.0] - 2020-10-09

### Added
- Application lock on security code copy or after a fixed period of inactivity
- Notify user that https is required in order to use camera streaming to flash QR code
- Notify user that the security code has been copied to clipboard when user click it
- Show selected accounts count in Manage view
- New option to show/hide icons in accounts list

### Changed
- More mobile friendly Close button for modal
- More advanced notification component
- Fixed header to keep Search field and Delete button always visible
- Switches replaced by checkboxes in Settings

### Fixed
- Hide context around iPhone X+ notch
- Unwanted access to restricted pages as guest

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