<template>
    <form-wrapper :title="$t('auth.forms.new_password')">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" disabled readonly />
            <form-field :form="form" fieldName="password" inputType="password" :label="$t('auth.forms.new_password')" />
            <form-field :form="form" fieldName="password_confirmation" inputType="password" :label="$t('auth.forms.confirm_password')" />
            <form-buttons :isBusy="form.isBusy" :caption="$t('auth.forms.change_password')" :showCancelButton="true" cancelLandingView="login" />
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
                    email : '',
                    password : '',
                    password_confirmation : '',
                    token: ''
                })
            }
        },

        created () {
            this.form.email = this.$route.query.email
            this.form.token = this.$route.params.token
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.form.post('/api/password/reset', {returnError: true})
                .then(response => {

                    this.$notify({ type: 'is-success', text: response.data.status, duration:-1 })
                })
                .catch(error => {
                    if( error.response.data.resetFailed ) {

                        this.$notify({ type: 'is-danger', text: error.response.data.resetFailed, duration:-1 })
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