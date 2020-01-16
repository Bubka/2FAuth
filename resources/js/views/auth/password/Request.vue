<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('auth.forms.reset_password') }}</h1>
                <form method="POST" action="/password/email">
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.email') }}</label>
                        <div class="control">
                            <input id="email" type="email" class="input" v-model="email" required autofocus />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.email">{{ validationErrors.email.toString() }}</p>
                    </div>
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-link" @click="handleSubmit">{{ $t('auth.forms.send_password_reset_link') }}</button>
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
        <div class="columns is-mobile is-centered" v-if="response">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                {{ response }}
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
                validationErrors: {},
                response: '',
                errorMessage: ''
            }
        },
        methods : {
            async handleSubmit(e){
                e.preventDefault()

                this.validationErrors = {}

                try {
                    const { data } = await axios.post('/api/password/email', {
                        email: this.email
                    })

                    this.response = data.status
                }
                catch (error) {
                    if( error.response.data.errors ) {
                        this.validationErrors = error.response.data.errors
                    }
                    else if( error.response.data.requestFailed ) {
                        this.errorMessage = error.response.data.requestFailed
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                }
            }
        }
    }
</script>