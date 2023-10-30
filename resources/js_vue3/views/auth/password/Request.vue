<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotifyStore } from '@/stores/notify'

    const notify = useNotifyStore()

    const form = reactive(new Form({
        email: '',
    }))

    /**
     * Submits the password reset request to the backend
     */
    function requestPasswordReset(e) {
        form.post('/user/password/lost', {returnError: true})
        .then(response => {
            notify.success({ text: response.data.message, duration:-1 })
        })
        .catch(error => {
            console.log(error)
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
    <FormWrapper :title="$t('auth.forms.reset_password')" :punchline="$t('auth.forms.reset_punchline')">
        <form @submit.prevent="requestPasswordReset" @keydown="form.onKeydown($event)">
            <FormField v-model="form.email" fieldName="email" :fieldError="form.errors.get('email')" label="auth.forms.email" autofocus />
            <FormButtons
                :submitId="'btnSendResetPwd'"
                :isBusy="form.isBusy"
                :caption="$t('auth.forms.send_password_reset_link')"
                :showCancelButton="true"
                cancelLandingView="login" />
        </form>
        <VueFooter />
    </FormWrapper>
</template>
