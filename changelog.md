# Change log

## [2.1.0] - 2021-03-04

### Added
- German translation, thanks to [@chenmichael](https://crowdin.com/profile/chenmichael)

## [2.0.2] - 2020-12-04

### Fixed
- [issue #20](https://github.com/Bubka/2FAuth/issues/20) Issues using 'Protect sensible data'

## [2.0.1] - 2020-12-03

### Fixed
- [issue #18](https://github.com/Bubka/2FAuth/issues/18) Install using MySQL causes exception
- [issue #17](https://github.com/Bubka/2FAuth/issues/17) Capitalization of email address during login should not matter
- [issue #15](https://github.com/Bubka/2FAuth/issues/15) Applied group filter is not removed if the group is deleted
- [issue #14](https://github.com/Bubka/2FAuth/issues/14) Cache is not refreshed automatically after group changes
- Missing footer links at first start
- Missing redirection after registration

## [2.0.0] - 2020-11-29

2FAuth goes to v2.0!

This release comes with multiple improvements and a lot of changes under the hood.
Don't forget to backup your database before you upgrade. Have fun :)

### Added
- Add Groups to enhance accounts management
- New advanced form to define fully customized accounts without QR code
- New user option to skip the submitting page
- New DB protection option to encrypt sensitive 2FA data
- QR code generation of recorded accounts
- Support of the OTP `image` parameter when a QR code is imported

### Changed
- Performance improvement thanks to data caching
- Show Register/Login forms and their links only when relevant
- Let the user choose between all available submitting methods (livescan, qrcode upload, advanced form)
- Translations are now managed on [Crowdin.com/2fauth](https://crowdin.com/project/2fauth). You master some foreign languages? Why not help translate 2FAuth, your help would be welcome.

### Fixed
- [issue #13](https://github.com/Bubka/2FAuth/issues/13) Long Service name push content out of viewport
- [issue #11](https://github.com/Bubka/2FAuth/issues/11) Token generation do not loop if TOTP period is different from 30s
- [issue #9](https://github.com/Bubka/2FAuth/issues/9) Upload QR code in standard form return a 422 missing uri field

## [1.3.1] - 2020-10-12

### Changed
- Upgrade to Laravel 7.0
- Drop PHP 7.2 support
- Enable the Request reset password form in Demo mode but inactivated

### Fixed
- Fix missing notifications in Auth views

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