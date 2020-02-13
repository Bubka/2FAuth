<template>
    <form-wrapper :title="$t('auth.forms.reset_password')" :fail="fail" :success="success">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" autofocus />
            <form-buttons :isBusy="form.isBusy" :caption="$t('auth.forms.send_password_reset_link')" :showCancelButton="true" cancelLandingView="login" />
        </form>
    </form-wrapper>
</template>

<script>

    import Form from './../../../components/Form'

    export default {
        data(){
            return {
                success: '',
                fail: '',
                form: new Form({
                    email: '',
                })
            }
        },
        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.form.post('/api/password/email', {returnError: true})
                .then(response => {

                    this.success = response.data.status
                })
                .catch(error => {
                    if( error.response.data.requestFailed ) {
                        this.fail = error.response.data.requestFailed
                    }
                    else if( error.response.status !== 422 ) {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });

            }
        }
    }
</script>