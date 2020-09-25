<template>
    <form-wrapper>
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

                this.form.patch('/api/settings/password', {returnError: true})
                .then(response => {

                    this.$notify({ type: 'is-success', text: response.data.message })
                })
                .catch(error => {
                    if( error.response.status === 400 ) {

                        this.$notify({ type: 'is-danger', text: error.response.data.message })
                    }
                    else if( error.response.status !== 422 ) {
                        
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
            }
        },
    }
</script>