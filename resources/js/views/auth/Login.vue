<script setup>
    import Form from '@/components/formElements/Form'
    import SsoConnectLink from '@/components/SsoConnectLink.vue'
    import { useUserStore } from '@/stores/user'
    import { useNotify } from '@2fauth/ui'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { webauthnService } from '@/services/webauthn/webauthnService'
    import { useI18n } from 'vue-i18n'
    import { useErrorHandler } from '@2fauth/stores'

    const errorHandler = useErrorHandler()
    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const router = useRouter()
    const user = useUserStore()
    const notify = useNotify()
    const appSettings = useAppSettingsStore()
    const showWebauthnForm = user.preferences.useWebauthnOnly ? true : useStorage($2fauth.prefix + 'showWebauthnForm', false) 
    const form = reactive(new Form({
        email: '',
        password: ''
    }))
    const isBusy = ref(false)
    const activeForm = ref()

    onMounted(() => {
        if (appSettings.enableSso == true && appSettings.useSsoOnly == true) {
            activeForm.value = 'sso'
        }
        else if (showWebauthnForm.value == true) {
            activeForm.value = 'webauthn'
        }
        else activeForm.value = 'legacy'
    })

    

    /**
     * Toggle the form between legacy and webauthn method
     */
    function switchToForm(formName) {
        form.clear()
        activeForm.value = formName
        showWebauthnForm.value = activeForm.value == 'webauthn'
    }

    /**
     * Sign in using the login/password form
     */
    function LegacysignIn(e) {
        notify.clear()
        isBusy.value = true

        form.post('/user/login', {returnError: true}).then(async (response) => {
            await user.loginAs({
                id: response.data.id,
                name: response.data.name,
                email: response.data.email,
                oauth_provider: response.data.oauth_provider,
                authenticated_by_proxy: false,
                preferences: response.data.preferences,
                isAdmin: response.data.is_admin,
            })

            router.push({ name: 'accounts' })
        })
        .catch(error => {
            if( error.response.status === 401 ) {
                notify.alert({text: t('message.auth.forms.authentication_failed'), duration: 10000 })
            }
            else if( error.response.status !== 422 ) {
                errorHandler.parse(error)
                router.push({ name: 'genericError' })
            }
        })
        .finally(() => {
            isBusy.value = false
        })
    }

    /**
     * Sign in using webauthn
     */
    function webauthnLogin() {
        notify.clear()
        form.clear()
        isBusy.value = true

        webauthnService.authenticate(form.email).then(async (response) => {
            await user.loginAs({
                id: response.data.id,
                name: response.data.name,
                email: response.data.email,
                oauth_provider: response.data.oauth_provider,
                authenticated_by_proxy: false,
                preferences: response.data.preferences,
                isAdmin: response.data.is_admin,
            })

            router.push({ name: 'accounts' })
        })
        .catch(error => {
            if ('webauthn' in error) {
                if (error.name == 'is-warning') {
                    notify.warn({ text: t(error.message) })
                }
                else notify.alert({ text: t(error.message) })
            }
            else if( error.response.status === 401 ) {
                notify.alert({text: t('message.auth.forms.authentication_failed'), duration: 10000 })
            }
            else if( error.response.status == 422 ) {
                form.errors.set(form.extractErrors(error.response))
            }
            else {
                errorHandler.parse(error)
                router.push({ name: 'genericError' })
            }
        })
        .finally(() => {
            isBusy.value = false
        })
    }

</script>

<template>
    <!-- webauthn authentication -->
    <FormWrapper v-if="activeForm == 'webauthn'" title="message.auth.forms.webauthn_login" punchline="message.auth.welcome_to_2fauth">
        <div v-if="appSettings.enableSso == true && appSettings.useSsoOnly == true" class="notification is-warning has-text-centered" v-html="$t('message.auth.forms.sso_only_form_restricted_to_admin')" />
        <div class="field">
            {{ $t('message.auth.webauthn.use_security_device_to_sign_in') }}
        </div>
        <form id="frmWebauthnLogin" @submit.prevent="webauthnLogin" @keydown="form.onKeydown($event)">
            <FormField v-model="form.email" fieldName="email" :errorMessage="form.errors.get('email')" inputType="email" label="message.auth.forms.email" autofocus />
            <FormButtons :isBusy="isBusy" submitLabel="message.continue" submitId="btnContinue"/>
        </form>
        <div class="nav-links">
            <p>
                {{ $t('message.auth.webauthn.lost_your_device') }}&nbsp;
                <RouterLink id="lnkRecoverAccount" :to="{ name: 'webauthn.lost' }" class="is-link">
                    {{ $t('message.auth.webauthn.recover_your_account') }}
                </RouterLink>
            </p>
            <p>{{ $t('message.auth.sign_in_using') }}&nbsp;
                <a id="lnkSignWithLegacy" role="button" class="is-link" @keyup.enter="switchToForm('legacy')" @click="switchToForm('legacy')" tabindex="0">
                    {{ $t('message.auth.login_and_password') }}
                </a>
            </p>
            <p v-if="appSettings.disableRegistration == false && appSettings.useSsoOnly == false" class="mt-4">
                {{ $t('message.auth.forms.dont_have_account_yet') }}&nbsp;
                <RouterLink id="lnkRegister" :to="{ name: 'register' }" class="is-link">
                    {{ $t('message.auth.register') }}
                </RouterLink>
            </p>
            <div v-if="appSettings.enableSso == true && Object.values($2fauth.config.sso).includes(true)" class="columns mt-4 is-variable is-1">
                <div class="column is-narrow py-1">
                    {{ $t('message.auth.or_continue_with') }}
                </div>
                <div class="column py-1">
                    <div class="buttons">
                        <template v-for="(isEnabled, provider) in $2fauth.config.sso">
                            <SsoConnectLink v-if="isEnabled" :class="'is-outlined is-small'" :provider="provider" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </FormWrapper>
    <!-- SSO only links -->
     <!-- TODO: compléter la punchline avec les lignes manquantes de auth.webauthn.webauthn_uses_trusted_devices -->
    <FormWrapper v-else-if="activeForm == 'sso'" title="message.auth.forms.sso_login" punchline="message.auth.welcome_to_2fauth">
        <div v-if="$2fauth.isDemoApp" class="notification is-info has-text-centered is-radiusless">
            {{ $t('message.auth.forms.welcome_to_demo_app') }}<br />
            <i18n-t keypath="message.auth.forms.sign_in_using_email_password" tag="span">
                <template v-slot:email>
                    <strong>demo@2fauth.app</strong>
                </template>
                <template v-slot:password>
                    <strong>demo</strong>
                </template>
            </i18n-t>
        </div>
        <div v-if="$2fauth.isTestingApp" class="notification is-warning has-text-centered is-radiusless">
            {{ $t('message.auth.forms.welcome_to_testing_app') }}
            <i18n-t keypath="message.auth.forms.use_those_credentials" tag="span">
                <template v-slot:email>
                    <strong>testing@2fauth.app</strong>
                </template>
                <template v-slot:password>
                    <strong>password</strong>
                </template>
            </i18n-t>
        </div>
        <div class="nav-links">
            <p class="">{{ $t('message.auth.password_login_and_webauthn_are_disabled') }}</p>
            <p class="">{{ $t('message.auth.sign_in_using_sso') }}</p>
        </div>
        <div v-if="Object.values($2fauth.config.sso).includes(true)" class="buttons mt-4">
            <template v-for="(isEnabled, provider) in $2fauth.config.sso">
                <SsoConnectLink v-if="isEnabled" :provider="provider" />
            </template>
        </div>
        <p v-else class="is-italic">- {{ $t('message.auth.no_provider') }} -</p>
        <div class="nav-links">
            <p>
                {{ $t('message.auth.no_sso_provider_or_provider_is_missing') }}&nbsp;
                <a id="lnkSsoDocs" class="is-link" tabindex="0" :href="$2fauth.urls.ssoDocUrl" target="_blank">
                    {{ $t('message.auth.see_how_to_enable_sso') }}
                </a>
            </p>
            <p >{{ $t('message.auth.if_administrator') }}&nbsp;
                <a id="lnkSignWithLegacy" role="button" class="is-link" @keyup.enter="switchToForm('legacy')" @click="switchToForm('legacy')" tabindex="0">
                    {{ $t('message.auth.sign_in_here') }}
                </a>
            </p>
        </div>
    </FormWrapper>
    <!-- login/password legacy form -->
    <FormWrapper v-else-if="activeForm == 'legacy'" title="message.auth.forms.login" punchline="message.auth.welcome_to_2fauth">
        <div v-if="$2fauth.isDemoApp" class="notification is-info has-text-centered is-radiusless">
            {{ $t('message.auth.forms.welcome_to_demo_app') }}<br />
            <i18n-t keypath="message.auth.forms.sign_in_using_email_password" tag="span">
                <template v-slot:email>
                    <strong>demo@2fauth.app</strong>
                </template>
                <template v-slot:password>
                    <strong>demo</strong>
                </template>
            </i18n-t>
        </div>
        <div v-if="$2fauth.isTestingApp" class="notification is-warning has-text-centered is-radiusless">
            {{ $t('message.auth.forms.welcome_to_testing_app') }}
            <i18n-t keypath="message.auth.forms.use_those_credentials" tag="span">
                <template v-slot:email>
                    <strong>testing@2fauth.app</strong>
                </template>
                <template v-slot:password>
                    <strong>password</strong>
                </template>
            </i18n-t>
        </div>
        <div v-if="appSettings.enableSso == true && appSettings.useSsoOnly == true" class="notification is-warning has-text-centered" v-html="$t('message.auth.forms.sso_only_form_restricted_to_admin')" />
        <form id="frmLegacyLogin" @submit.prevent="LegacysignIn" @keydown="form.onKeydown($event)">
            <FormField v-model="form.email" fieldName="email" :errorMessage="form.errors.get('email')" inputType="email" label="message.auth.forms.email" autocomplete="username" autofocus />
            <FormPasswordField v-model="form.password" fieldName="password" :errorMessage="form.errors.get('password')" label="message.auth.forms.password" autocomplete="current-password" />
            <FormButtons :isBusy="isBusy" submitLabel="message.auth.sign_in" submitId="btnSignIn"/>
        </form>
        <div class="nav-links">
            <p>{{ $t('message.auth.forms.forgot_your_password') }}&nbsp;
                <RouterLink id="lnkResetPwd" :to="{ name: 'password.request' }" class="is-link" :aria-label="$t('message.auth.forms.reset_your_password')">
                    {{ $t('message.auth.forms.request_password_reset') }}
                </RouterLink>
            </p>
            <p >{{ $t('message.auth.sign_in_using') }}&nbsp;
                <a id="lnkSignWithWebauthn" role="button" class="is-link" @keyup.enter="switchToForm('webauthn')" @click="switchToForm('webauthn')" tabindex="0" :aria-label="$t('message.auth.sign_in_using_security_device')">
                    {{ $t('message.auth.webauthn.security_device') }}
                </a>
            </p>
            <p v-if="appSettings.disableRegistration == false && appSettings.useSsoOnly == false" class="mt-4">
                {{ $t('message.auth.forms.dont_have_account_yet') }}&nbsp;
                <RouterLink id="lnkRegister" :to="{ name: 'register' }" class="is-link">
                    {{ $t('message.auth.register') }}
                </RouterLink>
            </p>
            <div v-if="appSettings.enableSso && Object.values($2fauth.config.sso).includes(true)" class="columns mt-4 is-variable is-1">
                <div class="column is-narrow py-1">
                    {{ $t('message.auth.or_continue_with') }}
                </div>
                <div class="column py-1">
                    <div class="buttons">
                        <template v-for="(isEnabled, provider) in $2fauth.config.sso">
                            <SsoConnectLink v-if="isEnabled" :class="'is-outlined is-small'" :provider="provider" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </FormWrapper>
    <VueFooter />
</template>
