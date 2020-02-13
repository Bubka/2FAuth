<template>
    <form-wrapper :fail="fail" :success="success">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="name" :label="$t('auth.forms.name')" autofocus />
            <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" />
            <form-field :form="form" fieldName="password" inputType="password" :label="$t('auth.forms.current_password')" />
            <form-buttons :isBusy="form.isBusy" :caption="$t('commons.update')" />
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
                    name : '',
                    email : '',
                    password : '',
                })
            }
        },

        async mounted() {
            const { data } = await this.form.get('/api/user')

            this.form.fill(data)
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.fail = ''
                this.success = ''

                this.form.patch('/api/user', {returnError: true})
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