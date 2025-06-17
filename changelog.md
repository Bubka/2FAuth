# Change log

## [5.6.0] - 2025-06-18

Unless you are an icon lover, there isn't much to get excited about with 2FAuth v5.6 (see below for details). That's because I'm focused on refactoring the web app's front end and the web extension so that they are built using shared components. The process is time consuming, but it's a necessary step to optimize future developments and avoid repeating code.

The shared components are ready, as is a new version of the web extension that makes use of these components. I plan to migrate the 2FAuth web app as soon as possible so that I can start working on new features again.

### Added

- The _Get official icon_ feature now includes two new icon providers, [selfh.st](https://selfh.st/icons/) and [dashboardicons.com](https://dashboardicons.com/), as well as the ability to select a preferred variant or to switch between providers directly from the Advanced form. ([#475](https://github.com/Bubka/2FAuth/issues/475)).

#### New env vars

- `OPENID_HTTP_VERIFY_SSL_PEER`: Enable or disable SSL peer verification during OpenID authentication process ([doc](https://docs.2fauth.app/getting-started/configuration/#openid_http_verify_ssl_peer)).

### Changed

- Personal Access Token (PAT) can be used when authentication is restricted to SSO only. This is particularly useful when you want to use the 2FAuth web extension. Check out the new _Allow PAT usage_ setting in the Admin > Auth > SSO section ([#474](https://github.com/Bubka/2FAuth/issues/474)).

### Fixed

- [issue #477](https://github.com/Bubka/2FAuth/issues/477) Steam OTP codes don't refresh when become invalid
- [PR #482](https://github.com/Bubka/2FAuth/pull/482) Docker entrypoint not calling the right php-fpm version, thanks to [@jkoch22](https://github.com/jkoch22)

## [5.5.2] - 2025-04-11

### Fixed

- [issue #472](https://github.com/Bubka/2FAuth/issues/472) QR scan reader blocked by csp

## [5.5.1] - 2025-04-11

### Changed

- The _Show next OTP_ user preference is enabled by default

### Fixed

- [issue #472](https://github.com/Bubka/2FAuth/issues/472) QR scan reader blocked by csp

## [5.5.0] - 2025-03-27

### Announcement

Did you know that the 2FAuth official web browser extension has been released!?

The goal of this web extension is to offer an alternative way to interact with your 2FAuth server and to make 2FA account registration and OTP generation even easier and faster.

It's still in early (and beta) stage, but it's functional. For now, only OTP generation is supported, as well as the Search & Group features. Next step is to be able to capture QR codes in the browser pages. This will allow to register a 2FA account in 2FAuth during the 2FA enrollment process on the service website.

- [2FAuth web extension for Firefox](https://addons.mozilla.org/en-US/firefox/addon/2fauth-addon/)
- [2FAuth web extension for Chrome](https://chromewebstore.google.com/detail/2fauth-beta/kokhpbhfeokchmbimdlaldcmlinjpipm?hl=en)
- [2FAuth web extension for Edge](https://microsoftedge.microsoft.com/addons/detail/2fauth-beta/kgfofcnddpapmmhibkbaljffnpmcmlde)

Feedback and bug reports (in this repository please) are very welcome.

<hr/>

⚠️ This release drops PHP 8.2 support ⚠️

### Added

- It is now possible to define custom defaults for user preferences as well as to lock the preferences from being changed by users. This feature requires a bit of configuration, a [dedicated page](https://docs.2fauth.app/getting-started/config/user-preferences/) has been added to the documentation site to guide you through the process. ([#413](https://github.com/Bubka/2FAuth/issues/413))
- A user preference to enable precalculation and display of the next OTP code.  
  Don't be surprised if you don't see the next code right after enabling this option, the code fades in slowly in order to maintain good readability of the current code. ([#416](https://github.com/Bubka/2FAuth/issues/416))
- New languages: Danish, Dutch, Italian, Korean, Portuguese (Brazilian)

### Changed

- The version number has been removed from the footer and from the About page for unauthenticated users. ([#432](https://github.com/Bubka/2FAuth/issues/432))
- 2FAuth now starts searching as soon as the user starts typing, without having to explicitly give focus to the search field. ([#441](https://github.com/Bubka/2FAuth/issues/441))

### Fixed

- [issue #438](https://github.com/Bubka/2FAuth/issues/438) Sorting not working if "Service" is null
- [issue #458](https://github.com/Bubka/2FAuth/issues/458) The `/up` route no longer creates sessions
- [issue #462](https://github.com/Bubka/2FAuth/issues/462) The check for new versions is no longer triggered whereas the _Check for new version_ setting is disabled
- [PR #455](https://github.com/Bubka/2FAuth/pull/455) Logo size overflow, by [@BitSleek](https://github.com/BitSleek)
- Multiple Race Condition in Group Management Feature. Credits to [@bugdiscole](https://github.com/bugdisclose)

### API [1.7.0]

- New `403` response for the PUT operation of path `/api/v1/user/preferences/{name}`
- New `409` response for the POST operation of path `/api/v1/groups/{id}/assign`
- New `locked` property in the `userPreference` model

## [5.4.3] - 2024-11-27

### Fixed

- [issue #408](https://github.com/Bubka/2FAuth/issues/408) Deleted icon is back after saving from the advanced form
- [issue #417](https://github.com/Bubka/2FAuth/issues/417) Login page does not load after v5.4.1 update
- [issue #418](https://github.com/Bubka/2FAuth/issues/418) Opening of the footer menu submits the advanced form
- [issue #420](https://github.com/Bubka/2FAuth/issues/420) QR codes are cropped on small screens
- [issue #421](https://github.com/Bubka/2FAuth/issues/421) Freeze when switching to Manage mode
- [issue #423](https://github.com/Bubka/2FAuth/issues/423) Icon for accounts without an icon doesn't exist

### Changed

- CSS styles are no longer loaded from tailwindcss.com in the `/up` view

## [5.4.2] - 2024-11-18

### Changed

- CSP has been turned off (for now) since it breaks the app under Google Chrome. ([#417](https://github.com/Bubka/2FAuth/issues/417))

## [5.4.1] - 2024-11-17

### Security release

- Fix XSS & SSRF vulnerabilities (thx to the XBOW team).
- Content Security Policy is now available and enable by default. CSP helps to prevent or minimize the risk of certain types of security threats.  
  If CSP is already enable on your server, you can set the `CONTENT_SECURITY_POLICY` environment variable to `false` to disable it at 2FAuth level.

## [5.4.0] - 2024-11-08

### Changed

- The links in the footer (Settings, [Admin,] Sign out) have been replaced by the email address of the logged in user. Clicking on this email shows a navigation menu containing the links that were previously visible in the footer. The former display is still available if you don't like the new one, just uncheck the new _Show email in footer_ user option in Settings. ([#404](https://github.com/Bubka/2FAuth/issues/404))

### Added

- Administrators can now configure 2FAuth to register 2FA icons in the database (see the new _Store icons to database_ setting in the admin panel). When enabled, existing icons in the local file system are automatically registered in the database. These files are retained and then used for caching purposes only. 2FAuth will automatically re-create cache files if they are missing, so you only have to consider the database when backing up your instance. When disabled, 2FAuth will check that all registered icons in the database have a corresponding local file before flushing out the db icons table. ([#364](https://github.com/Bubka/2FAuth/issues/364)).
- The ability to export 2FA accounts as a list of otpauth URIs ([#386](https://github.com/Bubka/2FAuth/issues/386)).

### Fixed

- Part of the content of some pages (such as the error page) could be hidden by the footer on small screens.

### API [1.6.0]

- New `otpauth` query parameter for the GET operation of path `/api/v1/twofaccounts/export` to force data export as otpauth URIs instead of the 2FAuth json format.

## [5.3.2] - 2024-10-26

### Fixed

- [issue #402](https://github.com/Bubka/2FAuth/issues/402) Error asking me to log out when using multiple devices, pressing back logs me in anyway

## [5.3.1] - 2024-10-12

### Fixed

- [issue #396](https://github.com/Bubka/2FAuth/issues/396) PROXY_HEADER_FOR_IP not working as intended
- [issue #397](https://github.com/Bubka/2FAuth/issues/397) Base table or view not found: 1146 Table '2fauth.jobs' doesn't exist
- [issue #399](https://github.com/Bubka/2FAuth/issues/399) Cannot set CACHE_DRIVER and SESSION_DRIVER to database

## [5.3.0] - 2024-09-27

### Added

- The `/up` endpoint for health checks ([#271](https://github.com/Bubka/2FAuth/issues/271)).
- A user preference to close the on-screen OTP after a predefined delay
- A user preference to automatically register a 2FA account immediately after a QR code scan. When enabled, there is no need to click the Save button anymore to save the account to the database.
- An admin setting to make SSO the only authentication method available (does not apply to admins). ([#368](https://github.com/Bubka/2FAuth/issues/368)).
- The ability to assign a 2FA account to a specific group directly from the advanced form ([#372](https://github.com/Bubka/2FAuth/issues/372)).
- A new _Auth_ tab in the admin panel to gather settings related to authentication
- Proxy support for the OpenID connector (using `PROXY_FOR_OUTGOING_REQUESTS`), thanks to [@rstefko](https://github.com/rstefko) ([PR #367](https://github.com/Bubka/2FAuth/pull/367))

#### New env vars

A lot of new environment variables are available thanks to the Laravel 11 upgrade. They give more control over various features of the application:

- `ARGON_THREADS`: Number of threads that Argon2 will use to compute a hash.
- `ARGON_TIME`: Maximum amount of time it may take to compute an Argon2 hash.
- `ARGON_MEMORY`: Maximum memory (in kibibytes) that may be used to compute an Argon2 hash.
- `DB_CHARSET`: The character set of the database.
- `DB_COLLATION`: The collation of the database.
- `HASH_DRIVER`: The hash algorithm used to hash user passwords.
- `LOG_STACK`: The stack of log channels used when the log channel is set to `stack`.
- `LOG_DAILY_DAYS`: Number of log files to generate/rotate when using the `daily` log channel.
- `LOG_SLACK_USERNAME`: The name of the user sending the log messages when using the `slack` log channel.
- `LOG_SLACK_EMOJI`: The Emoji code of the emoji used to illustrate log messages when using the `slack` log channel.
- `LOG_SYSLOG_FACILITY`: The syslog facility that provides a rough clue of where in a system the message originated.
- `SESSION_TABLE`: Name of the table to be used to store sessions when using the database `session` driver.
- `SESSION_ENCRYPT`: Whether or not session data are encrypted before it is stored.

Please refer to the [Configuration doc](https://docs.2fauth.app/getting-started/configuration/) to find out when and how to use them.

### Changed

- The Service data field is now encrypted in the database ([#365](https://github.com/Bubka/2FAuth/issues/365)).
- Upgrade to Laravel 11

### Fixed

- [issue #347](https://github.com/Bubka/2FAuth/issues/347) Sort with ignore case
- [issue #349](https://github.com/Bubka/2FAuth/issues/349) "Show QR Code" feature returns wrong QR code
- [issue #360](https://github.com/Bubka/2FAuth/issues/360) Can’t import QR Codes from Confluence 2FA
- [issue #362](https://github.com/Bubka/2FAuth/issues/362) Cannot use SSO if app runs in subdirectory

### API [1.5.0]

- New `group_id` property for POST and PUT operations of the `/api/v1/twofaccounts` path

## [5.2.0] - 2024-05-29

2FAuth v5.2 offers a new notification feature. Each user can now decide whether they want to receive an email after a successful login from a new device, or after a failed login.

For now, both notifications are __disabled__ by default. Why this choice when this feature increases security? Because if the email configuration of your 2FAuth instance is not set up correctly, such login attempts will take a while (until all email sending attempts have failed).

If you never set up email sending on your instance, do it. It is the only way to recover your account, whether you use a password or a passkey to authenticate. To help you in this task, all required environment variables are described [here](https://docs.2fauth.app/getting-started/configuration/#email-setting). Since v5.1, administrators also have access to a test email button to validate the email configuration from the UI.

Notifications will be enabled by default in a future version.

Last but not least :

⚠️ This version drops PHP 8.1 support ⚠️

### Added

- When [installed](https://developer.mozilla.org/en-US/docs/Web/Progressive_web_apps/Guides/Installing), 2FAuth now offers shortcuts to common actions.
- User authentication logs (See user management pages in the admin area).
- Two user preferences to control the notifications sent when authentication events occur.
- A user preference to set the timezone applied to dates and times displayed in the app.

#### New env vars

- `APP_TIMEZONE`: The timezone applied to dates and times recorded to database ([doc](https://docs.2fauth.app/getting-started/configuration/#app_timezone)).
- `AUTHENTICATION_LOG_RETENTION`: The authentication log retention time, in days ([doc](https://docs.2fauth.app/getting-started/configuration/#authentication_log_retention)).
- `PROXY_HEADER_FOR_IP`: Name of the HTTP header sent by a reverse proxy to pass the original visitor IP address. ([doc](https://docs.2fauth.app/getting-started/configuration/#proxy_header_for_ip)).

### Changed

- `MAIL_DRIVER` env var renamed to `MAIL_MAILER`.  
  This is not a breaking change as the former name is still supported. This is just to stick to Laravel defaults.
- NGINX server now also listens to ipv6 in Docker image ([#336](https://github.com/Bubka/2FAuth/issues/336)).

### Fixed

- [issue #192](https://github.com/Bubka/2FAuth/issues/192) `DB_DATABASE` path not respected by entrypoint script
- [issue #244](https://github.com/Bubka/2FAuth/issues/244) gauth qr code can't be imported
- [issue #255](https://github.com/Bubka/2FAuth/issues/255) Only one Webauthn Device functioning
- [issue #295](https://github.com/Bubka/2FAuth/issues/295) Add support for PHP 8.3
- [issue #331](https://github.com/Bubka/2FAuth/issues/311) Last admin can demote to user, leaving the instance administratorless

### API [1.4.0]

- `/api/v1/users/{id}/authentications` GET path added ([doc](https://docs.2fauth.app/resources/rapidoc.html#get-/api/v1/users/-id-/authentications)).

## [5.1.1] - 2024-03-21

### Fixed

- [issue #326](https://github.com/Bubka/2FAuth/issues/326) Admin panel not working when using security device
- [issue #327](https://github.com/Bubka/2FAuth/issues/327) "Keep SSO registration enabled" is not saved

## [5.1.0] - 2024-03-08

Hey Administrators, this release is for you, a brand new Admin Panel has arrived.

With this dedicated space, you will be able to manage admin settings previously located in the User Options view (like encryption, version check, registration). Some new settings are available to better control registration (email restrictions and self-ruling SSO) and two new features are coming: Email Configuration Testing and Cache Clearing.

But the real newness is the user management. All registered accounts are now searchable, the administrator role can be granted to any user, user access (password, personal token, security key/passphrase) can be revoked and you may also delete existing users or even create new ones.

Note that the 2FAuth API has been updated with the new paths related to user management.

### Added

- A user preference to clear search results after copying a code ([#300](https://github.com/Bubka/2FAuth/issues/300)).
- A user preference to return to default group after copying a code ([#300](https://github.com/Bubka/2FAuth/issues/300)).
- The ability to submit a migration text directly in the Import view besides TXT files & QR codes loading ([#288](https://github.com/Bubka/2FAuth/issues/288)).
- An administrator setting to restrict registration to a limited range of email addresses ([#250](https://github.com/Bubka/2FAuth/issues/250)).
- An administrator setting to keep user registration via SSO enabled ([#317](https://github.com/Bubka/2FAuth/issues/317)).
- A test email feature to ensure email sending works as expected ([#307](https://github.com/Bubka/2FAuth/issues/307)).
- A Clear cache feature to... clear the cache, but from the browser ([#316](https://github.com/Bubka/2FAuth/issues/316)).
- Hindi translation, thanks to [@saxenas](https://crowdin.com/profile/saxenas)

### Changed

- User preferences & Environment variables have been moved from the About view to the new Administration panel ([#303](https://github.com/Bubka/2FAuth/issues/303)).
- Spaces are now removed from the Secret when filling out the Advanced form ([#311](https://github.com/Bubka/2FAuth/issues/311)).

### Fixed

- [issue #303](https://github.com/Bubka/2FAuth/issues/303) "Already authenticated" error message
- [issue #305](https://github.com/Bubka/2FAuth/issues/305) 403 Forbidden {message: "unauthorized"}
- [issue #315](https://github.com/Bubka/2FAuth/issues/315) "Check now" button is untranslatable
- [issue #320](https://github.com/Bubka/2FAuth/issues/320) app/Policies/OwnershipTrait contains a bug, i think

### API [1.3.0]

- `/api/v1/users` paths added to manage registered users
- `oauth_provider` property to the response body of `/api/v1/user` GET path

## [5.0.4] - 2024-02-23

### Added

- Japanese translation, thanks to [@yheuhtozr](https://crowdin.com/profile/yheuhtozr)

### Fixed

- [issue #284](https://github.com/Bubka/2FAuth/issues/284) Blank screen with version 5.0.3
- [issue #296](https://github.com/Bubka/2FAuth/issues/296) WARN Command cancelled (env=production breaks docker entrypoint)
- [issue #298](https://github.com/Bubka/2FAuth/issues/298) WebAuthn account recovery and password recovery doesn't work. Email template broken
- [issue #299](https://github.com/Bubka/2FAuth/issues/299) OID redirect behind reverse proxy

## [5.0.3] - 2024-01-19

⚠️ For everyone experiencing a blank screen after updating to v5.*, please set the `ASSET_URL` env variable to the same value as `APP_URL`.

### Added

- The `ASSET_URL` now appears in the .env.example variables next to `APP_URL`

### Fixed

- [issue #273](https://github.com/Bubka/2FAuth/issues/273) Unable to automatically paste email and password in login page
- [issue #276](https://github.com/Bubka/2FAuth/issues/276) Camera does not work
- [issue #277](https://github.com/Bubka/2FAuth/issues/277) Import 2FAS
- [issue #279](https://github.com/Bubka/2FAuth/issues/279) Cannot use stdout LOG_CHANNEL anymore

## [5.0.2] - 2023-12-29

### Fixed

- [issue #265](https://github.com/Bubka/2FAuth/issues/265) Version 5.0.1 doesn't display colored countdown segments

## [5.0.1] - 2023-12-29

### Fixed

- [issue #262](https://github.com/Bubka/2FAuth/issues/262) Missing custom base url support

## [5.0.0] - 2023-12-15

### 2FAuth v5, the not-so-major release

Why? Because most of the changes are internal and come from the Vue 3 migration. I choose the long way, the one where all components had to be rewritten to adopt the new Vue Composition API and where the whole architecture has been rethought. Thus, despite all that work, almost nothing has changed on the surface.

But it was a necessary step, especially because Vue 2 will reach End Of Life on the end of 2023. Now 2FAuth is also better prepared for futur enhancements.

Ok, so is there anything new?  
Yes, SSO.

Not so bad, right ?

The feature, bootstrapped by [@indyKoning](https://github.com/indykoning) with an OpenID provider, has been completed and now provides a Github provider as well. I plan to add more providers, tell me in the discussion which ones you would like to see. If you need help, the [docs site](https://docs.2fauth.app/security/authentication/sso/) has been updated to guide you through the setup process.

v5 also comes with the following.

### Added

- Single Sign-On (SSO) is now available as an authentication method, with OpenID & Github. Contributed by [@indyKoning](https://github.com/indykoning) ([PR #243](https://github.com/Bubka/2FAuth/pull/243))
- The ability to reveal passwords obscured with dots. See the Options tab in Settings ([#208](https://github.com/Bubka/2FAuth/issues/208)).
- An env var to set a proxy for outgoing requests ([#252](https://github.com/Bubka/2FAuth/issues/252)).

### Changed

- Automatic signed out user now lands on the Login view instead of the Autolock view ([#138](https://github.com/Bubka/2FAuth/issues/138))
- User preferences that depend on another now appear indented
- Letters with diacritic marks are allowed in Group name ([#241](https://github.com/Bubka/2FAuth/issues/241))
- Request body threshold increased to 10Mo in the Docker image to allow importing large file ([#239](https://github.com/Bubka/2FAuth/issues/239))

### Removed

- [PR #247](https://github.com/Bubka/2FAuth/pull/247), [PR #248](https://github.com/Bubka/2FAuth/pull/248), [PR #249](https://github.com/Bubka/2FAuth/pull/249) Useless env var, thanks to [@rouilj](https://github.com/rouilj)

### Fixed

- [issue #253](https://github.com/Bubka/2FAuth/issues/253) 2FAs exports cannot be imported

### API [1.2.0]

- `/api/v1/user` GET path added
- `ids` and `withOtp` query parameters added to the `/api/v1/twofaccounts` GET path

---

__Full Changelog__: [v4.2.4...v5.0.0](https://github.com/Bubka/2FAuth/compare/v4.2.4...v5.0.0)

## [4.2.4] - 2023-11-21

### Changed

- [PR #242](https://github.com/Bubka/2FAuth/pull/242) The Docker image now embed the PostgreSQL PHP extensions, thanks to [@stavros-k](https://github.com/stavros-k)

### Fixed

- [PR #235](https://github.com/Bubka/2FAuth/pull/235) Fix build badge broken, thanks to [@sy-records](https://github.com/sy-records)

## [4.2.3] - 2023-09-26

### Fixed

- [issue #232](https://github.com/Bubka/2FAuth/issues/232) Vendor.js throws error making frontend unusable
- [issue #233](https://github.com/Bubka/2FAuth/issues/233) The Close button of the 404 error page loops the page on itself

## [4.2.2] - 2023-09-17

### Fixed

- [issue #232](https://github.com/Bubka/2FAuth/issues/232) Vendor.js throws error making frontend unusable

## [4.2.1] - 2023-09-14

### Fixed

- [issue #227](https://github.com/Bubka/2FAuth/issues/227) PAT and Webauthn registration broken

## [4.2.0] - 2023-09-13

### Added

- An Only for the brave feature: ctrl + click a TOTP account from the main view automatically generates a password and copies it to the clipboard without displaying it at all. Will the password be valid at the time you paste it? Nobody knows 💀
- The `MAIL_VERIFY_SSL_PEER` environment variable to disable SSL peers verification ([#219](https://github.com/Bubka/2FAuth/issues/219)).
- Russian translation, but partial. Want to help complete this translation? ➡️ [2FAuth project on Crowdin](https://crowdin.com/project/2fauth).

### Changed

- Navigation with the __Back__ and __Close__ buttons is now fully consistent with their labeling, even when browsing back through successive views using those buttons.
- The length of the email submitted during registration is now limited to 191 characters ([#214](https://github.com/Bubka/2FAuth/issues/214)).
- Upgrade to Laravel 10

### Fixed

- [issue #213](https://github.com/Bubka/2FAuth/issues/213) `checkForUpdate` value is missing in the About view
- Inconsistent page titles

---

__Full Changelog__: [v4.1.0...v4.2.0](https://github.com/Bubka/2FAuth/compare/v4.1.0...v4.2.0)

## [4.1.0] - 2023-07-07

This new version introduces a very common feature in the 2FA app world, the automatic generation and display of passwords.

Since the very beginning, 2FAuth offers an _Open, Click & Get one password_ behavior, this is one of the main reasons why I created it. But this can be very troublesome or frustrating for users migrating from other 2FA apps as almost all of them work with an _Open & Get passwords_ behavior, which is much more straightforward.

So this is now only a user choice as 2FAuth offers both behaviors via a user preference. Obvisouly, the _Open, Click & Get one password_ behavior remains the default one.

### Added

- A user preference to generate and show 2FA passwords on the main view without user interaction ([#153](https://github.com/Bubka/2FAuth/issues/153))
- An administrator setting to disable user registration ([#170](https://github.com/Bubka/2FAuth/issues/170))
- A `2fauth:install` Artisan command to ease both initial and upgrade installation.
- A spinner, during 2FA password loading - By [@josh-gaby](https://github.com/josh-gaby).
- Russian translation, but partial. Want to help complete this translation? ➡️ [2FAuth project on Crowdin](https://crowdin.com/project/2fauth).

### Changed

- Aegis migrations with empty `name` properties are no longer rejected. The `issuer` property is then used as a fallback value.
- The Docker image now embed the MySQL/MariaDB PHP extension, so it may be ready to work with.

### Fixed

- [issue #180](https://github.com/Bubka/2FAuth/issues/180) OTP does not rotate while _Close after copy_ and _Copy on display_ is activated - By [@josh-gaby](https://github.com/josh-gaby)
- [issue #194](https://github.com/Bubka/2FAuth/issues/194) Container keeps trying to make connection to 172.67.161.186
- [issue #134](https://github.com/Bubka/2FAuth/issues/134), [#143](https://github.com/Bubka/2FAuth/issues/143), [#147](https://github.com/Bubka/2FAuth/issues/147) Issue with some Microsoft 2FA
- [issue #196](https://github.com/Bubka/2FAuth/issues/196) ERROR The [public/storage] link already exists

## [4.0.3] - 2023-06-30

### Security release

- Fix possible SQL injection in validation rule (thx [@YouGina](https://github.com/YouGina))
- Fix various possible XSS injections (thx [@quirinziessler](https://github.com/quirinziessler))

## [4.0.2] - 2023-04-19

### Fixed

- [issue #176](https://github.com/Bubka/2FAuth/issues/176) Lost keys when upgrading to 4.x whilst using proxy header authentication

## [4.0.1] - 2023-04-16

### Fixed

- [issue #174](https://github.com/Bubka/2FAuth/issues/174) PHP Fatal error after latest Update

## [4.0.0] - 2023-04-14

Time for multi-user has arrived, here comes v4.0!

This is a first step mainly dedicated to internal changes, so the feature has been integrated gently. For now, almost nothing has changed around user management, except that registrations are opened to new users and some options are only available to the administrator.

This version also comes with nice additions. A light theme, an export feature or the support of custom base url just to name a few.

⚠️ This release drops PHP 8.0 support ⚠️

### Added

- An Export feature (accessible via the _Manage_ view) that lets you download your 2FA data in a JSON migration file
- The Import feature accepts the 2FAuth JSON file generated by the Export feature
- Support of custom base URL. You can now install 2FAuth in a domain sub-directory, e.g `https://mydomain/2fauth/` (see [Docs](https://docs.2fauth.app/getting-started/installation/self-hosted-server//#subdirectory))
- ctrl+F keyboard shortcut to focus on Search on the main view
- A light theme
- IP addresses of failed login attempts are now logged

### Changed

⚠️ 2FAuth uses a new component to operate the WebAuthn authentication that cannot use existing registrations of your security devices. As a consequence, all your security devices will be revoked and the "Use Webauthn only" option will be disabled during the upgrade to avoid any issue and/or lockout. You will have to sign in using your email and password to re-register you security devices.

- The _Manage_ view layout has been rearranged: The search bar remains and the action buttons now stand in the page footer
- Password formatting is now a user option available with 3 formats: Grouping digits by pair, by trio or by half
- Failed login throttling and API calls throttling can be configured in the .env file
- Logs give more information
- Upgrade to Laravel 9.0

### Removed

- The ability to set a Secret in a plain text format (in the advanced form). This was confusing and without any benefit.

### Fixed

- [issue #166](https://github.com/Bubka/2FAuth/issues/166) Unable to register Nitrokey

## [3.4.2] - 2023-01-25

### Fixed

- [issue #160](https://github.com/Bubka/2FAuth/issues/160) Steam otpauth URI from Aegis are rejected by the Import feature

## [3.4.1] - 2022-11-25

### Fixed

- [issue #140](https://github.com/Bubka/2FAuth/issues/140) Bad regex for Period field (advanced form)
- [issue #141](https://github.com/Bubka/2FAuth/issues/141) Digits field is missing in advanced form

## [3.4.0] - 2022-10-20

This release is a big step towards more accessibility. Keyboard navigation is now fully supported, with clean and consistent focus, and several UI components have received relevant ARIA properties to support assistive technologies.

It also provides a rewritten Import feature that supports new export formats (Aegis and 2FAS Authenticators) and more to come.

⚠️ This release should be the last that supports PHP 8.0

### Added

- An option to check for new release on Github ([#127](https://github.com/Bubka/2FAuth/issues/127))
- An option to automatically copy One-Time Passwords when they are displayed ([#125](https://github.com/Bubka/2FAuth/issues/125))
- [Aegis](https://github.com/beemdevelopment/Aegis) and [2FAS](https://2fas.com/) export formats are now supported by the Import feature ([#128](https://github.com/Bubka/2FAuth/issues/128))
- (Partial) Spanish and Chinese (simplified) localizations

### Changed

- Password fields can reveal the password and inform about the password strength ([#124](https://github.com/Bubka/2FAuth/issues/124))

### Fixed

- [issue #126](https://github.com/Bubka/2FAuth/issues/126) HOTP counters are not updated after OTP generation
- Autolock setup ignored when session lifetime was shorter, causing CSRF token mismatch errors

## [3.3.3] - 2022-08-16

### Fixed

- [issue #110](https://github.com/Bubka/2FAuth/issues/110) Can't sign in with login/password after the removal of the last webauthn device
- [issue #111](https://github.com/Bubka/2FAuth/issues/111) Inappropriate notification about existing user during registration
- [issue #113](https://github.com/Bubka/2FAuth/issues/113) Password reset does not work
- [issue #115](https://github.com/Bubka/2FAuth/issues/115) WEBAUTHN_NAME .env variable set as null generates server error

## [3.3.1-3.3.2] - 2022-08-02

### Fixed

- [issue #109](https://github.com/Bubka/2FAuth/issues/109) Timeout right after login

## [3.3] - 2022-08-01

⚠️ This release drops PHP 7.4 support ⚠️

The [docker image](https://hub.docker.com/r/2fauth/2fauth) has been upgraded as well.

### Added

- An option to fetch icons automatically from [2factorauth/twofactorauth](https://github.com/2factorauth/twofactorauth) ([#99](https://github.com/Bubka/2FAuth/issues/99))
- An _About_ page, accessible from the footer ([#91](https://github.com/Bubka/2FAuth/issues/91))
- Alphabetical sorting feature ([#95](https://github.com/Bubka/2FAuth/issues/95))

### Changed

- The footer is now visible everywhere to ease access to the _About_ page

### Fixed

- [issue #89](https://github.com/Bubka/2FAuth/issues/89) Deploy to Heroku fails without `composer.lock`
- [issue #102](https://github.com/Bubka/2FAuth/issues/102) OTP generation from the Create/Edit form with invalid data should display errors
- [issue #103](https://github.com/Bubka/2FAuth/issues/103) Google Authenticator import error: "Label contains a colon"
- [issue #109](https://github.com/Bubka/2FAuth/issues/109) Account creation/import fails when encryption is On

### Removed

- PHP 7.4 support

## [3.2] - 2022-07-18

### Added

- Support of Google Authenticator migration data: QR codes generated by the G-Auth export feature can be flashed/uploaded to import their data into 2FAuth. ([Import doc](https://docs.2fauth.app/usage/import), [#74](https://github.com/Bubka/2FAuth/issues/74))
- Partial support of STEAM TOTP. See the [Steam Guard doc](https://docs.2fauth.app/usage/steam-guard) for detailed informations about this support ([#30](https://github.com/Bubka/2FAuth/issues/30))

### Changed

- Pages now have a unique title
- Signing in while already authenticated no longer display the "_Already authenticated_" error message ([#88](https://github.com/Bubka/2FAuth/issues/88))
- The Auto lock feature now forwards to a dedicated page to ensure proper logout and prevent CSRF token mismatch error (see [issue #73](https://github.com/Bubka/2FAuth/issues/73)) that still occurred in certain situation

### Fixed

- [issue #90](https://github.com/Bubka/2FAuth/issues/90) Empty page after deletion of all accounts
- [issue #97](https://github.com/Bubka/2FAuth/issues/97) Secret's format selector should not clear the locked field in edit form

## [3.1.1] - 2022-05-31

### Fixed

- [issue #85](https://github.com/Bubka/2FAuth/issues/57), [issue #86](https://github.com/Bubka/2FAuth/issues/86) Invalid OTP generated after the 2FA account has been saved to db

## [3.1.0] - 2022-05-20

### Added

- `PROXY_LOGOUT_URL` environment variable to specify a custom logout url when using an auth proxy
- Locked/Unlocked state for the _Secret_ field in the 2FA account Edit form to prevent undesirable edit.

### Fixed

- Fix OAuth setting view returning an error when auth is handled by a proxy
- [issue #57](https://github.com/Bubka/2FAuth/issues/57) Can't save icons or upload QR codes - Docker installation
- [issue #81](https://github.com/Bubka/2FAuth/issues/81) Unable to create configured logger. Using emergency logger
- [issue #82](https://github.com/Bubka/2FAuth/issues/82) Autolock feature should be disabled while auth is handled by a proxy
- [issue #84](https://github.com/Bubka/2FAuth/issues/84) Reverse-proxy-guard authenticates request without valid headers configuration

## [3.0.2] - 2022-05-14

### Added

- Mail settings section in the docker readme by [@aronmal](https://github.com/aronmal)

### Fixed

- [issue #72](https://github.com/Bubka/2FAuth/issues/72) 2FA secret passed as plain text rejected by form validation
- [issue #73](https://github.com/Bubka/2FAuth/issues/73) CSRF token mismatch
- [issue #78](https://github.com/Bubka/2FAuth/issues/78) Add tags other then latest when pushing images to dockerhub

## [3.0.1] - 2022-05-11

### Fixed

- [issue #68](https://github.com/Bubka/2FAuth/issues/68) 2fauth not run after update
- [issue #71](https://github.com/Bubka/2FAuth/issues/71) Cannot view old TOTP entries on latest Docker Image
- Missing login information on the demo website

## [3.0.0] - 2022-05-09

Finally, here is version 3.0!

This is a milestone in the 2FAuth development that greatly enhances 2FAuth under the hoods and comes with a [brand new documentation](https://docs.2fauth.app/).

### New

- 2FAuth now exposes a REST API following the OpenAPI 3.1 specification that allows connexion with third parties (see the [API doc](https://docs.2fauth.app/api/))
- Support of the _Web Authentication_ standard, aka WebAuthn, to login using a security device like a Yubikey or FaceID
- Support of authentication proxy to bypass the 2FAuth auth login
- Heroku setup to deploy 2FAuth using the _Deploy to Heroku_ button

#### Also added

- Ability to delete the user account and reset 2FAuth
- The content of any non-2FA QR code can be copied or followed (in case of an HTTP link)
- PHP 8.0 support

### Changed

- 2Fauth now uses the browser language preference by default.
- The current group is now clickable in the group selector
- Upgrade to Laravel 8

### Fixed

- [issue #45](https://github.com/Bubka/2FAuth/issues/45) Account or Service field containing colon breaks the Test feature in the advanced form
- [issue #47](https://github.com/Bubka/2FAuth/issues/47) Account creation fails when otpauth service parameter is missing
- [issue #50](https://github.com/Bubka/2FAuth/issues/50) Email password reset does not work
- [issue #51](https://github.com/Bubka/2FAuth/issues/51) Cannot delete a group with accounts (MySQL only)
- [issue #52](https://github.com/Bubka/2FAuth/issues/52) null "Default group" setting after group delete
- [issue #57](https://github.com/Bubka/2FAuth/issues/57) Can't save icons or upload QR codes - Docker installation

### Removed

- PHP 7.3 support

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
