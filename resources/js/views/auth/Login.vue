<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('auth.forms.login') }}</h1>
                <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.email') }}</label>
                        <div class="control">
                            <input id="email" type="email" class="input" v-model="form.email" autofocus />
                        </div>
                        <field-error :form="form" field="email" />
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.password') }}</label>
                        <div class="control">
                            <input id="password" type="password" class="input" v-model="form.password" />
                        </div>
                        <field-error :form="form" field="password" />
                    </div>
                    <div class="field">
                        <div class="control">
                            <v-button :isLoading="form.isBusy" >{{ $t('auth.sign_in') }}</v-button>
                        </div>
                    </div>
                    <div class="field" v-if="errorMessage">
                        <span class="tag is-danger">
                            {{ errorMessage }}
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <div class="columns is-mobile is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                {{ $t('auth.forms.dont_have_account_yet') }}&nbsp;<router-link :to="{ name: 'register' }" class="is-link">
                    {{ $t('auth.register') }}
                </router-link>
            </div>
        </div>
        <div class="columns is-mobile is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                {{ $t('auth.forms.forgot_your_password') }}&nbsp;<router-link :to="{ name: 'password.request' }" class="is-link">
                    {{ $t('auth.forms.request_password_reset') }}
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data(){
            return {
                errorMessage: '',
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
                        this.errorMessage = this.$t('auth.forms.password_do_not_match')
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