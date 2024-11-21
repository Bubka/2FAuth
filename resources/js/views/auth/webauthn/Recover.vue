<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotifyStore } from '@/stores/notify'

    const $2fauth = inject('2fauth')
    const notify = useNotifyStore()
    const router = useRouter()
    const route = useRoute()
    const showWebauthnForm = useStorage($2fauth.prefix + 'showWebauthnForm', false)
    
    const form = reactive(new Form({
        email : route.query.email,
        password : '',
        token: route.query.token,
        revokeAll: false,
    }))

    /**
     * Submits the recovery to the backend
     */
    function recover(e) {
        notify.clear()
        form.post('/webauthn/recover', {returnError: true})
        .then(response => {
            showWebauthnForm.value = false
            router.push({ name: 'login' })
        })
        .catch(error => {
            if ( error.response.status === 401 ) {
                notify.alert({ text: trans('auth.forms.authentication_failed'), duration:-1 })
            }
            else if (error.response.status === 422) {
                notify.alert({ text: error.response.data.message, duration:-1 })
            }
            else  {
                notify.error(error)
            }
        })
    }

    onBeforeRouteLeave(() => {
        notify.clear()
    })
</script>

<template>
    <FormWrapper :title="$t('auth.webauthn.account_recovery')" :punchline="$t('auth.webauthn.recover_account_instructions')" >
        <div>
            <form @submit.prevent="recover" @keydown="form.onKeydown($event)">
                <FormCheckbox v-model="form.revokeAll" fieldName="revokeAll" label="auth.webauthn.disable_all_security_devices" help="auth.webauthn.disable_all_security_devices_help" />
                <FormPasswordField v-model="form.password" fieldName="password" :fieldError="form.errors.get('password')" autocomplete="current-password" :showRules="false" label="auth.forms.current_password.label" help="auth.forms.current_password.help" />
                <div class="field">
                    <p>
                        {{ $t('auth.forms.forgot_your_password') }}&nbsp;
                        <RouterLink id="lnkResetPwd" :to="{ name: 'password.request' }" class="is-link" :aria-label="$t('auth.forms.reset_your_password')">
                            {{ $t('auth.forms.request_password_reset') }}
                        </RouterLink>
                    </p>
                </div>
                <FormButtons
                    :submitId="'btnRecover'"
                    :isBusy="form.isBusy"
                    :isDisabled="form.isDisabled"
                    :caption="$t('commons.continue')"
                    :showCancelButton="true"
                    cancelLandingView="login" />
            </form>
        </div>
        <VueFooter />
    </FormWrapper>
</template>
