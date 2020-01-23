<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('auth.forms.change_password') }}</h1>
                <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.current_password') }}</label>
                        <div class="control">
                            <input id="currentPassword" type="password" class="input" v-model="form.currentPassword" />
                        </div>
                        <field-error :form="form" field="currentPassword" />
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.new_password') }}</label>
                        <div class="control">
                            <input id="password" type="password" class="input" v-model="form.password" />
                        </div>
                        <field-error :form="form" field="password" />
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.confirm_password') }}</label>
                        <div class="control">
                            <input id="password_confirmation" type="password" class="input" v-model="form.password_confirmation" />
                        </div>
                        <field-error :form="form" field="password_confirmation" />
                    </div>
                    <div class="field is-grouped">
                        <div class="control">
                            <v-button :isLoading="form.isBusy" >{{ $t('auth.forms.change_password') }}</v-button>
                        </div>
                        <div class="control">
                            <router-link :to="{ name: 'profile' }" class="button is-text">{{ $t('commons.cancel') }}</router-link>
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
            <div class="has-text-link column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <span class="tag is-success">
                    {{ response }}
                </span>
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
                    currentPassword : '',
                    password : '',
                    password_confirmation : '',
                })
            }
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.errorMessage = ''
                this.response = ''

                this.form.patch('/api/password')
                .then(response => {

                    this.response = response.data.message
                })
                .catch(error => {
                    if( error.response.status === 400 ) {
                        this.errorMessage = error.response.data.message
                    }
                    else if( error.response.status !== 422 ) {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
            }
        },
    }
</script>