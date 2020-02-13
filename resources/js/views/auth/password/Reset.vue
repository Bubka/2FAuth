<template>
    <form-wrapper :title="$t('auth.forms.new_password')" :fail="fail" :success="success">
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

                    this.success = response.data.status
                })
                .catch(error => {
                    if( error.response.data.resetFailed ) {
                        this.fail = error.response.data.resetFailed
                    }
                    else if( error.response.status !== 422 ) {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
            }
        },
    }
</script>