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
 * Attempt to intuit _why_ an error was raised after calling `navigator.credentials.get()`
 */
export function identifyAuthenticationError(error, options) {
    const { publicKey } = options;

    if (error.name === 'AbortError') {
        if (options.signal instanceof AbortSignal) {
            // Authentication ceremony was sent an abort signal
            // https://www.w3.org/TR/webauthn-2/#sctn-createCredential (Step 16)

            return {
                phrase: 'error.aborted_by_user',
                type: 'is-warning'
            }
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

    } else if (error.name === 'SecurityError') {

        const effectiveDomain = window.location.hostname;

        if (!isValidDomain(effectiveDomain)) {
            // The current location domain is not a valid domain
            // https://www.w3.org/TR/webauthn-2/#sctn-discover-from-external-source (Step 5)

            return {
                phrase: 'error.2fauth_has_not_a_valid_domain',
                type: 'is-danger'
            }

        } else if (publicKey.rpId !== effectiveDomain) {
            // The RP ID "${publicKey.rpId}" is invalid for this domain
            // // https://www.w3.org/TR/webauthn-2/#sctn-discover-from-external-source (Step 6)

            return {
                phrase: 'error.security_error_check_rpid',
                type: 'is-danger'
            }
        }

    } else if (error.name === 'UnknownError') {
        // The authenticator was unable to process the specified options, or could not create a new assertion signature
        // https://www.w3.org/TR/webauthn-2/#sctn-op-get-assertion (Step 1)
        // https://www.w3.org/TR/webauthn-2/#sctn-op-get-assertion (Step 12)

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