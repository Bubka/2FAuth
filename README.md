![Travis (.com)](https://img.shields.io/travis/com/bubka/2fauth?style=flat-square)

# 2FAuth
A web app to manage your Two-factors Auth (2FA) accounts and generate their OTP tokens

![screens](https://user-images.githubusercontent.com/858858/74479269-267a1600-4eaf-11ea-9281-415e5a54bd9f.png)

## Purpose
2FAuth is a web based self-hosted alternative to One Time Passcode (OTP) generators like Google Authenticator that you can use both on mobile or desktop.

I created it because :
* Most of the UIs for this kind of apps show tokens for all accounts in the same time with stressful countdowns (in my opinion)
* I wanted my 2FA accounts to be stored in a database I can easily backup and restore.
* I hate taking out my smartphone to get an OTP when I use a desktop computer.
* I love coding and I love self-hosted solution

## Features
* Manage 2FA accounts with QR code scanning and decoding
* Generate TOTP and HOTP tokens
* User authentication to protect access to 2FA accounts

#### Single user app
2FA are sensitives data so an authentication is needed to use the app. And because they are usually owned by the same person, it is not possible to create more than one account.

#### RFC compliance
2FAuth generates OTP according to RFC 4226 (HOTP Algorithm) and RFC 6238 (TOTP Algorithm) thanks to [Spomky-Labs/OTPHP](https://github.com/Spomky-Labs/otphp) php library.

## Requirements
* [![Requires PHP7](https://img.shields.io/badge/php-7.*-red.svg?style=flat-square)](https://secure.php.net/downloads.php)
* MySQL or SQLITE database

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

#### Set your variables
In your installation directory make a copy of the `.env.example` file and rename the copy `.env`.

Edit the `.env` file and adapt the settings to your running environment (see instructions in the file)

#### Prepare some stuff
```
php artisan migrate:refresh
php artisan passport:install
php artisan storage:link
php artisan config:cache
php artisan vue-i18n:generate
```

#### Install js dependencies
```
npm install
```

#### Build
`npm run dev` or `npm run prod`

You are ready to go.


# Contributing
to complete

# License
[AGPL-3.0](https://www.gnu.org/licenses/agpl-3.0.html)
