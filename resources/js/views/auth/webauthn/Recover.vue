<template>
    <form-wrapper :title="$t('auth.webauthn.account_recovery')" :punchline="$t('auth.webauthn.recover_account_instructions')" >
        <div>
            <form @submit.prevent="recover" @keydown="form.onKeydown($event)">
                <form-checkbox :form="form" fieldName="revokeAll" :label="$t('auth.webauthn.disable_all_security_devices')" :help="$t('auth.webauthn.disable_all_security_devices_help')" />
                <form-password-field :form="form" :autocomplete="'current-password'" fieldName="password" :label="$t('auth.forms.current_password.label')" :help="$t('auth.forms.current_password.help')" />
                <div class="field">
                    <p>{{ $t('auth.forms.forgot_your_password') }}&nbsp;<router-link id="lnkResetPwd" :to="{ name: 'password.request' }" class="is-link" :aria-label="$t('auth.forms.reset_your_password')">{{ $t('auth.forms.request_password_reset') }}</router-link></p>
                </div>
                <form-buttons :caption="$t('commons.continue')" :cancelLandingView="'login'" :showCancelButton="true" :isBusy="form.isBusy" :isDisabled="form.isDisabled" :submitId="'btnRecover'" />
            </form>
        </div>
        <!-- footer -->
        <vue-footer></vue-footer>
    </form-wrapper>
</template>

<script>

    import Form from './../../../components/Form'

    export default {
        data(){
            return {
                currentPassword: '',
                deviceRegistered: false,
                deviceId : null,
                form: new Form({
                    email: '',
                    password: '',
                    token: '',
                    revokeAll: false,
                }),
            }
        },

        created () {
            this.form.email = this.$route.query.email
            this.form.token = this.$route.query.token
        },

        methods : {

            /**
             * Register a new security device
             */
            recover() {
                this.form.post('/webauthn/recover', {returnError: true})
                .then(response => {
                    this.$router.push({ name: 'login', params: { forceRefresh: true } })
                })
                .catch(error => {
                    if( error.response.status === 401 ) {

                        this.$notify({ type: 'is-danger', text: this.$t('auth.forms.authentication_failed'), duration:-1 })
                    }
                    else if (error.response.status === 422) {
                        this.$notify({ type: 'is-danger', text: error.response.data.message })
                    }
                    else {
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