<template>
    <form-wrapper :title="$t('auth.webauthn.account_recovery')" :punchline="$t('auth.webauthn.recovery_punchline')">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" autofocus />
            <form-buttons :isBusy="form.isBusy" :caption="$t('auth.webauthn.send_recovery_link')" :showCancelButton="true" cancelLandingView="login" />
        </form>
    </form-wrapper>
</template>

<script>

    import Form from './../../../components/Form'

    export default {
        data(){
            return {
                form: new Form({
                    email: '',
                })
            }
        },
        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.form.post('/webauthn/lost', {returnError: true})
                .then(response => {
                    
                    this.$notify({ type: 'is-success', text: response.data.message, duration:-1 })
                })
                .catch(error => {
                    if( error.response.data.requestFailed ) {

                        this.$notify({ type: 'is-danger', text: error.response.data.requestFailed, duration:-1 })
                    }
                    else if( error.response.status !== 422 ) {

                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });

            }
        },

        beforeRouteLeave (to, from, next) {
            this.$notify({
                clean: true
            })

            next()
        }
    }
</script>