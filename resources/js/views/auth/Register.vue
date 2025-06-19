<script setup>
    import Form from '@/components/formElements/Form'
    import { useUserStore } from '@/stores/user'
    import { webauthnService } from '@/services/webauthn/webauthnService'
    import { useNotifyStore } from '@/stores/notify'

    const user = useUserStore()
    const notify = useNotifyStore()
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
                notify.error(error);
            }
        })
    }

    /**
     * Rename the registered device
     */
    function RenameDevice(e) {
        renameDeviceForm.patch('/webauthn/credentials/' + deviceId.value + '/name')
        .then(() => {
            notify.success({ text: trans('auth.webauthn.device_successfully_registered') })
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
        <FormWrapper v-if="showWebauthnRegistration" title="auth.authentication" punchline="auth.webauthn.enhance_security_using_webauthn">
            <div v-if="deviceId" class="field">
                <label id="lblDeviceRegistrationSuccess" class="label mb-5">{{ $t('auth.webauthn.device_successfully_registered') }}&nbsp;<font-awesome-icon :icon="['fas', 'check']" /></label>
                <form @submit.prevent="RenameDevice" @keydown="renameDeviceForm.onKeydown($event)">
                    <FormField v-model="renameDeviceForm.name" fieldName="name" :errorMessage="renameDeviceForm.errors.get('name')" inputType="text" placeholder="iPhone 12, TouchID, Yubikey 5C" label="auth.forms.name_this_device" />
                    <FormButtons :isBusy="renameDeviceForm.isBusy" :isDisabled="renameDeviceForm.isDisabled" submitLabel="commons.continue" />
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
                <FormField v-model="registerForm.name" fieldName="name" :errorMessage="registerForm.errors.get('name')" inputType="text" label="auth.forms.name" autocomplete="username" :maxLength="255" autofocus />
                <FormField v-model="registerForm.email" fieldName="email" :errorMessage="registerForm.errors.get('email')" inputType="email" label="auth.forms.email" autocomplete="email" :maxLength="255" />
                <FormPasswordField v-model="registerForm.password" fieldName="password" :errorMessage="registerForm.errors.get('password')" :showRules="true" autocomplete="new-password" label="auth.forms.password" />
                <FormButtons :isBusy="registerForm.isBusy" :isDisabled="registerForm.isDisabled" submitLabel="auth.register" submitId="btnRegister" />
            </form>
            <div class="nav-links">
                <p>{{ $t('auth.forms.already_register') }}&nbsp;<RouterLink id="lnkSignIn" :to="{ name: 'login' }" class="is-link">{{ $t('auth.sign_in') }}</RouterLink></p>
            </div>
        </FormWrapper>
        <!-- footer -->
        <VueFooter />
    </div>
</template>
