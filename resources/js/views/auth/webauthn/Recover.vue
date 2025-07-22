<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotify } from '@2fauth/ui'
    import { useI18n } from 'vue-i18n'
    import { useErrorHandler } from '@2fauth/stores'

    const errorHandler = useErrorHandler()
    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const notify = useNotify()
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
                notify.alert({ text: t('notification.authentication_failed'), duration:-1 })
            }
            else if (error.response.status === 422) {
                notify.alert({ text: error.response.data.message, duration:-1 })
            }
            else  {
                errorHandler.show(error)
            }
        })
    }

    onBeforeRouteLeave(() => {
        notify.clear()
    })
</script>

<template>
    <FormWrapper title="heading.account_recovery" punchline="message.recover_account_instructions" >
        <div>
            <form @submit.prevent="recover" @keydown="form.onKeydown($event)">
                <FormCheckbox v-model="form.revokeAll" fieldName="revokeAll" label="field.disable_all_security_devices" help="field.disable_all_security_devices.help" />
                <FormPasswordField v-model="form.password" fieldName="password" :errorMessage="form.errors.get('password')" autocomplete="current-password" :showRules="false" label="field.current_password" help="field.current_password.help" />
                <div class="field">
                    <p>
                        {{ $t('message.forgot_your_password') }}&nbsp;
                        <RouterLink id="lnkResetPwd" :to="{ name: 'password.request' }" class="is-link" :aria-label="$t('label.reset_your_password')">
                            {{ $t('link.request_password_reset') }}
                        </RouterLink>
                    </p>
                </div>
                <FormButtons
                    :submitId="'btnRecover'"
                    :isBusy="form.isBusy"
                    :isDisabled="form.isDisabled"
                    submitLabel="label.continue"
                    :showCancelButton="true"
                    @cancel="router.push({ name: 'login' })" />
            </form>
        </div>
        <VueFooter />
    </FormWrapper>
</template>
