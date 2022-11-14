/**
 * MIT License
 *
 * Copyright (c) Italo Israel Baeza Cabrera
 * https://github.com/Laragear/WebAuthn
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

export default class WebAuthn {

    /**
     * Create a new WebAuthn instance.
     *
     */
    constructor () {

    }

    /**
     * Parses the Public Key Options received from the Server for the browser.
     *
     * @param publicKey {Object}
     * @returns {Object}
     */
    parseIncomingServerOptions(publicKey) {
        publicKey.challenge = WebAuthn.uint8Array(publicKey.challenge);

        if ('user' in publicKey) {
            publicKey.user = {
                ...publicKey.user,
                id: WebAuthn.uint8Array(publicKey.user.id)
            };
        }

        [
            "excludeCredentials",
            "allowCredentials"
        ]
            .filter(key => key in publicKey)
            .forEach(key => {
                publicKey[key] = publicKey[key].map(data => {
                    return {...data, id: WebAuthn.uint8Array(data.id)};
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
    parseOutgoingCredentials(credentials) {
        let parseCredentials = {
            id: credentials.id,
            type: credentials.type,
            rawId: WebAuthn.arrayToBase64String(credentials.rawId),
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
            .forEach(key => parseCredentials.response[key] = WebAuthn.arrayToBase64String(credentials.response[key]));

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
            useAtob ? atob(input) : WebAuthn.base64UrlDecode(input), c => c.charCodeAt(0)
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
        return typeof PublicKeyCredential != "undefined";
    }
    

    /**
     * Checks if the browser doesn't support WebAuthn.
     *
     * @returns {boolean}
     */
    static doesntSupportWebAuthn() {
        return !this.supportsWebAuthn();
    }
}
