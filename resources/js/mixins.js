import Vue from 'vue'

Vue.mixin({

    data: function () {
        return {
            appVersion: window.appVersion
        }
    },

    methods: {

        async appLogout(evt) {
            if (this.$root.appConfig.proxyAuth) {
                if (this.$root.appConfig.proxyLogoutUrl) {
                    location.assign(this.$root.appConfig.proxyLogoutUrl)
                }
                else return false
            }
            else {
                await this.axios.get('/user/logout')
                this.$storage.clear()
                this.$router.push({ name: 'login', params: {forceRefresh: true} })
            }
        },
        
        exitSettings: function(event) {
            if (event) {
                this.$notify({ clean: true })
                this.$router.push({ name: 'accounts' })
            }
        },

        isUrl: function (url) {
            var strRegex = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/
            var re = new RegExp(strRegex)

            return re.test(url)
        },

        openInBrowser(uri) {
            const a = document.createElement('a')
            a.setAttribute('href', uri)
            a.dispatchEvent(new MouseEvent("click", {'view': window, 'bubbles': true, 'cancelable': true}))
        },

        /**
         * Parses the Public Key Options received from the Server for the browser.
         *
         * @param publicKey {Object}
         * @returns {Object}
         */
        parseIncomingServerOptions(publicKey) {
            publicKey.challenge = this.uint8Array(publicKey.challenge);

            if (publicKey.user !== undefined) {
                publicKey.user = {
                    ...publicKey.user,
                    id: this.uint8Array(publicKey.user.id, true)
                };
            }

            ["excludeCredentials", "allowCredentials"]
                .filter((key) => publicKey[key] !== undefined)
                .forEach((key) => {
                    publicKey[key] = publicKey[key].map((data) => {
                        return { ...data, id: this.uint8Array(data.id) };
                    });
                });

            return publicKey;
        },

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
                rawId: this.arrayToBase64String(credentials.rawId),
                response: {}
            };
            [
                "clientDataJSON",
                "attestationObject",
                "authenticatorData",
                "signature",
                "userHandle"
            ]
                .filter((key) => credentials.response[key] !== undefined)
                .forEach((key) => {
                    if( credentials.response[key] === null )
                    {
                        parseCredentials.response[key] = null
                    }
                    else {
                        parseCredentials.response[key] = this.arrayToBase64String(
                            credentials.response[key]
                        );
                    }
                });
                
            return parseCredentials;
        },

        /**
         * Transform an string into Uint8Array instance.
         *
         * @param input {string}
         * @param atob {boolean}
         * @returns {Uint8Array}
         */
        uint8Array(input, atob = false) {
            return Uint8Array.from(
                atob ? window.atob(input) : this.base64UrlDecode(input),
                (c) => c.charCodeAt(0)
            );
        },
        
        /**
         * Encodes an array of bytes to a BASE64 URL string
         *
         * @param arrayBuffer {ArrayBuffer|Uint8Array}
         * @returns {string}
         */
        arrayToBase64String(arrayBuffer) {
            return btoa(String.fromCharCode(...new Uint8Array(arrayBuffer)));
        },

        /**
         *
         * Decodes a BASE64 URL string into a normal string.
         *
         * @param input {string}
         * @returns {string|Iterable}
         */
        base64UrlDecode(input) {
            input = input.replace(/-/g, "+").replace(/_/g, "/");
            const pad = input.length % 4;

            if (pad) {
                if (pad === 1) {
                    throw new Error(
                        "InvalidLengthError: Input base64url string is the wrong length to determine padding"
                    );
                }

                input += new Array(5 - pad).join("=");
            }

            return window.atob(input);
        },
        
        /**
         * Encodes an array of bytes to a BASE64 URL string
         *
         * @param arrayBuffer {ArrayBuffer|Uint8Array}
         * @returns {string}
         */
        inputId(fieldType, fieldName) {
            let prefix

            switch (fieldType) {
                case 'button':
                    prefix = 'txt'
                    break
                case 'button':
                    prefix = 'btn'
                    break
                case 'email':
                    prefix = 'eml'
                    break
                case 'password':
                    prefix = 'pwd'
                    break
                case 'radio':
                    prefix = 'rdo'
                    break
                case 'label':
                    prefix = 'lbl'
                    break
                default:
                    prefix = 'txt'
                    break
            }

            return prefix + fieldName[0].toUpperCase() + fieldName.toLowerCase().slice(1);
            // button
            // checkbox
            // color
            // date 
            // datetime-local
            // file
            // hidden
            // image
            // month
            // number
            // radio
            // range
            // reset
            // search
            // submit
            // tel
            // text
            // time
            // url
            // week
        },
    }

})