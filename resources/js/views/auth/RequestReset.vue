<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotify } from '@2fauth/ui'
    import { useErrorHandler } from '@2fauth/stores'

    const errorHandler = useErrorHandler()
    const notify = useNotify()
    const route = useRoute()

    const isWebauthnReset = route.name == 'webauthn.lost'

    const form = reactive(new Form({
        email: '',
    }))

    /**
     * Submits the reset request to the backend
     */
    function requestPasswordReset(e) {
        notify.clear()
        form.post(isWebauthnReset ? '/webauthn/lost' : '/user/password/lost', {returnError: true})
        .then(response => {
            notify.success({ text: response.data.message, duration:-1 })
        })
        .catch(error => {
            if( error.response.data.requestFailed ) {
                notify.alert({ text: error.response.data.requestFailed, duration:-1 })
            }
            else if( error.response.status !== 422 ) {
                errorHandler.show(error)
            }
        })
    }

    onBeforeRouteLeave(() => {
        notify.clear()
    })
</script>

<template>
    <FormWrapper :title="isWebauthnReset ? 'message.auth.webauthn.account_recovery' : 'message.auth.forms.reset_password'" :punchline="isWebauthnReset ? 'message.auth.webauthn.recovery_punchline' : 'message.auth.forms.reset_punchline'">
        <div v-if="isWebauthnReset" class="block">
            {{ $t('message.auth.webauthn.ensure_you_open_mail_in_trusted_device') }}
        </div>
        <form @submit.prevent="requestPasswordReset" @keydown="form.onKeydown($event)">
            <FormField v-model="form.email" fieldName="email" :errorMessage="form.errors.get('email')" label="message.auth.forms.email" autofocus />
            <FormButtons
                :submitId="'btnSendResetPwd'"
                :isBusy="form.isBusy"
                :submitLabel="isWebauthnReset ? 'message.auth.webauthn.send_recovery_link' : 'message.auth.forms.send_password_reset_link'"
                :showCancelButton="true"
                @cancel="router.push({ name: 'login' })" />
        </form>
        <VueFooter />
    </FormWrapper>
</template>
