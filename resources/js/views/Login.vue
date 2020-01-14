<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('auth.forms.login') }}</h1>
                <form method="POST" action="/login">
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.email') }}</label>
                        <div class="control">
                            <input id="email" type="email" class="input" v-model="email" required autofocus />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.email">{{ validationErrors.email.toString() }}</p>
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.password') }}</label>
                        <div class="control">
                            <input id="password" type="password" class="input" v-model="password" required />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.password">{{ validationErrors.password.toString() }}</p>
                    </div>
                    <div class="field">
                        <div class="control">
                            <button type="submit" class="button is-link" @click="handleSubmit">{{ $t('auth.sign_in') }}</button>
                        </div>
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
    </div>
</template>

<script>
    export default {
        data(){
            return {
                email : '',
                password : '',
                validationErrors: {}
            }
        },
        methods : {
            handleSubmit(e){
                e.preventDefault()

                axios.post('api/login', {
                    email: this.email,
                    password: this.password
                })
                .then(response => {
                    localStorage.setItem('user',response.data.message.name)
                    localStorage.setItem('jwt',response.data.message.token)

                    if (localStorage.getItem('jwt') != null){
                        this.$router.go('/');
                    }
                })
                .catch(error => {
                    if( error.response.status === 401 ) {
                        this.validationErrors['email'] = ''
                        this.validationErrors['password'] = [ this.$t('auth.forms.password_do_not_match') ]
                    }
                    else if( error.response.data.validation ) {
                        this.validationErrors = error.response.data.validation
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response.data.message } });
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