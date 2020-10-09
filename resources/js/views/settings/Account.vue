<template>
    <form-wrapper>
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="name" :label="$t('auth.forms.name')" autofocus />
            <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" />
            <form-field :form="form" fieldName="password" inputType="password" :label="$t('auth.forms.current_password.label')" :help="$t('auth.forms.current_password.help')" :hasOffset="true" />
            <form-buttons :isBusy="form.isBusy" :caption="$t('commons.update')" />
        </form>
    </form-wrapper>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data(){
            return {
                form: new Form({
                    name : '',
                    email : '',
                    password : '',
                })
            }
        },

        async mounted() {
            const { data } = await this.form.get('/api/settings/account')

            this.form.fill(data)
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.form.patch('/api/settings/account', {returnError: true})
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