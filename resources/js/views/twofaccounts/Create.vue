<template>
    <div>
        <!-- Quick form -->
        <form @submit.prevent="createAccount" @keydown="form.onKeydown($event)" v-if="showQuickForm">
            <div class="container preview has-text-centered">
                <div class="columns is-mobile">
                    <div class="column">
                        <label class="add-icon-button" v-if="!tempIcon">
                            <input class="file-input" type="file" accept="image/*" v-on:change="uploadIcon" ref="iconInput">
                            <font-awesome-icon :icon="['fas', 'image']" size="2x" />
                        </label>
                        <button class="delete delete-icon-button is-medium" v-if="tempIcon" @click.prevent="deleteIcon"></button>
                        <token-displayer ref="QuickFormTokenDisplayer" v-bind="form.data()" @increment-hotp="incrementHotp">
                        </token-displayer>
                    </div>
                </div>
                <div class="columns is-mobile" v-if="form.errors.any()">
                    <div class="column">
                        <p v-for="field in form.errors.errors" class="help is-danger">
                            <ul>
                                <li v-for="(error, index) in field">{{ error }}</li>
                            </ul>
                        </p>
                    </div>
                </div>
                <div class="columns is-mobile">
                    <div class="column quickform-footer">
                        <div class="field is-grouped is-grouped-centered">
                            <div class="control">
                                <v-button :isLoading="form.isBusy" >{{ $t('commons.save') }}</v-button>
                            </div>
                            <div class="control">
                                <button type="button" class="button is-text" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- Full form -->
        <form-wrapper :title="$t('twofaccounts.forms.new_account')" v-if="showAdvancedForm">
            <form @submit.prevent="createAccount" @keydown="form.onKeydown($event)">
                <!-- qcode fileupload -->
                <div class="field">
                    <div class="file is-black is-small">
                        <label class="file-label" :title="$t('twofaccounts.forms.use_qrcode.title')">
                            <input class="file-input" type="file" accept="image/*" v-on:change="uploadQrcode" ref="qrcodeInput">
                            <span class="file-cta">
                                <span class="file-icon">
                                    <font-awesome-icon :icon="['fas', 'qrcode']" size="lg" />
                                </span>
                                <span class="file-label">{{ $t('twofaccounts.forms.prefill_using_qrcode') }}</span>
                            </span>
                        </label>
                    </div>
                </div>
                <field-error :form="form" field="qrcode" class="help-for-file" />
                <!-- service -->
                <form-field :form="form" fieldName="service" inputType="text" :label="$t('twofaccounts.service')" :placeholder="$t('twofaccounts.forms.service.placeholder')" autofocus />
                <!-- account -->
                <form-field :form="form" fieldName="account" inputType="text" :label="$t('twofaccounts.account')" :placeholder="$t('twofaccounts.forms.account.placeholder')" />
                <!-- icon upload -->
                <div class="field">
                    <label class="label">{{ $t('twofaccounts.icon') }}</label>
                    <div class="file is-dark">
                        <label class="file-label">
                            <input class="file-input" type="file" accept="image/*" v-on:change="uploadIcon" ref="iconInput">
                            <span class="file-cta">
                                <span class="file-icon">
                                    <font-awesome-icon :icon="['fas', 'image']" />
                                </span>
                                <span class="file-label">{{ $t('twofaccounts.forms.choose_image') }}</span>
                            </span>
                        </label>
                        <span class="tag is-black is-large" v-if="tempIcon">
                            <img class="icon-preview" :src="'/storage/icons/' + tempIcon" >
                            <button class="delete is-small" @click.prevent="deleteIcon"></button>
                        </span>
                    </div>
                </div>
                <field-error :form="form" field="icon" class="help-for-file" />
                <!-- otp type -->
                <form-toggle class="has-uppercased-button" :form="form" :choices="otpTypes" fieldName="otpType" :label="$t('twofaccounts.forms.otp_type.label')" :help="$t('twofaccounts.forms.otp_type.help')" :hasOffset="true" />
                <div v-if="form.otpType">
                    <!-- secret -->
                    <label class="label" v-html="$t('twofaccounts.forms.secret.label')"></label>
                    <div class="field has-addons">
                        <p class="control">
                            <span class="select">
                                <select v-model="form.secretIsBase32Encoded">
                                    <option v-for="format in secretFormats" :value="format.value">{{ format.text }}</option>
                                </select>
                            </span>
                        </p>
                        <p class="control is-expanded">
                            <input class="input" type="text" v-model="form.secret">
                        </p>
                    </div>
                    <div class="field">
                        <field-error :form="form" field="secret" class="help-for-file" />
                        <p class="help" v-html="$t('twofaccounts.forms.secret.help')"></p>
                    </div>
                    <h2 class="title is-4 mt-5 mb-2">{{ $t('commons.options') }}</h2>
                    <p class="help mb-4">
                        {{ $t('twofaccounts.forms.options_help') }}
                    </p>
                    <!-- digits -->
                    <form-toggle :form="form" :choices="digitsChoices" fieldName="digits" :label="$t('twofaccounts.forms.digits.label')" :help="$t('twofaccounts.forms.digits.help')" />
                    <!-- algorithm -->
                    <form-toggle :form="form" :choices="algorithms" fieldName="algorithm" :label="$t('twofaccounts.forms.algorithm.label')" :help="$t('twofaccounts.forms.algorithm.help')" />
                    <!-- TOTP period -->
                    <form-field v-if="form.otpType === 'totp'" :form="form" fieldName="totpPeriod" inputType="text" :label="$t('twofaccounts.forms.totpPeriod.label')" :placeholder="$t('twofaccounts.forms.totpPeriod.placeholder')" :help="$t('twofaccounts.forms.totpPeriod.help')" />
                    <!-- HOTP counter -->
                    <form-field v-if="form.otpType === 'hotp'" :form="form" fieldName="hotpCounter" inputType="text" :label="$t('twofaccounts.forms.hotpCounter.label')" :placeholder="$t('twofaccounts.forms.hotpCounter.placeholder')" :help="$t('twofaccounts.forms.hotpCounter.help')" />
                </div>
                <vue-footer :showButtons="true">
                    <p class="control">
                        <v-button :isLoading="form.isBusy" class="is-rounded" >{{ $t('commons.create') }}</v-button>
                    </p>
                    <p class="control" v-if="form.otpType && form.secret">
                        <button type="button" class="button is-success is-rounded" @click="previewAccount">{{ $t('twofaccounts.forms.test') }}</button>
                    </p>
                    <p class="control">
                        <button type="button" class="button is-text is-rounded" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
                    </p>
                </vue-footer>
            </form>
            <!-- modal -->
            <modal v-model="ShowTwofaccountInModal">
                <token-displayer ref="AdvancedFormTokenDisplayer" v-bind="form.data()" @increment-hotp="incrementHotp">
                </token-displayer>
            </modal>
        </form-wrapper>
    </div>
</template>

<script>

    /**
     *  Create form view
     *  
     *  route: '/account/create'
     *  
     *  Offer the user to define, preview and store an account. The form has 2 designs :
     *  - The 'Quick Form', a read only design fed with $route.params.decodedUri passed by the Start view.
     *  - The 'Advanced Form', a fully editable form, that let user define all field and OTP parameters.
     *    ~ A qrcode can be used to automatically fill the form
     *    ~ If an 'image' parameter is embeded in the qrcode, the remote image is downloaded and preset in the icon field
     *
     *  Both design use the tokenDisplayer component to preview the account with a token rotation.
     *
     *  input : [optional, for the Quick Form] an URI previously decoded by the Start view
     *  submit : post account data to php backend to create the account
     */

    import Modal from '../../components/Modal'
    import Form from './../../components/Form'
    import TokenDisplayer from '../../components/TokenDisplayer'

    export default {
        data() {
            return {
                showQuickForm: false,
                showAdvancedForm: false,
                ShowTwofaccountInModal : false,
                tempIcon: '',
                form: new Form({
                    service: '',
                    account: '',
                    otpType: '',
                    uri: '',
                    icon: '',
                    secret: '',
                    secretIsBase32Encoded: 0,
                    algorithm: '',
                    digits: null,
                    hotpCounter: null,
                    totpPeriod: null,
                    imageLink: '',
                    qrcode: null,
                }),
                otpTypes: [
                    { text: 'TOTP', value: 'totp' },
                    { text: 'HOTP', value: 'hotp' },
                ],
                digitsChoices: [
                    { text: 6, value: 6 },
                    { text: 7, value: 7 },
                    { text: 8, value: 8 },
                    { text: 9, value: 9 },
                    { text: 10, value: 10 },
                ],
                secretFormats: [
                    { text: this.$t('twofaccounts.forms.plain_text'), value: 0 },
                    { text: 'Base32', value: 1 }
                ],
                algorithms: [
                    { text: 'sha1', value: 'sha1' },
                    { text: 'sha256', value: 'sha256' },
                    { text: 'sha512', value: 'sha512' },
                    { text: 'md5', value: 'md5' },
                ],
            }
        },

        watch: {
            tempIcon: function(val) {
                if( this.showQuickForm ) {
                    this.$refs.QuickFormTokenDisplayer.internal_icon = val
                }
            },
        },

        mounted: function () {
            if( this.$route.params.decodedUri ) {

                // the Start view provided an uri so we parse it and prefill the quick form
                this.axios.post('/api/twofaccounts/preview', { uri: this.$route.params.decodedUri }).then(response => {

                    this.form.fill(response.data)
                    this.tempIcon = response.data.icon ? response.data.icon : null
                    this.showQuickForm = true
                })
                .catch(error => {
                    if( error.response.status === 422 ) {

                        this.$router.push({ name: 'genericError', params: { err: this.$t('errors.cannot_create_otp_with_those_parameters') } });
                    }
                });

            } else {
                this.showAdvancedForm = true
            }

            // stop TOTP generation on modal close
            this.$on('modalClose', function() {

                this.$refs.AdvancedFormTokenDisplayer.stopLoop()
            });
        },

        components: {
            Modal,
            TokenDisplayer,
        },

        methods: {

            async createAccount() {
                // set current temp icon as account icon
                this.form.icon = this.tempIcon

                await this.form.post('/api/twofaccounts')

                if( this.form.errors.any() === false ) {
                    this.$router.push({name: 'accounts', params: { toRefresh: true }});
                }

            },

            previewAccount() {
                this.$refs.AdvancedFormTokenDisplayer.getToken()
            },

            cancelCreation: function() {

                if( this.form.service && this.form.uri ) {
                    if( confirm(this.$t('twofaccounts.confirm.cancel')) === false ) {
                        return
                    }
                }

                // clean possible uploaded temp icon
                this.deleteIcon()

                this.$router.push({name: 'accounts'});
            },

            async uploadQrcode(event) {

                let imgdata = new FormData();
                imgdata.append('qrcode', this.$refs.qrcodeInput.files[0]);
                imgdata.append('inputFormat', 'fileUpload');

                // First we get the uri encoded in the qrcode
                const { data } = await this.form.upload('/api/qrcode/decode', imgdata)

                // Then the otp described by the uri
                this.axios.post('/api/twofaccounts/preview', { uri: data.uri }).then(response => {
                    this.form.fill(response.data)
                    this.form.secretIsBase32Encoded = 1
                    this.tempIcon = response.data.icon ? response.data.icon : null
                    this.form.uri = '' // we don't want the uri because the user can change any otp parameter in the form
                })
            },

            async uploadIcon(event) {

                // clean possible already uploaded temp icon
                this.deleteIcon()

                let imgdata = new FormData();
                imgdata.append('icon', this.$refs.iconInput.files[0]);

                const { data } = await this.form.upload('/api/icon/upload', imgdata)

                this.tempIcon = data;

            },

            deleteIcon(event) {
                if(this.tempIcon) {
                    this.axios.delete('/api/icon/delete/' + this.tempIcon)
                    this.tempIcon = ''
                }
            },

            incrementHotp(payload) {
                // The quick form or the preview feature has incremented the HOTP counter so we get the new value from
                // the component.
                // This could desynchronized the HOTP verification server and our local counter if the user never verified the HOTP but this
                // is acceptable (and HOTP counter can be edited by the way)
                this.form.hotpCounter = payload.nextHotpCounter
                this.form.uri = payload.nextUri
            },
            
        },

    }
</script>