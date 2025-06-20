<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotifyStore } from '@/stores/notify'
    import { useI18n } from 'vue-i18n'

    const { t } = useI18n()
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
                notify.alert({ text: t('message.auth.forms.authentication_failed'), duration:-1 })
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
    <FormWrapper title="message.auth.webauthn.account_recovery" punchline="message.auth.webauthn.recover_account_instructions" >
        <div>
            <form @submit.prevent="recover" @keydown="form.onKeydown($event)">
                <FormCheckbox v-model="form.revokeAll" fieldName="revokeAll" label="message.auth.webauthn.disable_all_security_devices" help="message.auth.webauthn.disable_all_security_devices_help" />
                <FormPasswordField v-model="form.password" fieldName="password" :errorMessage="form.errors.get('password')" autocomplete="current-password" :showRules="false" label="message.auth.forms.current_password.label" help="message.auth.forms.current_password.help" />
                <div class="field">
                    <p>
                        {{ $t('message.auth.forms.forgot_your_password') }}&nbsp;
                        <RouterLink id="lnkResetPwd" :to="{ name: 'password.request' }" class="is-link" :aria-label="$t('message.auth.forms.reset_your_password')">
                            {{ $t('message.auth.forms.request_password_reset') }}
                        </RouterLink>
                    </p>
                </div>
                <FormButtons
                    :submitId="'btnRecover'"
                    :isBusy="form.isBusy"
                    :isDisabled="form.isDisabled"
                    submitLabel="message.continue"
                    :showCancelButton="true"
                    @cancel="router.push({ name: 'login' })" />
            </form>
        </div>
        <VueFooter />
    </FormWrapper>
</template>
