<template>
    <div>
        <!-- webauthn registration -->
        <form-wrapper v-if="showWebauthnRegistration" :title="$t('auth.authentication')" :punchline="$t('auth.webauthn.enhance_security_using_webauthn')">
            <div v-if="deviceRegistered" class="field">
                <label id="lblDeviceRegistrationSuccess" class="label mb-5">{{ $t('auth.webauthn.device_successfully_registered') }}&nbsp;<font-awesome-icon :icon="['fas', 'check']" /></label>
                <form @submit.prevent="handleDeviceSubmit" @keydown="deviceForm.onKeydown($event)">
                    <form-field :form="deviceForm" fieldName="name" inputType="text" placeholder="iPhone 12, TouchID, Yubikey 5C" :label="$t('auth.forms.name_this_device')" />
                    <form-buttons :isBusy="deviceForm.isBusy" :isDisabled="deviceForm.isDisabled" :caption="$t('commons.continue')" />
                </form>
            </div>
            <div v-else class="field is-grouped">
                <!-- register button -->
                <div class="control">
                    <button type="button" id="btnRegisterNewDevice" @click="registerWebauthnDevice()" class="button is-link">{{ $t('auth.webauthn.register_a_device') }}</button>
                </div>
                <!-- dismiss button -->
                <div class="control">
                    <router-link id="btnMaybeLater" :to="{ name: 'accounts', params: { toRefresh: true } }" class="button is-text">{{ $t('auth.maybe_later') }}</router-link>
                </div>
            </div>
        </form-wrapper>
        <!-- User registration form -->
        <form-wrapper v-else :title="$t('auth.register')" :punchline="$t('auth.forms.register_punchline')">
            <form @submit.prevent="handleRegisterSubmit" @keydown="registerForm.onKeydown($event)">
                <form-field :form="registerForm" fieldName="name" inputType="text" :label="$t('auth.forms.name')" :maxLength="255" autofocus />
                <form-field :form="registerForm" fieldName="email" inputType="email" :label="$t('auth.forms.email')" :maxLength="255" />
                <form-password-field :form="registerForm" fieldName="password" :showRules="true" :label="$t('auth.forms.password')" />
                <form-buttons :isBusy="registerForm.isBusy" :isDisabled="registerForm.isDisabled" :caption="$t('auth.register')" :submitId="'btnRegister'" />
            </form>
            <div class="nav-links">
                <p>{{ $t('auth.forms.already_register') }}&nbsp;<router-link id="lnkSignIn" :to="{ name: 'login' }" class="is-link">{{ $t('auth.sign_in') }}</router-link></p>
            </div>
        </form-wrapper>
        <!-- footer -->
        <vue-footer></vue-footer>
    </div>
</template>

<script>

    import Form from './../../components/Form'
    import WebAuthn from './../../components/WebAuthn'

    export default {
        data(){
            return {
                registerForm: new Form({
                    name : '',
                    email : '',
                    password : '',
                    password_confirmation : '',
                }),
                deviceForm: new Form({
                    name : '',
                }),
                showWebauthnRegistration: false,
                deviceRegistered: false,
                deviceId : null,
                webauthn: new WebAuthn()
            }
        },

        methods : {
            /**
             * Register a new user
             */
            async handleRegisterSubmit(e) {
                e.preventDefault()
                this.registerForm.password_confirmation = this.registerForm.password

                this.registerForm.post('/user', {returnError: true})
                .then(response => {
                    this.showWebauthnRegistration = true
                })
                .catch(error => {
                    if( error.response.status !== 422 ) {

                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
            },


            /**
             * Register a new security device
             */
            async registerWebauthnDevice() {
                // Check https context
                if (!window.isSecureContext) {
                    this.$notify({ type: 'is-danger', text: this.$t('errors.https_required') })
                    return false
                }

                // Check browser support
                if (this.webauthn.doesntSupportWebAuthn) {
                    this.$notify({ type: 'is-danger', text: this.$t('errors.browser_does_not_support_webauthn') })
                    return false
                }

                const registerOptions = await this.axios.post('/webauthn/register/options').then(res => res.data)
                const publicKey = this.webauthn.parseIncomingServerOptions(registerOptions)
                let bufferedCredentials

                try {
                    bufferedCredentials = await navigator.credentials.create({ publicKey })
                }
                catch (error) {
                    if (error.name == 'AbortError') {
                        this.$notify({ type: 'is-warning', text: this.$t('errors.aborted_by_user') })
                    }
                    else if (error.name == 'NotAllowedError' || 'InvalidStateError') {
                        this.$notify({ type: 'is-danger', text: this.$t('errors.security_device_unsupported') })
                    }
                    return false
                }

                const publicKeyCredential = this.webauthn.parseOutgoingCredentials(bufferedCredentials);

                this.axios.post('/webauthn/register', publicKeyCredential).then(response => {
                    this.deviceId = publicKeyCredential.id
                    this.deviceRegistered = true
                })
            },


            /**
             * Rename the registered device
             */
            async handleDeviceSubmit(e) {

                await this.deviceForm.patch('/webauthn/credentials/' + this.deviceId + '/name')

                if( this.deviceForm.errors.any() === false ) {
                    this.$router.push({name: 'accounts', params: { toRefresh: true }})
                }
            },

        },

        beforeRouteLeave (to, from, next) {
            this.$notify({
                clean: true
            })

            next()
        }
    }
</script>