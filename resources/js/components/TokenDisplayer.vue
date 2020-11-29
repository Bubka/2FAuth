<template>
    <div>
        <figure class="image is-64x64" :class="{ 'no-icon': !internal_icon }" style="display: inline-block">
            <img :src="'/storage/icons/' + internal_icon" v-if="internal_icon">
        </figure>
        <p class="is-size-4 has-text-grey-light has-ellipsis">{{ internal_service }}</p>
        <p class="is-size-6 has-text-grey has-ellipsis">{{ internal_account }}</p>
        <p class="is-size-1 has-text-white is-clickable" :title="$t('commons.copy_to_clipboard')" v-clipboard="() => token.replace(/ /g, '')" v-clipboard:success="clipboardSuccessHandler">{{ displayedToken }}</p>
        <ul class="dots" v-if="internal_otpType === 'totp'">
            <li v-for="n in 10"></li>
        </ul>
        <ul v-else-if="internal_otpType === 'hotp'">
            <li>counter: {{ internal_hotpCounter }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'TokenDisplayer',

        data() {
            return {
                id: null,
                token : '',
                remainingTimeout: null,
                firstDotToNextOneTimeout: null,
                dotToDotInterval: null,
                position: null,
                totpTimestamp: null,
                internal_otpType: '',
                internal_account: '',
                internal_service: '',
                internal_icon: '',
                internal_hotpCounter: null,
                lastActiveDot: null,
                dotToDotCounter: null,
            }
        },

        props: {
            account : String,
            algorithm : String,
            digits : Number,
            hotpCounter : null,
            icon : String,
            imageLink : String,
            otpType : String,
            qrcode : null,
            secret : String,
            secretIsBase32Encoded : Number,
            service : String,
            totpPeriod : null,
            uri : String
        },

        computed: {
            displayedToken() {
                return this.$root.appSettings.showTokenAsDot ? this.token.replace(/[0-9]/g, '●') : this.token
            }
        },

        mounted: function() {
            this.getToken()
        },

        methods: {

            async getToken(id) {

                // 3 possible cases :
                //   - Trigger when user ask for a token of an existing account: the ID is provided so we fetch the account data
                //     from db but without the uri.
                //     This prevent the uri (a sensitive data) to transit via http request unnecessarily. In this
                //     case this.otpType is sent by the backend.
                //   - Trigger when user use the Quick Uploader and preview the account: No ID but we have an URI.
                //   - Trigger when user use the Advanced form and preview the account: We should have all OTP parameter
                //     to obtain a token, including Secret and otpType which are required

                this.internal_service = this.service
                this.internal_account = this.account
                this.internal_icon = this.icon
                this.internal_otpType = this.otpType
                this.internal_hotpCounter = this.hotpCounter

                if( id ) {

                    this.id = id
                    const { data } = await this.axios.get('api/twofaccounts/' + this.id)

                    this.internal_service = data.service
                    this.internal_account = data.account
                    this.internal_icon = data.icon
                    this.internal_otpType = data.otpType
                    
                    if( data.otpType === 'hotp' && data.hotpCounter ) {
                        this.internal_hotpCounter = data.hotpCounter
                    }
                }

                // We force the otpType to be based on the uri
                if( this.uri ) {
                    this.internal_otpType = this.uri.slice(0, 15 ).toLowerCase() === "otpauth://totp/" ? 'totp' : 'hotp';
                }

                if( this.id || this.uri || this.secret ) { // minimun required vars to get a token from the backend
                    
                    switch(this.internal_otpType) {
                        case 'totp':
                            await this.getTOTP()
                            break;
                        case 'hotp':
                            await this.getHOTP()
                            break;
                        default:
                            this.$router.push({ name: 'genericError', params: { err: this.$t('errors.not_a_supported_otp_type') } });
                    } 

                    this.$parent.isActive = true
                }
            },


            getTOTP: function() {

                this.dotToDotCounter = 0

                this.axios.post('/api/twofaccounts/token', { id: this.id, otp: this.$props }).then(response => {

                    let spacePosition = Math.ceil(response.data.token.length / 2);
                    
                    this.token = response.data.token.substr(0, spacePosition) + " " + response.data.token.substr(spacePosition);
                    this.totpTimestamp = response.data.totpTimestamp; // the timestamp used to generate the token
                    this.position = this.totpTimestamp % response.data.totpPeriod // The position of the totp timestamp in the current period

                    // Hide all dots
                    let dots = this.$el.querySelector('.dots');

                    while (dots.querySelector('[data-is-active]')) {
                        dots.querySelector('[data-is-active]').removeAttribute('data-is-active');
                    }

                    // Activate the dot at the totp position
                    let relativePosition = (this.position * 10) / response.data.totpPeriod
                    let dotNumber = (Math.floor(relativePosition) +1)

                    this.lastActiveDot = dots.querySelector('li:nth-child(' + dotNumber + ')');
                    this.lastActiveDot.setAttribute('data-is-active', true);

                    // Main timeout which run all over the totpPeriod.

                    let remainingTimeBeforeEndOfPeriod = response.data.totpPeriod - this.position
                    let self = this; // because of the setInterval/setTimeout closures

                    this.remainingTimeout = setTimeout(function() {
                        self.stopLoop()
                        self.getTOTP();
                    }, remainingTimeBeforeEndOfPeriod*1000);

                    // During the remainingTimeout countdown we have to show a next dot every durationBetweenTwoDots seconds
                    // except for the first next dot

                    let durationBetweenTwoDots = response.data.totpPeriod / 10 // we have 10 dots
                    let firstDotTimeout = (Math.ceil(this.position / durationBetweenTwoDots) * durationBetweenTwoDots) - this.position

                    this.firstDotToNextOneTimeout = setTimeout(function() {

                        if( firstDotTimeout > 0 ) {
                            self.activeNextDot()
                            dotNumber += 1
                        }

                        self.dotToDotInterval = setInterval(function() {

                            self.dotToDotCounter += 1
                            self.activeNextDot()
                            dotNumber += 1

                        }, durationBetweenTwoDots*1000)

                    }, firstDotTimeout*1000)
                })
                .catch(error => {
                    this.$router.push({ name: 'genericError', params: { err: error.response } });
                });
            },


            getHOTP: function() {

                this.axios.post('/api/twofaccounts/token', { id: this.id, otp: this.$props }).then(response => {
                    let spacePosition = Math.ceil(response.data.token.length / 2);
                    
                    this.token = response.data.token.substr(0, spacePosition) + " " + response.data.token.substr(spacePosition)

                    // returned counter & uri are incremented
                    this.$emit('increment-hotp', { nextHotpCounter: response.data.hotpCounter, nextUri: response.data.uri })

                })
                .catch(error => {
                    this.$router.push({ name: 'genericError', params: { err: error.response } });
                });
            },


            clearOTP: function() {
                this.stopLoop()
                this.id = this.remainingTimeout = this.dotToDotInterval = this.firstDotToNextOneTimeout = this.position = this.internal_hotpCounter = null
                this.internal_service = this.internal_account = this.internal_icon = this.internal_otpType = ''
                this.token = '... ...'

                try {
                    this.$el.querySelector('[data-is-active]').removeAttribute('data-is-active');
                    this.$el.querySelector('.dots li:first-child').setAttribute('data-is-active', true);
                }
                catch(e) {
                    // we do not throw anything
                }
            },


            stopLoop: function() {
                if( this.internal_otpType === 'totp' ) {
                    clearTimeout(this.remainingTimeout)
                    clearTimeout(this.firstDotToNextOneTimeout)
                    clearInterval(this.dotToDotInterval)
                }
            },


            activeNextDot: function() {
                if(this.lastActiveDot.nextSibling !== null) {

                    this.lastActiveDot.removeAttribute('data-is-active')
                    this.lastActiveDot.nextSibling.setAttribute('data-is-active', true)
                    this.lastActiveDot = this.lastActiveDot.nextSibling
                }
            },


            clipboardSuccessHandler ({ value, event }) {

                if(this.$root.appSettings.kickUserAfter == -1) {
                    this.appLogout()
                }
                else if(this.$root.appSettings.closeTokenOnCopy) {
                    this.$parent.isActive = false
                    this.clearOTP()
                }

                this.$notify({ type: 'is-success', text: this.$t('commons.copied_to_clipboard') })
            },


            clipboardErrorHandler ({ value, event }) {
                console.log('error', value)
            }

        },

        beforeDestroy () {
            this.stopLoop()
        }
    }
</script>