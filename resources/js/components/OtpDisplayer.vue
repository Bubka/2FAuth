<template>
    <div>
        <figure class="image is-64x64" :class="{ 'no-icon': !internal_icon }" style="display: inline-block">
            <img :src="$root.appConfig.subdirectory + '/storage/icons/' + internal_icon" v-if="internal_icon" :alt="$t('twofaccounts.icon_to_illustrate_the_account')">
        </figure>
        <p class="is-size-4 has-ellipsis" :class="$root.showDarkMode ? 'has-text-grey-light' : 'has-text-grey'">{{ internal_service }}</p>
        <p class="is-size-6 has-ellipsis" :class="$root.showDarkMode ? 'has-text-grey' : 'has-text-grey-light'">{{ internal_account }}</p>
        <p>
            <span id="otp" role="log" ref="otp" tabindex="0" class="otp is-size-1 is-clickable px-3" :class="$root.showDarkMode ? 'has-text-white' : 'has-text-grey-dark'" @click="copyOTP(internal_password, true)" @keyup.enter="copyOTP(internal_password, true)" :title="$t('commons.copy_to_clipboard')">
                {{ displayPwd(this.internal_password) }}
            </span>
        </p>
        <dots v-show="isTimeBased(internal_otp_type)" ref="dots"></dots>
        <ul v-show="isHMacBased(internal_otp_type)">
            <li>counter: {{ internal_counter }}</li>
        </ul>
        <totp-looper 
            v-if="this.hasTOTP"
            :period="internal_period" 
            :generated_at="internal_generated_at" 
            :autostart="false" 
            v-on:loop-ended="getOtp()"
            v-on:loop-started="turnDotsOn($event)"
            v-on:stepped-up="turnDotsOn($event)"
            ref="looper"
        ></totp-looper>
    </div>
</template>

<script>
    import TotpLooper from './TotpLooper'
    import Dots from './Dots'

    export default {
        name: 'OtpDisplayer',

        data() {
            return {
                internal_id: null,
                internal_otp_type: '',
                internal_account: '',
                internal_service: '',
                internal_icon: '',
                internal_secret: null,
                internal_digits: null,
                internal_algorithm: null,
                internal_period: null,
                internal_counter: null,
                internal_password: '',
                internal_uri: '',
                internal_generated_at: null,
                hasTOTP: false
            }
        },

        props: {
            otp_type : String,
            account : String,
            service : String,
            icon : String,
            secret : String,
            digits : Number,
            algorithm : String,
            period : null,
            counter : null,
            image : String,
            qrcode : null,
            uri : String
        },

        computed: {

        },

        components: {
            TotpLooper,
            Dots,
        },

        mounted: function() {
            
        },

        methods: {

            /**
             * 
             */
            turnDotsOn(stepIndex) {
                this.$refs.dots.turnOn(stepIndex)
            },

            copyOTP (otp, permit_closing) {
                // see https://web.dev/async-clipboard/ for future Clipboard API usage.
                // The API should allow to copy the password on each trip without user interaction.

                // For now too many browsers don't support the clipboard-write permission
                // (see https://developer.mozilla.org/en-US/docs/Web/API/Permissions#browser_support)

                const rawOTP = otp.replace(/ /g, '')
                const success = this.$clipboard(rawOTP)

                if (success == true) {
                    if(this.$root.userPreferences.kickUserAfter == -1) {
                        this.appLogout()
                    }
                    else if(this.$root.userPreferences.closeOtpOnCopy && (permit_closing || false) === true) {
                        this.$parent.isActive = false
                        this.clearOTP()
                    }

                    this.$notify({ type: 'is-success', text: this.$t('commons.copied_to_clipboard') })
                }
            },

            isTimeBased: function(otp_type) {
                return (otp_type === 'totp' || otp_type === 'steamtotp')
            },

            isHMacBased: function(otp_type) {
                return otp_type === 'hotp'
            },

            async show(id) {

                // 3 possible cases :
                //   - Trigger when user ask for an otp of an existing account: the ID is provided so we fetch the account data
                //     from db but without the uri.
                //     This prevent the uri (a sensitive data) to transit via http request unnecessarily. In this
                //     case this.otp_type is sent by the backend.
                //   - Trigger when user use the Quick Uploader and preview the account: No ID but we have an URI.
                //   - Trigger when user use the Advanced form and preview the account: We should have all OTP parameter
                //     to obtain an otp, including Secret and otp_type which are required

                this.internal_otp_type = this.otp_type
                this.internal_account = this.account
                this.internal_service = this.service
                this.internal_icon = this.icon
                this.internal_secret = this.secret
                this.internal_digits = this.digits
                this.internal_algorithm = this.algorithm
                this.internal_period = this.period
                this.internal_counter = this.counter

                if( id ) {

                    this.internal_id = id
                    const { data } = await this.axios.get('api/v1/twofaccounts/' + this.internal_id)

                    this.internal_service = data.service
                    this.internal_account = data.account
                    this.internal_icon = data.icon
                    this.internal_otp_type = data.otp_type

                    if( this.isHMacBased(data.otp_type) && data.counter ) {
                        this.internal_counter = data.counter
                    }
                }

                // We force the otp_type to be based on the uri
                if( this.uri ) {
                    this.internal_uri = this.uri
                    this.internal_otp_type = this.uri.slice(0, 15 ).toLowerCase() === "otpauth://totp/" ? 'totp' : 'hotp';
                }

                if( this.internal_id || this.uri || this.secret ) { // minimun required vars to get an otp from the backend
                    try {
                        if( this.isTimeBased(this.internal_otp_type) || this.isHMacBased(this.internal_otp_type)) {
                            await this.getOtp()
                        }
                        else this.$router.push({ name: 'genericError', params: { err: this.$t('errors.not_a_supported_otp_type') } });

                        this.$parent.isActive = true
                        this.focusOnOTP()
                    }
                    catch(error) {
                        this.clearOTP()
                    }
                    finally {
                        this.$root.hideSpinner();
                    }
                } else  {
                    this.$root.hideSpinner();
                }
            },

            /**
             * 
             */
            getOtp: async function() {

                await this.axios(this.getOtpRequest()).then(response => {

                    let otp = response.data

                    this.internal_password = otp.password

                    if(this.$root.userPreferences.copyOtpOnDisplay) {
                        this.copyOTP(otp.password)
                    }

                    if (this.isTimeBased(otp.otp_type)) {
                        this.internal_generated_at = otp.generated_at
                        this.internal_period = otp.period
                        this.hasTOTP = true

                        this.$nextTick(() => {
                            this.$refs.looper.startLoop()
                        })
                    }
                    else if (this.isHMacBased(otp.otp_type)) {

                        this.internal_counter = otp.counter

                        // returned counter & uri are incremented
                        this.$emit('increment-hotp', { nextHotpCounter: otp.counter, nextUri: otp.uri })
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.$emit('validation-error', error.response)
                    }
                    throw error
                })
            },

            /**
             * 
             */
            getOtpRequest() {

                if(this.internal_id) {
                    return {
                        method: 'get',
                        url: '/api/v1/twofaccounts/' + this.internal_id + '/otp'
                    }
                }
                else if(this.internal_uri) {
                    return {
                        method: 'post',
                        url: '/api/v1/twofaccounts/otp',
                        data: {
                            uri: this.internal_uri
                        }
                    }
                }
                else {
                    return {
                        method: 'post',
                        url: '/api/v1/twofaccounts/otp',
                        data: {
                            service     : this.internal_service,
                            account     : this.internal_account,
                            icon        : this.internal_icon,
                            otp_type    : this.internal_otp_type,
                            secret      : this.internal_secret,
                            digits      : this.internal_digits,
                            algorithm   : this.internal_algorithm,
                            period      : this.internal_period,
                            counter     : this.internal_counter,
                        }
                    }
                }
            },

            /**
             * 
             */
            clearOTP: function() {
                
                this.internal_id = this.internal_counter = this.internal_generated_at = null
                this.internal_service = this.internal_account = this.internal_icon = this.internal_otp_type = this.internal_secret = ''
                this.internal_password = '... ...'
                this.hasTOTP = false

                this.$refs.looper?.clearLooper();
            },

            /**
             * 
             */
            focusOnOTP() {
                this.$nextTick(() => {
                    this.$refs.otp.focus()
                })
            }

        },

        beforeDestroy () {
            
        }
    }
</script>
