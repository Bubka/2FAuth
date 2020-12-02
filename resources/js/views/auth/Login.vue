<template>
    <form-wrapper :title="$t('auth.forms.login')" :punchline="punchline" v-if="username">
        <div v-if="isDemo" class="notification is-info has-text-centered" v-html="$t('auth.forms.welcome_to_demo_app_use_those_credentials')" />
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" autofocus />
            <form-field :form="form" fieldName="password" inputType="password" :label="$t('auth.forms.password')" />
            <form-buttons :isBusy="form.isBusy" :caption="$t('auth.sign_in')" />
        </form>
        <p v-if=" !username ">{{ $t('auth.forms.dont_have_account_yet') }}&nbsp;<router-link :to="{ name: 'register' }" class="is-link">{{ $t('auth.register') }}</router-link></p>
        <p>{{ $t('auth.forms.forgot_your_password') }}&nbsp;<router-link :to="{ name: 'password.request' }" class="is-link">{{ $t('auth.forms.request_password_reset') }}</router-link></p>
    </form-wrapper>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data(){
            return {
                username: null,
                isDemo: this.$root.appSettings.isDemoApp,
                form: new Form({
                    email: '',
                    password: ''
                })
            }
        },

        computed : {
            punchline: function() {
                return this.isDemo ? '' : this.$t('auth.welcome_back_x', [this.username])
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
                        this.$router.push({ name: 'accounts', params: { toRefresh: true } })
                    }
                })
                .catch(error => {
                    if( error.response.status === 401 ) {

                        this.$notify({ type: 'is-danger', text: this.$t('auth.forms.password_do_not_match'), duration:-1 })
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

            next(async vm => {
                const { data } = await vm.axios.post('api/checkuser')

                if( !data.username ) {
                    return next({ name: 'register' });
                }
                else {
                    vm.username = data.username
                }
            });

            next();
        },

        beforeRouteLeave (to, from, next) {
            this.$notify({
                clean: true
            })
            
            next()
        }
    }
</script>