<template>
    <form-wrapper :title="$t('auth.register')" :punchline="$t('auth.forms.register_punchline')">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="name" inputType="text" :label="$t('auth.forms.name')" autofocus />
            <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" />
            <form-field :form="form" fieldName="password" inputType="password" :label="$t('auth.forms.password')" />
            <form-field :form="form" fieldName="password_confirmation" inputType="password" :label="$t('auth.forms.confirm_password')" />
            <form-buttons :isBusy="form.isBusy" :isDisabled="form.isDisabled" :caption="$t('auth.register')" />
        </form>
        <p>{{ $t('auth.forms.already_register') }}&nbsp;<router-link :to="{ name: 'login' }" class="is-link">{{ $t('auth.sign_in') }}</router-link></p>
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
                    password_confirmation : '',
                })
            }
        },

        methods : {
            async handleSubmit(e) {
                e.preventDefault()

                this.form.post('/api/register', {returnError: true})
                .then(response => {
                    localStorage.setItem('user',response.data.message.name)
                    localStorage.setItem('jwt',response.data.message.token)

                    if (localStorage.getItem('jwt') != null){
                        this.$router.push({ name: 'accounts', params: { toRefresh: true } })
                    }
                })
                .catch(error => {
                    console.log(error.response)
                    if( error.response.status === 422 && error.response.data.errors.taken ) {

                        this.$notify({ type: 'is-danger', text: this.$t('errors.already_one_user_registered') + ' ' + this.$t('errors.cannot_register_more_user'), duration:-1 })
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