<template>
    <form-wrapper :title="$t('auth.forms.login')" :fail="fail" :success="success">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" autofocus />
            <form-field :form="form" fieldName="password" inputType="password" :label="$t('auth.forms.password')" />
            <form-buttons :isBusy="form.isBusy" :caption="$t('auth.sign_in')" />
        </form>
        <p>{{ $t('auth.forms.dont_have_account_yet') }}&nbsp;<router-link :to="{ name: 'register' }" class="is-link">{{ $t('auth.register') }}</router-link></p>
        <p>{{ $t('auth.forms.forgot_your_password') }}&nbsp;<router-link :to="{ name: 'password.request' }" class="is-link">{{ $t('auth.forms.request_password_reset') }}</router-link></p>
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
                    email: '',
                    password: ''
                })
            }
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.form.post('/api/login', {returnError: true})
                .then(response => {
                    localStorage.setItem('user',response.data.message.name)
                    localStorage.setItem('jwt',response.data.message.token)

                    if (localStorage.getItem('jwt') != null){
                        this.$router.go('/');
                    }
                })
                .catch(error => {
                    if( error.response.status === 401 ) {
                        this.fail = this.$t('auth.forms.password_do_not_match')
                    }
                    else if( error.response.status !== 422 ) {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
            }
            
        },

        beforeRouteEnter (to, from, next) {
            if (localStorage.getItem('jwt')) {
                return next('/');
            }

            next();
        }
    }
</script>