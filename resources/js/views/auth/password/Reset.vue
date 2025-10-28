<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotify } from '@2fauth/ui'
    import { useErrorHandler } from '@2fauth/stores'

    const errorHandler = useErrorHandler()
    const notify = useNotify()
    const router = useRouter()
    const route = useRoute()
    
    const isPending = ref(true)
    const form = reactive(new Form({
        email : route.query.email,
        password : '',
        password_confirmation : '',
        token: route.query.token
    }))

    /**
     * Submits the password reset to the backend
     */
    function resetPassword(e) {
        form.password_confirmation = form.password

        form.post('/user/password/reset', {returnError: true})
        .then(response => {
            form.password = ''
            form.password_confirmation = ''
            isPending.value = false
            notify.success({ text: response.data.message, duration:-1 })
        })
        .catch(error => {
            if( error.response.data.resetFailed ) {
                notify.alert({ text: error.response.data.resetFailed, duration:-1 })
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
    <FormWrapper title="heading.new_password">
        <form @submit.prevent="resetPassword" @keydown="form.onKeydown($event)">
            <FormField v-model="form.email" :isDisabled="true" fieldName="email" :errorMessage="form.errors.get('email')" label="field.email" autofocus />
            <FormPasswordField v-model="form.password" fieldName="password" :errorMessage="form.errors.get('password')" autocomplete="new-password" :showRules="true" label="field.new_password" />
            <FormFieldError v-if="form.errors.get('token') != undefined" :error="form.errors.get('token')" :field="form.token" />
            <FormButtons
                v-if="isPending"
                :submitId="'btnResetPwd'"
                :isBusy="form.isBusy"
                submitLabel="label.change_password"
                :showCancelButton="true"
                @cancel="router.push({ name: 'login' })" />
            <RouterLink v-if="!isPending" id="btnContinue" :to="{ name: 'accounts' }" class="button is-link">{{ $t('link.continue') }}</RouterLink>
        </form>
        <VueFooter />
    </FormWrapper>
</template>
