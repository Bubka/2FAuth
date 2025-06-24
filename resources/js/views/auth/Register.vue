<script setup>
    import Form from '@/components/formElements/Form'
    import { useUserStore } from '@/stores/user'
    import { webauthnService } from '@/services/webauthn/webauthnService'
    import { useNotify } from '@2fauth/ui'
    import { useI18n } from 'vue-i18n'
    import { useErrorHandler } from '@2fauth/stores'

    const errorHandler = useErrorHandler()
    const { t } = useI18n()
    const user = useUserStore()
    const notify = useNotify()
    const router = useRouter()
    const showWebauthnRegistration = ref(false)
    const deviceId = ref(null)
    
    const registerForm = reactive(new Form({
        name : '',
        email : '',
        password : '',
        password_confirmation : '',
    }))

    const renameDeviceForm = reactive(new Form({
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
                email: response.data.email,
                preferences: response.data.preferences,
                isAdmin: response.data.is_admin ?? false,
                // TODO : add 'id' to the response
            })
            user.applyTheme()

            showWebauthnRegistration.value = true
        })
    }    

    /**
     * Register a new security device
     */
    function registerWebauthnDevice() {
        webauthnService.register().then((response) => {
            const publicKeyCredential = JSON.parse(response.config.data)

            deviceId.value = publicKeyCredential.id
        })
        .catch(error => {
            if( error.response.status === 422 ) {
                notify.alert({ text: error.response.data.message })
            }
            else {
                errorHandler.parse(error)
                router.push({ name: 'genericError' })
            }
        })
    }

    /**
     * Rename the registered device
     */
    function RenameDevice(e) {
        renameDeviceForm.patch('/webauthn/credentials/' + deviceId.value + '/name')
        .then(() => {
            notify.success({ text: t('message.auth.webauthn.device_successfully_registered') })
            router.push({ name: 'accounts' })
        })
    }

    onBeforeRouteLeave(() => {
        notify.clear()
    })
</script>

<template>
    <div>
        <!-- webauthn registration -->
        <FormWrapper v-if="showWebauthnRegistration" title="message.auth.authentication" punchline="message.auth.webauthn.enhance_security_using_webauthn">
            <div class="block">
                {{ $t('message.auth.webauthn.webauthn_uses_trusted_devices') }}
            </div>
            <div v-if="deviceId" class="field">
                <label id="lblDeviceRegistrationSuccess" class="label mb-5">{{ $t('message.auth.webauthn.device_successfully_registered') }}&nbsp;<font-awesome-icon :icon="['fas', 'check']" /></label>
                <form @submit.prevent="RenameDevice" @keydown="renameDeviceForm.onKeydown($event)">
                    <FormField v-model="renameDeviceForm.name" fieldName="name" :errorMessage="renameDeviceForm.errors.get('name')" inputType="text" placeholder="iPhone 12, TouchID, Yubikey 5C" label="message.auth.forms.name_this_device" />
                    <FormButtons :isBusy="renameDeviceForm.isBusy" :isDisabled="renameDeviceForm.isDisabled" submitLabel="message.continue" />
                </form>
            </div>
            <div v-else class="field is-grouped">
                <!-- register button -->
                <div class="control">
                    <button type="button" id="btnRegisterNewDevice" @click="registerWebauthnDevice()" class="button is-link">{{ $t('message.auth.webauthn.register_a_device') }}</button>
                </div>
                <!-- dismiss button -->
                <div class="control">
                    <RouterLink id="btnMaybeLater" :to="{ name: 'accounts' }" class="button is-text">{{ $t('message.auth.maybe_later') }}</RouterLink>
                </div>
            </div>
        </FormWrapper>
        <!-- User registration form -->
        <FormWrapper v-else title="message.auth.register" punchline="message.auth.forms.welcome_to_2fauth">
            <div class="block">
                {{ $t('message.auth.forms.you_need_an_account_to_go_further') }}
            </div>
            <form @submit.prevent="register" @keydown="registerForm.onKeydown($event)">
                <FormField v-model="registerForm.name" fieldName="name" :errorMessage="registerForm.errors.get('name')" inputType="text" label="message.auth.forms.name" autocomplete="username" :maxLength="255" autofocus />
                <FormField v-model="registerForm.email" fieldName="email" :errorMessage="registerForm.errors.get('email')" inputType="email" label="message.auth.forms.email" autocomplete="email" :maxLength="255" />
                <FormPasswordField v-model="registerForm.password" fieldName="password" :errorMessage="registerForm.errors.get('password')" :showRules="true" autocomplete="new-password" label="message.auth.forms.password" />
                <FormButtons :isBusy="registerForm.isBusy" :isDisabled="registerForm.isDisabled" submitLabel="message.auth.register" submitId="btnRegister" />
            </form>
            <div class="nav-links">
                <p>{{ $t('message.auth.forms.already_register') }}&nbsp;<RouterLink id="lnkSignIn" :to="{ name: 'login' }" class="is-link">{{ $t('message.auth.sign_in') }}</RouterLink></p>
            </div>
        </FormWrapper>
        <VueFooter />
    </div>
</template>
