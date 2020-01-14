<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('auth.new_password') }}</h1>
                <form method="POST" action="/password/reset">
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.email') }}</label>
                        <div class="control">
                            <input id="email" type="email" class="input" v-model="email" required />
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
                        <label class="label">{{ $t('auth.forms.password_confirmation') }}</label>
                        <div class="control">
                            <input id="password_confirmation" type="password" class="input" v-model="password_confirmation" required />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.password_confirmation">{{ validationErrors.password_confirmation.toString() }}</p>
                    </div>
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-link" @click="handleSubmit">{{ $t('auth.register') }}</button>
                        </div>
                        <div class="control">
                            <router-link :to="{ name: 'login' }" class="button is-text">{{ $t('commons.cancel') }}</router-link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                token : '',
                email : '',
                password : '',
                password_confirmation : '',
                validationErrors: {}
            }
        },

        created () {
            this.email = this.$route.query.email
            this.token = this.$route.params.token
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                axios.post('api/password/reset', {
                    email: this.email,
                    password: this.password,
                    password_confirmation : this.password_confirmation,
                    token: this.token
                })
                .then(response => {
                    console.log(response)
                    // localStorage.setItem('user',response.data.message.name)
                    // localStorage.setItem('jwt',response.data.message.token)

                    // if (localStorage.getItem('jwt') != null){
                    //     this.$router.go('/');
                    // }
                })
                .catch(error => {
                    console.log(error.response)
                    if( error.response.data.errors ) {
                        this.validationErrors = error.response.data.errors
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response.data.message } });
                    }
                });
            }
        },
    }
</script>