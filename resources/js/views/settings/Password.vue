<template>
    <form-wrapper :fail="fail" :success="success">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="password" inputType="password" :label="$t('auth.forms.new_password')" />
            <form-field :form="form" fieldName="password_confirmation" inputType="password" :label="$t('auth.forms.confirm_new_password')" />
            <form-field :form="form" fieldName="currentPassword" inputType="password" :label="$t('auth.forms.current_password.label')" :help="$t('auth.forms.current_password.help')" :hasOffset="true" />
            <form-buttons :isBusy="form.isBusy" :caption="$t('auth.forms.change_password')" />
        </form>
    </form-wrapper>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data(){
            return {
                success: '',
                fail: '',
                form: new Form({
                    currentPassword : '',
                    password : '',
                    password_confirmation : '',
                })
            }
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.fail = ''
                this.success = ''

                this.form.patch('/api/settings/password', {returnError: true})
                .then(response => {

                    this.success = response.data.message
                })
                .catch(error => {
                    if( error.response.status === 400 ) {
                        this.fail = error.response.data.message
                    }
                    else if( error.response.status !== 422 ) {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
            }
        },
    }
</script>