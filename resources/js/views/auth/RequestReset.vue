<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotifyStore } from '@/stores/notify'

    const notify = useNotifyStore()
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
                notify.error(error)
            }
        })
    }

    onBeforeRouteLeave(() => {
        notify.clear()
    })
</script>

<template>
    <FormWrapper :title="$t(isWebauthnReset ? 'auth.webauthn.account_recovery' : 'auth.forms.reset_password')" :punchline="$t(isWebauthnReset ? 'auth.webauthn.recovery_punchline' : 'auth.forms.reset_punchline')">
        <form @submit.prevent="requestPasswordReset" @keydown="form.onKeydown($event)">
            <FormField v-model="form.email" fieldName="email" :errorMessage="form.errors.get('email')" label="auth.forms.email" autofocus />
            <FormButtons
                :submitId="'btnSendResetPwd'"
                :isBusy="form.isBusy"
                :submitLabel="isWebauthnReset ? 'auth.webauthn.send_recovery_link' : 'auth.forms.send_password_reset_link'"
                :showCancelButton="true"
                @cancel="router.push({ name: 'login' })" />
        </form>
        <VueFooter />
    </FormWrapper>
</template>
