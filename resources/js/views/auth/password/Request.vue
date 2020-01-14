<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('auth.reset_password') }}</h1>
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
                            <button type="submit" class="button is-link" @click="handleSubmit">{{ $t('auth.send_password_reset_link') }}</button>
                        </div>
                        <div class="control">
                            <router-link :to="{ name: 'login' }" class="button is-text">{{ $t('commons.cancel') }}</router-link>
                        </div>
                    </div>
                </form>
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
                response: ''
            }
        },
        methods : {
            handleSubmit(e){
                e.preventDefault()

                axios.post('/api/password/email', {
                    email: this.email
                })
                .then(response => {
                    this.response = response.data.status
                })
                .catch(error => {
                    console.log(error.response)
                    if( error.response.data.errors ) {
                        this.validationErrors = error.response.data.errors
                    } else if( error.response.data ) {
                        this.response = error.response.data.email
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response.data.message } });
                    }
                });
            }
        }
    }
</script>