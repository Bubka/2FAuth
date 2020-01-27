/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/langs/locales.js":
/*!***************************************!*\
  !*** ./resources/js/langs/locales.js ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ({
  "en": {
    "auth": {
      "sign_out": "Sign out",
      "sign_in": "Sign in",
      "register": "Register",
      "hello": "Hi {username} !",
      "confirm": {
        "logout": "Are you sure you want to log out?"
      },
      "forms": {
        "name": "Name",
        "login": "Login",
        "email": "Email",
        "password": "Password",
        "confirm_password": "Confirm password",
        "dont_have_account_yet": "Don't have your account yet?",
        "already_register": "Already registered?",
        "password_do_not_match": "Password do not match",
        "forgot_your_password": "Forgot your password?",
        "request_password_reset": "Request a password reset",
        "reset_password": "Reset password",
        "new_password": "New password",
        "current_password": "Current password",
        "change_password": "Change password",
        "send_password_reset_link": "Send password reset link",
        "change_your_password": "Change your password",
        "password_successfully_changed": "Password successfully changed ",
        "profile_saved": "Profile successfully updated!"
      }
    },
    "commons": {
      "cancel": "Cancel",
      "update": "Update",
      "copy_to_clipboard": "Copy to clipboard",
      "profile": "Profile"
    },
    "errors": {
      "resource_not_found": "Resource not found",
      "error_occured": "An error occured:",
      "already_one_user_registered": "There is already a registered user.",
      "cannot_register_more_user": "You cannot register more than one user.",
      "refresh": "refresh",
      "please": "Please ",
      "response": {
        "no_valid_totp": "No valid TOTP resource in this QR code"
      },
      "something_wrong_with_server": "Something is wrong with your server",
      "Unable_to_decrypt_uri": "Unable to decrypt uri",
      "wrong_current_password": "Wrong current password, nothing has changed"
    },
    "pagination": {
      "previous": "&laquo; Previous",
      "next": "Next &raquo;"
    },
    "passwords": {
      "password": "Passwords must be at least eight characters and match the confirmation.",
      "reset": "Your password has been reset!",
      "sent": "We have e-mailed your password reset link!",
      "token": "This password reset token is invalid.",
      "user": "We can't find a user with that e-mail address."
    },
    "twofaccounts": {
      "service": "Service",
      "account": "Account",
      "icon": "Icon",
      "new": "New",
      "no_account_here": "No 2FA here!",
      "add_one": "Add one",
      "manage": "Manage",
      "done": "Done",
      "forms": {
        "service": {
          "placeholder": "example.com"
        },
        "account": {
          "placeholder": "John DOE"
        },
        "new_account": "New account",
        "edit_account": "Edit account",
        "totp_uri": "TOTP Uri",
        "hotp_counter": "HOTP Counter",
        "use_qrcode": {
          "val": "Use a qrcode",
          "title": "Use a QR code to fill the form magically"
        },
        "unlock": {
          "val": "Unlock",
          "title": "Unlock it (at your own risk)"
        },
        "lock": {
          "val": "Lock",
          "title": "Lock it"
        },
        "choose_image": "Choose an image…",
        "create": "Create",
        "save": "Save"
      },
      "confirm": {
        "delete": "Are you sure you want to delete this account?"
      }
    },
    "validation": {
      "accepted": "The {attribute} must be accepted.",
      "active_url": "The {attribute} is not a valid URL.",
      "after": "The {attribute} must be a date after {date}.",
      "after_or_equal": "The {attribute} must be a date after or equal to {date}.",
      "alpha": "The {attribute} may only contain letters.",
      "alpha_dash": "The {attribute} may only contain letters, numbers, dashes and underscores.",
      "alpha_num": "The {attribute} may only contain letters and numbers.",
      "array": "The {attribute} must be an array.",
      "before": "The {attribute} must be a date before {date}.",
      "before_or_equal": "The {attribute} must be a date before or equal to {date}.",
      "between": {
        "numeric": "The {attribute} must be between {min} and {max}.",
        "file": "The {attribute} must be between {min} and {max} kilobytes.",
        "string": "The {attribute} must be between {min} and {max} characters.",
        "array": "The {attribute} must have between {min} and {max} items."
      },
      "boolean": "The {attribute} field must be true or false.",
      "confirmed": "The {attribute} confirmation does not match.",
      "date": "The {attribute} is not a valid date.",
      "date_equals": "The {attribute} must be a date equal to {date}.",
      "date_format": "The {attribute} does not match the format {format}.",
      "different": "The {attribute} and {other} must be different.",
      "digits": "The {attribute} must be {digits} digits.",
      "digits_between": "The {attribute} must be between {min} and {max} digits.",
      "dimensions": "The {attribute} has invalid image dimensions.",
      "distinct": "The {attribute} field has a duplicate value.",
      "email": "The {attribute} must be a valid email address.",
      "ends_with": "The {attribute} must end with one of the following: {values}",
      "exists": "The selected {attribute} is invalid.",
      "file": "The {attribute} must be a file.",
      "filled": "The {attribute} field must have a value.",
      "gt": {
        "numeric": "The {attribute} must be greater than {value}.",
        "file": "The {attribute} must be greater than {value} kilobytes.",
        "string": "The {attribute} must be greater than {value} characters.",
        "array": "The {attribute} must have more than {value} items."
      },
      "gte": {
        "numeric": "The {attribute} must be greater than or equal {value}.",
        "file": "The {attribute} must be greater than or equal {value} kilobytes.",
        "string": "The {attribute} must be greater than or equal {value} characters.",
        "array": "The {attribute} must have {value} items or more."
      },
      "image": "The {attribute} must be an image.",
      "in": "The selected {attribute} is invalid.",
      "in_array": "The {attribute} field does not exist in {other}.",
      "integer": "The {attribute} must be an integer.",
      "ip": "The {attribute} must be a valid IP address.",
      "ipv4": "The {attribute} must be a valid IPv4 address.",
      "ipv6": "The {attribute} must be a valid IPv6 address.",
      "json": "The {attribute} must be a valid JSON string.",
      "lt": {
        "numeric": "The {attribute} must be less than {value}.",
        "file": "The {attribute} must be less than {value} kilobytes.",
        "string": "The {attribute} must be less than {value} characters.",
        "array": "The {attribute} must have less than {value} items."
      },
      "lte": {
        "numeric": "The {attribute} must be less than or equal {value}.",
        "file": "The {attribute} must be less than or equal {value} kilobytes.",
        "string": "The {attribute} must be less than or equal {value} characters.",
        "array": "The {attribute} must not have more than {value} items."
      },
      "max": {
        "numeric": "The {attribute} may not be greater than {max}.",
        "file": "The {attribute} may not be greater than {max} kilobytes.",
        "string": "The {attribute} may not be greater than {max} characters.",
        "array": "The {attribute} may not have more than {max} items."
      },
      "mimes": "The {attribute} must be a file of type: {values}.",
      "mimetypes": "The {attribute} must be a file of type: {values}.",
      "min": {
        "numeric": "The {attribute} must be at least {min}.",
        "file": "The {attribute} must be at least {min} kilobytes.",
        "string": "The {attribute} must be at least {min} characters.",
        "array": "The {attribute} must have at least {min} items."
      },
      "not_in": "The selected {attribute} is invalid.",
      "not_regex": "The {attribute} format is invalid.",
      "numeric": "The {attribute} must be a number.",
      "present": "The {attribute} field must be present.",
      "regex": "The {attribute} format is invalid.",
      "required": "The {attribute} field is required.",
      "required_if": "The {attribute} field is required when {other} is {value}.",
      "required_unless": "The {attribute} field is required unless {other} is in {values}.",
      "required_with": "The {attribute} field is required when {values} is present.",
      "required_with_all": "The {attribute} field is required when {values} are present.",
      "required_without": "The {attribute} field is required when {values} is not present.",
      "required_without_all": "The {attribute} field is required when none of {values} are present.",
      "same": "The {attribute} and {other} must match.",
      "size": {
        "numeric": "The {attribute} must be {size}.",
        "file": "The {attribute} must be {size} kilobytes.",
        "string": "The {attribute} must be {size} characters.",
        "array": "The {attribute} must contain {size} items."
      },
      "starts_with": "The {attribute} must start with one of the following: {values}",
      "string": "The {attribute} must be a string.",
      "timezone": "The {attribute} must be a valid zone.",
      "unique": "The {attribute} has already been taken.",
      "uploaded": "The {attribute} failed to upload.",
      "url": "The {attribute} format is invalid.",
      "uuid": "The {attribute} must be a valid UUID.",
      "custom": {
        "attribute-name": {
          "rule-name": "custom-message"
        },
        "icon": {
          "image": "Supported format are jpeg, png, bmp, gif, svg, or webp"
        },
        "qrcode": {
          "image": "Supported format are jpeg, png, bmp, gif, svg, or webp"
        },
        "uri": {
          "starts_with": "Only valid TOTP uri are supported"
        },
        "email": {
          "exists": "No account found using this email"
        }
      },
      "attributes": []
    }
  }
});

/***/ }),

/***/ 1:
/*!*********************************************!*\
  !*** multi ./resources/js/langs/locales.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /media/bubka/DocsDrive/Repositories/2FAuth/resources/js/langs/locales.js */"./resources/js/langs/locales.js");


/***/ })

/******/ });