<template>
    <form-wrapper :title="$t('auth.webauthn.register_a_new_device')" :punchline="$t('auth.webauthn.recover_account_instructions')" >
        <div v-if="deviceRegistered" class="field">
            <label class="label mb-5">{{ $t('auth.webauthn.device_successfully_registered') }}&nbsp;<font-awesome-icon :icon="['fas', 'check']" /></label>
            <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
                <form-field :form="form" fieldName="name" inputType="text" placeholder="iPhone 12, TouchID, Yubikey 5C" :label="$t('auth.forms.name_this_device')" />
                <form-buttons :isBusy="form.isBusy" :isDisabled="form.isDisabled" :caption="$t('commons.continue')" />
            </form>
        </div>
        <div v-else>
            <div class="field">
                <input id="unique" name="unique" type="checkbox" class="is-checkradio is-info" v-model="unique" >
                <label for="unique" class="label">{{ $t('auth.webauthn.disable_all_other_devices') }}</label>
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <a class="button is-link" @click="register()">{{ $t('auth.webauthn.register_a_new_device')}}</a>
                </div>
                <div class="control">
                    <router-link :to="{ name: 'login' }" class="button is-text">{{ $t('commons.cancel') }}</router-link>
                </div>
            </div>
        </div>
    </form-wrapper>
</template>

<script>

    import Form from './../../../components/Form'

    export default {
        data(){
            return {
                email : '',
                token: '',
                unique: false,
                deviceRegistered: false,
                deviceId : null,
                form: new Form({
                    name : '',
                }),
            }
        },

        created () {
            this.email = this.$route.query.email
            this.token = this.$route.query.token
        },

        methods : {

            /**
             * Register a new security device
             */
            async register() {
                // Check https context
                if (!window.isSecureContext) {
                    this.$notify({ type: 'is-danger', text: this.$t('errors.https_required') })
                    return false
                }

                // Check browser support
                if (!window.PublicKeyCredential) {
                    this.$notify({ type: 'is-danger', text: this.$t('errors.browser_does_not_support_webauthn') })
                    return false
                }

                const registerOptions = await this.axios.post('/webauthn/recover/options',
                    {
                        email : this.email,
                        token: this.token
                    },
                    { returnError: true })
                .then(res => res.data)
                .catch(error => {
                    this.$notify({ type: 'is-danger', text: error.response.data.message })
                });

                const publicKey = this.parseIncomingServerOptions(registerOptions)
                let bufferedCredentials

                try {
                    bufferedCredentials = await navigator.credentials.create({ publicKey })
                }
                catch (error) {
                    if (error.name == 'AbortError') {
                        this.$notify({ type: 'is-warning', text: this.$t('errors.aborted_by_user') })
                    }
                    else if (error.name == 'NotAllowedError' || 'InvalidStateError') {
                        this.$notify({ type: 'is-danger', text: this.$t('errors.security_device_unsupported') })
                    }
                    return false
                }

                const publicKeyCredential = this.parseOutgoingCredentials(bufferedCredentials);

                this.axios.post('/webauthn/recover', publicKeyCredential, {
                    headers: {
                        email : this.email,
                        token: this.token,
                        unique: this.unique,
                    }
                }).then(response => {
                    this.$notify({ type: 'is-success', text: this.$t('auth.webauthn.device_successfully_registered') })
                    this.deviceId = publicKeyCredential.id
                    this.deviceRegistered = true
                })
            },


            /**
             * Rename the registered device
             */
            async handleSubmit(e) {

                await this.form.patch('/webauthn/credentials/' + this.deviceId + '/name')

                if( this.form.errors.any() === false ) {
                    this.$router.push({name: 'accounts', params: { toRefresh: true }})
                }
            },
        },

        beforeRouteLeave (to, from, next) {
            this.$notify({
                clean: true
            })

            next()
        }
    }
</script>