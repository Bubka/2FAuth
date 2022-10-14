<template>
    <form-wrapper :title="$t('auth.forms.new_password')">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="email" inputType="email" :label="$t('auth.forms.email')" isDisabled="true" readonly />
            <form-password-field :form="form" fieldName="password" :autocomplete="'new-password'" :showRules="true" :label="$t('auth.forms.new_password')" />
            <form-buttons v-if="pending" :isBusy="form.isBusy" :caption="$t('auth.forms.change_password')" :showCancelButton="true" cancelLandingView="login" />
            <router-link v-if="!pending" id="btnContinue" :to="{ name: 'accounts' }" class="button is-link">{{ $t('commons.continue') }}</router-link>
        </form>
        <!-- footer -->
        <vue-footer></vue-footer>
    </form-wrapper>
</template>

<script>

    import Form from './../../../components/Form'

    export default {
        data(){
            return {
                pending: true,
                form: new Form({
                    email : '',
                    password : '',
                    password_confirmation : '',
                    token: ''
                })
            }
        },

        created () {
            this.form.email = this.$route.query.email
            this.form.token = this.$route.query.token
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.form.password_confirmation = this.form.password

                this.form.post('/user/password/reset', {returnError: true})
                .then(response => {
                    this.pending = false
                    this.$notify({ type: 'is-success', text: response.data.message, duration:-1 })

                })
                .catch(error => {
                    if( error.response.data.resetFailed ) {

                        this.$notify({ type: 'is-danger', text: error.response.data.resetFailed, duration:-1 })
                    }
                    else if( error.response.status !== 422 ) {
                        
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
            }
        },

        beforeRouteLeave (to, from, next) {
            this.$notify({
                clean: true
            })

            next()
        }
    }
</script>