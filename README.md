![https://travis-ci.com/github/Bubka/2FAuth](https://img.shields.io/travis/com/bubka/2fauth?style=flat-square)
![https://codecov.io/gh/Bubka/2FAuth](https://img.shields.io/codecov/c/github/Bubka/2FAuth?style=flat-square)
![https://github.com/Bubka/2FAuth/blob/master/LICENSE](https://img.shields.io/github/license/Bubka/2FAuth.svg?style=flat-square)


# 2FAuth
A web app to manage your Two-Factor Authentication (2FA) accounts and generate their security codes

![screens](https://user-images.githubusercontent.com/858858/100485897-18c21400-3102-11eb-9c72-ea0b1b46ef2e.png)

#### [2FAuth Demo](https://demo.2fauth.app/)

Credentials (login - password) : *demo@2fauth.app* - *demo*

## Purpose
2FAuth is a web based self-hosted alternative to One Time Passcode (OTP) generators like Google Authenticator, designed for both mobile and desktop.

It aims to ease you perform your 2FA authentication steps whatever the device you handle, with a clean and suitable interface.

I created it because :
* Most of the UIs for this kind of apps show tokens for all accounts in the same time with stressful countdowns (in my opinion)
* I wanted my 2FA accounts to be stored in a standalone database I can easily backup and restore (did you already encountered a smartphone loss with all your 2FA accounts in Google Auth? I did...)
* I hate taking out my smartphone to get an OTP when I use a desktop computer
* I love coding and I love self-hosted solutions

## Main features
* Manage your 2FA accounts and organize them using Groups
* Scan and decode any QR code to add account in no time
* Add custom account without QR code thanks to an advanced form
* Edit accounts, even the imported ones
* Generate TOTP and HOTP security codes

2FAuth is currently fully localized in English and French. See [Contributing](#Contributing) if you want to help on adding more languages.

## Security

2FAuth provide with several security mechanisms to protect your 2FA data as best as possible.

#### Single user app
You have to create a user account and authenticate yourself to use the app. It is not possible to create more than one user account, the app is thought for personal use.

#### Data encryption
Sensitive data stored in the database can be encrypted to protect them against db compromise. Encryption is provided as an option which is disabled by default. It is strongly recommanded to backup the APP_KEY value of your .env file (or the whole file) when encryption is On.

#### Auto logout
2FAuth automatically log you out after an inactivity period to prevent long life session. The auto logout can be deactivated or triggered when a security code is copied.

#### RFC compliance
2FAuth generates OTP according to RFC 4226 (HOTP Algorithm) and RFC 6238 (TOTP Algorithm) thanks to [Spomky-Labs/OTPHP](https://github.com/Spomky-Labs/otphp) php library.

## Requirements
[![Requires PHP7](https://img.shields.io/badge/php-7.3.*-red.svg?style=flat-square)](https://secure.php.net/downloads.php) 
* See [Laravel server requirements](https://laravel.com/docs/7.x/installation#server-requirements)
* Any database [supported by Laravel](https://laravel.com/docs/7.x/database)

## Installation (using command line)

#### Clone the repo
```
git clone https://github.com/bubka/2fauth.git
```

#### Install all php dependencies
```
php composer.phar install
```
Don't have `composer`? [you can get it here](https://getcomposer.org/download/)

#### Set up your database

Create a database with one of the supported tools (see Requirements).
For SQLite, place the database `.sqlite` file in the `database/` folder of your 2FAuth installation.

#### Set your variables

In your installation directory make a copy of the `.env.example` file and rename the copy `.env`.
Edit the `.env` file and adapt the settings to your running environment (see instructions in the file)

#### Prepare some stuff
```
php artisan migrate:refresh
php artisan passport:install
php artisan storage:link
php artisan config:cache
```
You are ready to go.

#### For development only
Checkout the 'dev' branch then install and build js dependencies
```
npm install
npm run dev
```

## Upgrading
First, **backup your database**.

Then, using command line :
```
git pull
php composer.phar install
php artisan migrate
php artisan config:clear
```

# Contributing
You can contribute to 2FAuth in many ways:

- By [reporting bugs](https://github.com/Bubka/2FAuth/issues/new?template=bug_report.md), or even better, by submitting a fix with a pull request on the *dev* branch.
- By [suggesting enhancement or new feature](https://github.com/Bubka/2FAuth/issues/new?template=feature_request.md). Please have a look to the [2FAuth development project](https://github.com/Bubka/2FAuth/projects/2), maybe your idea is already there.
- By correcting or completing translations in a language you speak, using the [Crowdin platform](https://crowdin.com/project/2fauth). Ask for your language if this one is lacking.

# License
[AGPL-3.0](https://www.gnu.org/licenses/agpl-3.0.html)
