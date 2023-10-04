<script setup>
    import Form from '@/components/formElements/Form'
    import { useUserStore } from '@/stores/user'
    import { useNotifyStore } from '@/stores/notify'

    const user = useUserStore()
    const notify = useNotifyStore()
    const showWebauthnRegistration = ref(false)
    const deviceRegistered = ref(false)
    
    const registerForm = reactive(new Form({
        name : '',
        email : '',
        password : '',
        password_confirmation : '',
    }))

    const securityDeviceForm = reactive(new Form({
        name : ''
    }))

    /**
     * Register a new user
     */
    async function register(e) {
        registerForm.password_confirmation = registerForm.password

        registerForm.post('/user').then(response => {
            user.$patch({
                name: response.data.name,
                preferences: response.data.preferences,
                isAdmin: response.data.is_admin ?? false,
            })
            user.applyTheme()

            showWebauthnRegistration.value = true
        })
    }

    /**
     * Register a new security device
     */
    async function registerWebauthnDevice() {

    }

    onBeforeRouteLeave(() => {
        notify.clear()
    })
</script>

<template>
    <div>
        <!-- webauthn registration -->
        <FormWrapper v-if="showWebauthnRegistration" title="auth.authentication" punchline="auth.webauthn.enhance_security_using_webauthn">
            <div v-if="deviceRegistered" class="field">
                <label id="lblDeviceRegistrationSuccess" class="label mb-5">{{ $t('auth.webauthn.device_successfully_registered') }}&nbsp;<font-awesome-icon :icon="['fas', 'check']" /></label>
                <form @submit.prevent="handleDeviceSubmit" @keydown="deviceForm.onKeydown($event)">
                    <FormField v-model="securityDeviceForm.name" fieldName="name" :fieldError="securityDeviceForm.errors.get('name')" inputType="text" placeholder="iPhone 12, TouchID, Yubikey 5C" label="auth.forms.name_this_device" />
                    <FormButtons :isBusy="securityDeviceForm.isBusy" :isDisabled="deviceForm.isDisabled" caption="commons.continue" />
                </form>
            </div>
            <div v-else class="field is-grouped">
                <!-- register button -->
                <div class="control">
                    <button type="button" id="btnRegisterNewDevice" @click="registerWebauthnDevice()" class="button is-link">{{ $t('auth.webauthn.register_a_device') }}</button>
                </div>
                <!-- dismiss button -->
                <div class="control">
                    <RouterLink id="btnMaybeLater" :to="{ name: 'accounts' }" class="button is-text">{{ $t('auth.maybe_later') }}</RouterLink>
                </div>
            </div>
        </FormWrapper>
        <!-- User registration form -->
        <FormWrapper v-else title="auth.register" punchline="auth.forms.register_punchline">
            <form @submit.prevent="register" @keydown="registerForm.onKeydown($event)">
                <FormField v-model="registerForm.name" fieldName="name" :fieldError="registerForm.errors.get('name')" inputType="text" label="auth.forms.name" :maxLength="255" autofocus />
                <FormField v-model="registerForm.email" fieldName="email" :fieldError="registerForm.errors.get('email')" inputType="email" label="auth.forms.email" :maxLength="255" />
                <FormPasswordField v-model="registerForm.password" fieldName="password" :fieldError="registerForm.errors.get('password')" :showRules="true" label="auth.forms.password" />
                <FormButtons :isBusy="registerForm.isBusy" :isDisabled="registerForm.isDisabled" caption="auth.register" submitId="btnRegister" />
            </form>
            <div class="nav-links">
                <p>{{ $t('auth.forms.already_register') }}&nbsp;<RouterLink id="lnkSignIn" :to="{ name: 'login' }" class="is-link">{{ $t('auth.sign_in') }}</RouterLink></p>
            </div>
        </FormWrapper>
        <!-- footer -->
        <VueFooter />
    </div>
</template>
<!-- 
<script>

    import WebauthnService from './../../webauthn/webauthnService'
    import { webauthnAbortService } from './../../webauthn/webauthnAbortService'
    import { identifyRegistrationError }  from './../../webauthn/identifyRegistrationError'

    export default {
        data(){
            return {
                deviceRegistered: false,
                deviceId : null
            }
        },

        methods : {

            /**
             * Register a new security device
             */
            async registerWebauthnDevice() {
                let webauthnService = new WebauthnService()

                // Check https context
                if (!window.isSecureContext) {
                    this.$notify({ type: 'is-danger', text: this.$t('errors.https_required') })
                    return false
                }

                // Check browser support
                if (webauthnService.doesntSupportWebAuthn) {
                    this.$notify({ type: 'is-danger', text: this.$t('errors.browser_does_not_support_webauthn') })
                    return false
                }

                const registerOptions = await this.axios.post('/webauthn/register/options').then(res => res.data)
                const publicKey = webauthnService.parseIncomingServerOptions(registerOptions)
                
                let options = { publicKey }
                options.signal = webauthnAbortService.createNewAbortSignal()

                let bufferedCredentials
                try {
                    bufferedCredentials = await navigator.credentials.create(options)
                }
                catch (error) {
                    const webauthnError = identifyRegistrationError(error, options)
                    this.$notify({ type: webauthnError.type, text: this.$t(webauthnError.phrase) })

                    return false
                }

                const publicKeyCredential = webauthnService.parseOutgoingCredentials(bufferedCredentials);

                this.axios.post('/webauthn/register', publicKeyCredential, {returnError: true})
                .then(response => {
                    this.deviceId = publicKeyCredential.id
                    this.deviceRegistered = true
                })
                .catch(error => {
                    if( error.response.status === 422 ) {
                        this.$notify({ type: 'is-danger', text: error.response.data.message })
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
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
    }
</script> -->