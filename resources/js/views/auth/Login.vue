<template>
    <div v-if="username">
        <!-- webauthn authentication -->
        <form-wrapper v-if="showWebauthn" :title="$t('auth.forms.login')" :punchline="punchline">
            <div class="field">
                {{ $t('auth.webauthn.use_security_device_to_sign_in') }}
            </div>
            <div class="control">
                <button type="button" class="button is-link" @click="webauthnLogin">{{ $t('auth.sign_in') }}</button>
            </div>
            <p>{{ $t('auth.webauthn.lost_your_device') }}&nbsp;<router-link :to="{ name: 'webauthn.lost' }" class="is-link">{{ $t('auth.webauthn.recover_your_account') }}</router-link></p>
            <p v-if="!this.$root.appSettings.useWebauthnOnly">{{ $t('auth.sign_in_using') }}&nbsp;<a class="is-link" @click="showWebauthn = false">{{ $t('auth.login_and_password') }}</a></p>
        </form-wrapper>
        <!-- login/password legacy form -->
        <form-wrapper v-else :title="$t('auth.forms.login')" :punchline="punchline">
            <div v-if="isDemo" class="notification is-info has-text-centered is-radiusless" v-html="$t('auth.forms.welcome_to_demo_app_use_those_credentials')" />
            <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
                <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" autofocus />
                <form-field :form="form" fieldName="password" inputType="password" :label="$t('auth.forms.password')" />
                <form-buttons :isBusy="form.isBusy" :caption="$t('auth.sign_in')" />
            </form>
            <div v-if="!username">
                <p>{{ $t('auth.forms.dont_have_account_yet') }}&nbsp;<router-link :to="{ name: 'register' }" class="is-link">{{ $t('auth.register') }}</router-link></p>
            </div>
            <div v-else>
                <p>{{ $t('auth.forms.forgot_your_password') }}&nbsp;<router-link :to="{ name: 'password.request' }" class="is-link">{{ $t('auth.forms.request_password_reset') }}</router-link></p>
                <p >{{ $t('auth.sign_in_using') }}&nbsp;<a class="is-link" @click="showWebauthn = true">{{ $t('auth.webauthn.security_device') }}</a></p>
            </div>
        </form-wrapper>
    </div>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data(){
            return {
                username: null,
                isDemo: this.$root.appSettings.isDemoApp,
                form: new Form({
                    email: '',
                    password: ''
                }),
                isBusy: false,
                showWebauthn: this.$root.appSettings.useWebauthnAsDefault || this.$root.appSettings.useWebauthnOnly,
            }
        },

        computed : {
            punchline: function() {
                return this.isDemo ? '' : this.$t('auth.welcome_back_x', [this.username])
            }
        },

        methods : {
            /**
             * Sign in using the login/password form
             */
            handleSubmit(e) {
                e.preventDefault()

                this.form.post('/user/login', {returnError: true})
                .then(response => {
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
                if (!window.PublicKeyCredential) {
                    this.$notify({ type: 'is-danger', text: this.$t('errors.browser_does_not_support_webauthn') })
                    return false
                }

                const loginOptions = await this.axios.post('/webauthn/login/options').then(res => res.data)
                const publicKey = this.parseIncomingServerOptions(loginOptions)
                const credentials = await navigator.credentials.get({ publicKey: publicKey })
                .catch(error => {
                    this.$notify({ type: 'is-danger', text: this.$t('auth.webauthn.unknown_device') })
                })

                if (!credentials) return false

                const publicKeyCredential = this.parseOutgoingCredentials(credentials)

                this.axios.post('/webauthn/login', publicKeyCredential, {returnError: true}).then(response => {
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
        },

        beforeRouteEnter (to, from, next) {
            next(async vm => {
                const { data } = await vm.axios.get('api/v1/user/name')

                if( data.name ) {
                    // The email property is only sent when the user is logged in.
                    // In this case we push the user to the index view.
                    if( data.email ) {
                        return next({ name: 'accounts' });
                    }
                    vm.username = data.name
                }
                else {
                    return next({ name: 'register' });
                }
            });

            next();
        },

        beforeRouteLeave (to, from, next) {
            this.$notify({
                clean: true
            })
            
            next()
        }
    }
</script>