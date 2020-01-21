<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('auth.forms.reset_password') }}</h1>
                <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.email') }}</label>
                        <div class="control">
                            <input id="email" type="email" class="input" v-model="form.email" autofocus />
                        </div>
                        <field-error :form="form" field="email" />
                    </div>
                    <div class="field is-grouped">
                        <div class="control">
                            <v-button :isLoading="form.isBusy" >{{ $t('auth.forms.send_password_reset_link') }}</v-button>
                        </div>
                        <div class="control">
                            <router-link :to="{ name: 'login' }" class="button is-text">{{ $t('commons.cancel') }}</router-link>
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
        <div class="columns is-mobile is-centered" v-if="response">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                {{ response }}
            </div>
        </div>
    </div>
</template>

<script>

    import Form from './../../../components/Form'

    export default {
        data(){
            return {
                response: '',
                errorMessage: '',
                form: new Form({
                    email: '',
                })
            }
        },
        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.form.post('/api/password/email')
                .then(response => {

                    this.response = response.data.status
                })
                .catch(error => {
                    if( error.response.data.requestFailed ) {
                        this.errorMessage = error.response.data.requestFailed
                    }
                    else if( error.response.status !== 422 ) {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });

            }
        }
    }
</script>