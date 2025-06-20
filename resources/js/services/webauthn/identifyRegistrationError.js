/**
 * MIT License
 *
 * Copyright (c) 2023 Bubka - https://github.com/Bubka/2FAuth
 * Copyright (c) 2020 Matthew Miller - https://github.com/MasterKale/SimpleWebAuthn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 */

import { isValidDomain } from './isValidDomain';

/**
 * Attempt to intuit _why_ an error was raised after calling `navigator.credentials.create()`
 */
export function identifyRegistrationError(error, options) {
    const { publicKey } = options;

    if (error.name === 'AbortError') {
        if (options.signal instanceof AbortSignal) {
            // Registration ceremony was sent an abort signal
            // https://www.w3.org/TR/webauthn-2/#sctn-createCredential (Step 16)

            return {
                phrase: 'error.aborted_by_user',
                type: 'is-warning'
            }
        }

    } else if (error.name === 'ConstraintError') {
        if (publicKey.authenticatorSelection?.requireResidentKey === true) {
            // Discoverable credentials were required but no available authenticator supported it
            // https://www.w3.org/TR/webauthn-2/#sctn-op-make-cred (Step 4)
            
            return {
                phrase: 'error.authenticator_missing_discoverable_credential_support',
                type: 'is-danger'
            }

        } else if (publicKey.authenticatorSelection?.userVerification === 'required') {
            // User verification was required but no available authenticator supported it
            // https://www.w3.org/TR/webauthn-2/#sctn-op-make-cred (Step 5)

            return {
                phrase: 'error.authenticator_missing_user_verification_support',
                type: 'is-danger'
            }
        }

    } else if (error.name === 'InvalidStateError') {
        // The authenticator was previously registered
        // https://www.w3.org/TR/webauthn-2/#sctn-createCredential (Step 20)
        // https://www.w3.org/TR/webauthn-2/#sctn-op-make-cred (Step 3)
        
        return {
            phrase: 'error.security_device_already_registered',
            type: 'is-danger'
        }

    } else if (error.name === 'NotAllowedError') {
        /**
         * Pass the error directly through. Platforms are overloading this error beyond what the spec
         * defines and we don't want to overwrite potentially useful error messages.
         */

        return {
            phrase: 'error.not_allowed_operation',
            type: 'is-danger'
        }
        
    } else if (error.name === 'NotSupportedError') {

        const validPubKeyCredParams = publicKey.pubKeyCredParams.filter(
            (param) => param.type === 'public-key',
        );

        if (validPubKeyCredParams.length === 0) {
            // No entry in pubKeyCredParams was of type "public-key"
            // https://www.w3.org/TR/webauthn-2/#sctn-createCredential (Step 10)

            return {
                phrase: 'error.no_entry_was_of_type_public_key',
                type: 'is-danger'
            }
        }

        // No available authenticator supported any of the specified pubKeyCredParams algorithms
        // https://www.w3.org/TR/webauthn-2/#sctn-op-make-cred (Step 2)

        return {
            phrase: 'error.no_authenticator_support_specified_algorithms',
            type: 'is-danger'
        }

    } else if (error.name === 'SecurityError') {

        const effectiveDomain = window.location.hostname;

        if (!isValidDomain(effectiveDomain)) {
            // The current location domain is not a valid domain
            // https://www.w3.org/TR/webauthn-2/#sctn-createCredential (Step 7)

            return {
                phrase: 'error.2fauth_has_not_a_valid_domain',
                type: 'is-danger'
            }

        } else if (publicKey.rp.id !== effectiveDomain) {
            // The RP ID "${publicKey.rp.id}" is invalid for this domain
            // https://www.w3.org/TR/webauthn-2/#sctn-createCredential (Step 8)

            return {
                phrase: 'error.security_error_check_rpid',
                type: 'is-danger'
            }
        }

    } else if (error.name === 'TypeError') {
        if (publicKey.user.id.byteLength < 1 || publicKey.user.id.byteLength > 64) {
            // User ID was not between 1 and 64 characters
            // https://www.w3.org/TR/webauthn-2/#sctn-createCredential (Step 5)

            return {
                phrase: 'error.user_id_not_between_1_64',
                type: 'is-danger'
            }
        }

    } else if (error.name === 'UnknownError') {
        // The authenticator was unable to process the specified options, or could not create a new credential
        // https://www.w3.org/TR/webauthn-2/#sctn-op-make-cred (Step 1)
        // https://www.w3.org/TR/webauthn-2/#sctn-op-make-cred (Step 8)

        return {
            phrase: 'error.unknown_error',
            type: 'is-danger'
        }
    }

    return {
        phrase: 'error.unknown_error',
        type: 'is-danger'
    }
}
