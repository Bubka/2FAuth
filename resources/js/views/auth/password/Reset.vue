<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('auth.forms.new_password') }}</h1>
                <form method="POST">
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.email') }}</label>
                        <div class="control">
                            <input id="email" type="email" class="input" v-model="email" disabled readonly />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.email">{{ validationErrors.email.toString() }}</p>
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.new_password') }}</label>
                        <div class="control">
                            <input id="password" type="password" class="input" v-model="password" required />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.password">{{ validationErrors.password.toString() }}</p>
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.confirm_password') }}</label>
                        <div class="control">
                            <input id="password_confirmation" type="password" class="input" v-model="password_confirmation" required />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.password_confirmation">{{ validationErrors.password_confirmation.toString() }}</p>
                    </div>
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-link" @click="handleSubmit">{{ $t('auth.forms.change_password') }}</button>
                        </div>
                        <div class="control">
                            <router-link :to="{ name: 'login' }" class="button is-text">{{ $t('commons.cancel') }}</router-link>
                        </div>
                    </div>
                </form>
                <br />
                <span class="tag is-danger" v-if="errorMessage">
                    {{ errorMessage }}
                </span>
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
                password_confirmation : '',
                token : '',
                validationErrors: {},
                errorMessage: ''
            }
        },

        created () {
            this.email = this.$route.query.email
            this.token = this.$route.params.token
        },

        methods : {
            async handleSubmit(e) {
                e.preventDefault()

                this.validationErrors = {}

                try {
                    const { data } = await axios.post('/api/password/reset', {
                        email: this.email,
                        password: this.password,
                        password_confirmation : this.password_confirmation,
                        token: this.token
                    })

                    this.$router.go('/');
                }
                catch (error) {
                    if( error.response.data.errors ) {
                        this.validationErrors = error.response.data.errors
                    }
                    else if( error.response.data.resetFailed ) {
                        this.errorMessage = error.response.data.resetFailed
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response.data.message } });
                    }
                }
            }
        },
    }
</script>