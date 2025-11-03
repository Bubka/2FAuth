/**
 * MIT License
 *
 * Copyright (c) 2023 Bubka - https://github.com/Bubka/2FAuth
 * Copyright (c) Italo Israel Baeza Cabrera - https://github.com/Laragear/WebAuthn
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
 */

import { httpClientFactory } from '@/services/httpClientFactory'
import { webauthnAbortService } from '@/services/webauthn/webauthnAbortService'
import { identifyRegistrationError }  from '@/services/webauthn/identifyRegistrationError'
import { identifyAuthenticationError }  from '@/services/webauthn/identifyAuthenticationError'

const webClient = httpClientFactory('web')

class WebauthnService {

    async register() {
        let err = {
            webauthn: true,
            type: 'is-danger',
            message: ''
        }

        // Check https context
        if (!window.isSecureContext) {
            err.message = 'error.https_required'
            return Promise.reject(err)
        }

        // Check browser support
        if (! WebauthnService.supportsWebAuthn) {
            err.message = 'error.browser_does_not_support_webauthn'
            return Promise.reject(err)
        }

        const registerOptions = await webClient.post('/webauthn/register/options').then(response => response.data)
        const publicKey = WebauthnService.parseIncomingServerOptions(registerOptions)
        
        let options = { publicKey }
        options.signal = webauthnAbortService.createNewAbortSignal()

        let bufferedCredentials
        try {
            bufferedCredentials = await navigator.credentials.create(options)
        }
        catch (error) {
            const webauthnError = identifyRegistrationError(error, options)
            return Promise.reject({
                webauthn: true,
                type: webauthnError.type,
                message: webauthnError.phrase
            })
        }

        const publicKeyCredential = WebauthnService.parseOutgoingCredentials(bufferedCredentials);

        return webClient.post('/webauthn/register', publicKeyCredential, {returnError: true})
    }


    async authenticate(email) {

        // Check https context
        if (!window.isSecureContext) {
            err.message = 'error.https_required'
            return Promise.reject(err)
        }

        // Check browser support
        if (! WebauthnService.supportsWebAuthn) {
            err.message = 'error.browser_does_not_support_webauthn'
            return Promise.reject(err)
        }

        const loginOptions = await webClient.post('/webauthn/login/options', { email: email }).then(response => response.data)
        const publicKey = WebauthnService.parseIncomingServerOptions(loginOptions)

        let options = { publicKey }
        options.signal = webauthnAbortService.createNewAbortSignal()

        const credentials = await navigator.credentials.get(options)
        .catch(error => {
            const webauthnError = identifyAuthenticationError(error, options)
            return Promise.reject({
                webauthn: true,
                type: webauthnError.type,
                message: webauthnError.phrase
            })
        })

        let publicKeyCredential = WebauthnService.parseOutgoingCredentials(credentials)
        publicKeyCredential.email = email

        return webClient.post('/webauthn/login', publicKeyCredential, {returnError: true})
    }

    /**
     * Parses the Public Key Options received from the Server for the browser.
     *
     * @param publicKey {Object}
     * @returns {Object}
     */
    static parseIncomingServerOptions(publicKey) {
        publicKey.challenge = WebauthnService.uint8Array(publicKey.challenge);

        if ('user' in publicKey) {
            publicKey.user = {
                ...publicKey.user,
                id: WebauthnService.uint8Array(publicKey.user.id)
            };
        }

        [
            "excludeCredentials",
            "allowCredentials"
        ]
            .filter(key => key in publicKey)
            .forEach(key => {
                publicKey[key] = publicKey[key].map(data => {
                    return {...data, id: WebauthnService.uint8Array(data.id)};
                });
            });

        return publicKey;
    }

    /**
     * Parses the outgoing credentials from the browser to the server.
     *
     * @param credentials {Credential|PublicKeyCredential}
     * @return {{response: {string}, rawId: string, id: string, type: string}}
     */
    static parseOutgoingCredentials(credentials) {
        let parseCredentials = {
            id: credentials.id,
            type: credentials.type,
            rawId: WebauthnService.arrayToBase64String(credentials.rawId),
            response: {}
        };

        [
            "clientDataJSON",
            "attestationObject",
            "authenticatorData",
            "signature",
            "userHandle"
        ]
            .filter(key => key in credentials.response)
            .forEach(key => parseCredentials.response[key] = WebauthnService.arrayToBase64String(credentials.response[key]));

        return parseCredentials;
    }

    /**
     * Transform a string into Uint8Array instance.
     *
     * @param input {string}
     * @param useAtob {boolean}
     * @returns {Uint8Array}
     */
    static uint8Array(input, useAtob = false) {
        return Uint8Array.from(
            useAtob ? atob(input) : WebauthnService.base64UrlDecode(input), c => c.charCodeAt(0)
        );
    }

    /**
     * Encodes an array of bytes to a BASE64 URL string
     *
     * @param arrayBuffer {ArrayBuffer|Uint8Array}
     * @returns {string}
     */
    static arrayToBase64String(arrayBuffer) {
        return btoa(String.fromCharCode(...new Uint8Array(arrayBuffer)));
    }

    
    /**
     * Decodes a BASE64 URL string into a normal string.
     *
     * @param input {string}
     * @returns {string|Iterable}
     */
    static base64UrlDecode(input) {
        input = input.replace(/-/g, "+").replace(/_/g, "/");

        const pad = input.length % 4;

        if (pad) {
            if (pad === 1) {
                throw new Error("InvalidLengthError: Input base64url string is the wrong length to determine padding");
            }

            input += new Array(5 - pad).join("=");
        }

        return atob(input);
    }


    /**
     * Checks if the browser supports WebAuthn.
     *
     * @returns {boolean}
     */
    static supportsWebAuthn() {
        return (window?.PublicKeyCredential !== undefined && typeof window.PublicKeyCredential === 'function');
    }
    

    /**
     * Checks if the browser doesn't support WebAuthn.
     *
     * @returns {boolean}
     */
    // static doesntSupportWebAuthn() {
    //     return !WebauthnService.supportsWebAuthn();
    // }
}

export const webauthnService = new WebauthnService();