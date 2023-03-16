<template>
    <div>
        <!-- webauthn authentication -->
        <form-wrapper v-if="showWebauthn" :title="$t('auth.forms.webauthn_login')" :punchline="$t('auth.welcome_to_2fauth')">
            <div class="field">
                {{ $t('auth.webauthn.use_security_device_to_sign_in') }}
            </div>
            <form id="frmWebauthnLogin" @submit.prevent="webauthnLogin" @keydown="form.onKeydown($event)">
                <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" autofocus />
                <form-buttons :isBusy="form.isBusy" :caption="$t('commons.continue')" :submitId="'btnContinue'"/>
            </form>
            <div class="nav-links">
                <p>{{ $t('auth.webauthn.lost_your_device') }}&nbsp;<router-link id="lnkRecoverAccount" :to="{ name: 'webauthn.lost' }" class="is-link">{{ $t('auth.webauthn.recover_your_account') }}</router-link></p>
                <p v-if="!this.$root.userPreferences.useWebauthnOnly">{{ $t('auth.sign_in_using') }}&nbsp;
                    <a id="lnkSignWithLegacy" role="button" class="is-link" @keyup.enter="toggleForm" @click="toggleForm" tabindex="0">{{ $t('auth.login_and_password') }}</a>
                </p>
            </div>
        </form-wrapper>
        <!-- login/password legacy form -->
        <form-wrapper v-else :title="$t('auth.forms.login')" :punchline="$t('auth.welcome_to_2fauth')">
            <div v-if="isDemo" class="notification is-info has-text-centered is-radiusless" v-html="$t('auth.forms.welcome_to_demo_app_use_those_credentials')" />
            <div v-if="isTesting" class="notification is-warning has-text-centered is-radiusless" v-html="$t('auth.forms.welcome_to_testing_app_use_those_credentials')" />
            <form id="frmLegacyLogin" @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
                <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" autofocus />
                <form-password-field :form="form" fieldName="password" :label="$t('auth.forms.password')" />
                <form-buttons :isBusy="form.isBusy" :caption="$t('auth.sign_in')" :submitId="'btnSignIn'"/>
            </form>
            <div class="nav-links">
                <p>{{ $t('auth.forms.forgot_your_password') }}&nbsp;<router-link id="lnkResetPwd" :to="{ name: 'password.request' }" class="is-link" :aria-label="$t('auth.forms.reset_your_password')">{{ $t('auth.forms.request_password_reset') }}</router-link></p>
                <p >{{ $t('auth.sign_in_using') }}&nbsp;
                    <a id="lnkSignWithWebauthn" role="button" class="is-link" @keyup.enter="toggleForm" @click="toggleForm" tabindex="0" :aria-label="$t('auth.sign_in_using_security_device')">{{ $t('auth.webauthn.security_device') }}</a>
                </p>
                <p class="mt-4">{{ $t('auth.forms.dont_have_account_yet') }}&nbsp;<router-link id="lnkRegister" :to="{ name: 'register' }" class="is-link">{{ $t('auth.register') }}</router-link></p>
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
                isDemo: this.$root.isDemoApp,
                isTesting: this.$root.isTestingApp,
                form: new Form({
                    email: '',
                    password: ''
                }),
                isBusy: false,
                showWebauthn: this.$root.userPreferences.useWebauthnOnly,
                csrfRefresher: null,
                webauthn: new WebAuthn()
            }
        },

        mounted: function() {
            this.csrfRefresher = setInterval(this.refreshToken, 300000) // 5 min
            this.showWebauthn = this.$storage.get('showWebauthnForm', false)
        },

        methods : {
            /**
             * Toggle the form between legacy and webauthn method
             */
            toggleForm() {
                this.showWebauthn = ! this.showWebauthn
                this.$storage.set('showWebauthnForm', this.showWebauthn)
            },

            /**
             * Sign in using the login/password form
             */
            handleSubmit(e) {
                e.preventDefault()

                this.form.post('/user/login', {returnError: true})
                .then(response => {
                    this.applyPreferences(response.data.preferences);
                    this.$router.push({ name: 'accounts', params: { toRefresh: true } })
                })
                .catch(error => {
                    if( error.response.status === 401 ) {

                        this.$notify({ type: 'is-danger', text: this.$t('auth.forms.authentication_failed'), duration:-1 })
                    }
                    else if( error.response.status !== 422 ) {

                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
            },

            /**
             * Sign in using the WebAuthn API
             */
            async webauthnLogin() {
                this.isBusy = false

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

                const loginOptions = await this.form.post('/webauthn/login/options').then(res => res.data)
                const publicKey = this.webauthn.parseIncomingServerOptions(loginOptions)
                const credentials = await navigator.credentials.get({ publicKey: publicKey })
                .catch(error => {
                    if (error.name == 'AbortError') {
                        this.$notify({ type: 'is-warning', text: this.$t('errors.aborted_by_user') })
                    }
                    else if (error.name == 'SecurityError') {
                        this.$notify({ type: 'is-danger', text: this.$t('errors.security_error_check_rpid') })
                    }
                    else if (error.name == 'NotAllowedError') {
                        this.$notify({ type: 'is-danger', text: this.$t('errors.not_allowed_operation') })
                    }
                    else if (error.name == 'NotSupportedError') {
                        this.$notify({ type: 'is-danger', text: this.$t('errors.unsupported_operation') })
                    }
                    else if (error.name == 'InvalidStateError') {
                        this.$notify({ type: 'is-danger', text: this.$t('auth.webauthn.unknown_device') })
                    }
                    else this.$notify({ type: 'is-danger', text: this.$t('errors.unknown_error') })
                })

                if (!credentials) return false

                let publicKeyCredential = this.webauthn.parseOutgoingCredentials(credentials)
                publicKeyCredential.email = this.form.email

                this.axios.post('/webauthn/login', publicKeyCredential, {returnError: true}).then(response => {
                    this.applyPreferences(response.data.preferences);
                    this.$router.push({ name: 'accounts', params: { toRefresh: true } })
                })
                .catch(error => {
                    if( error.response.status === 401 ) {

                        this.$notify({ type: 'is-danger', text: this.$t('auth.forms.authentication_failed'), duration:-1 })
                    }
                    else if( error.response.status !== 422 ) {

                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });

                this.isBusy = false
            },

            refreshToken(){
                this.axios.get('/refresh-csrf')
            }
        },

        beforeRouteEnter (to, from, next) {
            if (to.params.forceRefresh && from.name !== null) {
                window.location.href = "." + to.path;
                return;
            }

            next();
        },

        beforeRouteLeave (to, from, next) {
            this.$notify({
                clean: true
            })
            clearInterval(this.csrfRefresher);
            next()
        }
    }
</script>