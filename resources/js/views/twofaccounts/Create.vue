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
                        <otp-displayer ref="QuickFormOtpDisplayer" v-bind="form.data()" @increment-hotp="incrementHotp">
                        </otp-displayer>
                    </div>
                </div>
                <div class="columns is-mobile" role="alert">
                    <div v-if="form.errors.any()" class="column">
                        <p v-for="(field, index) in form.errors.errors" :key="index" class="help is-danger">
                            <ul>
                                <li v-for="(error, index) in field" :key="index">{{ error }}</li>
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
                <div class="field is-grouped">
                    <div class="control">
                        <div role="button" tabindex="0" class="file is-black is-small" @keyup.enter="$refs.qrcodeInputLabel.click()">
                            <label class="file-label" :title="$t('twofaccounts.forms.use_qrcode.title')" ref="qrcodeInputLabel">
                                <input aria-hidden="true" tabindex="-1" class="file-input" type="file" accept="image/*" v-on:change="uploadQrcode" ref="qrcodeInput">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <font-awesome-icon :icon="['fas', 'qrcode']" size="lg" />
                                    </span>
                                    <span class="file-label">{{ $t('twofaccounts.forms.prefill_using_qrcode') }}</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <field-error :form="form" field="qrcode" class="help-for-file" />
                <!-- service -->
                <form-field :form="form" :isDisabled="form.otp_type === 'steamtotp'" fieldName="service" inputType="text" :label="$t('twofaccounts.service')" :placeholder="$t('twofaccounts.forms.service.placeholder')" autofocus />
                <!-- account -->
                <form-field :form="form" fieldName="account" inputType="text" :label="$t('twofaccounts.account')" :placeholder="$t('twofaccounts.forms.account.placeholder')" />
                <!-- icon upload -->
                <label class="label">{{ $t('twofaccounts.icon') }}</label>
                <div class="field is-grouped">
                    <!-- i'm lucky button -->
                    <div class="control" v-if="$root.userPreferences.getOfficialIcons">
                        <v-button @click="fetchLogo" :color="$root.showDarkMode ? 'is-dark' : ''" :nativeType="'button'" :isDisabled="form.service.length < 1">
                            <span class="icon is-small">
                                <font-awesome-icon :icon="['fas', 'globe']" />
                            </span>
                            <span>{{ $t('twofaccounts.forms.i_m_lucky') }}</span>
                        </v-button>
                    </div>
                    <!-- upload button -->
                    <div class="control">
                        <div role="button" tabindex="0" class="file" :class="$root.showDarkMode ? 'is-dark' : 'is-white'" @keyup.enter="$refs.iconInputLabel.click()">
                            <label class="file-label" ref="iconInputLabel">
                                <input aria-hidden="true" tabindex="-1" class="file-input" type="file" accept="image/*" v-on:change="uploadIcon" ref="iconInput">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <font-awesome-icon :icon="['fas', 'upload']" />
                                    </span>
                                    <span class="file-label">{{ $t('twofaccounts.forms.choose_image') }}</span>
                                </span>
                            </label>
                            <span class="tag is-large" :class="$root.showDarkMode ? 'is-dark' : 'is-white'" v-if="tempIcon">
                                <img class="icon-preview" :src="$root.appConfig.subdirectory + '/storage/icons/' + tempIcon" :alt="$t('twofaccounts.icon_to_illustrate_the_account')">
                                <button class="clear-selection delete is-small" @click.prevent="deleteIcon" :aria-label="$t('twofaccounts.remove_icon')"></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <field-error :form="form" field="icon" class="help-for-file" />
                    <p v-if="$root.userPreferences.getOfficialIcons" class="help" v-html="$t('twofaccounts.forms.i_m_lucky_legend')"></p>
                </div>
                <!-- otp type -->
                <form-toggle class="has-uppercased-button" :form="form" :choices="otp_types" fieldName="otp_type" :label="$t('twofaccounts.forms.otp_type.label')" :help="$t('twofaccounts.forms.otp_type.help')" :hasOffset="true" />
                <div v-if="form.otp_type">
                    <!-- secret -->
                    <label :for="this.inputId('text','secret')" class="label" v-html="$t('twofaccounts.forms.secret.label')"></label>
                    <div class="field">
                        <p class="control is-expanded">
                            <input :id="this.inputId('text','secret')" class="input" type="text" v-model="form.secret">
                        </p>
                    </div>
                    <div class="field">
                        <field-error :form="form" field="secret" />
                        <p class="help" v-html="$t('twofaccounts.forms.secret.help')"></p>
                    </div>
                    <div v-if="form.otp_type !== 'steamtotp'">
                        <h2 class="title is-4 mt-5 mb-2">{{ $t('commons.options') }}</h2>
                        <p class="help mb-4">
                            {{ $t('twofaccounts.forms.options_help') }}
                        </p>
                        <!-- digits -->
                        <form-toggle :form="form" :choices="digitsChoices" fieldName="digits" :label="$t('twofaccounts.forms.digits.label')" :help="$t('twofaccounts.forms.digits.help')" />
                        <!-- algorithm -->
                        <form-toggle :form="form" :choices="algorithms" fieldName="algorithm" :label="$t('twofaccounts.forms.algorithm.label')" :help="$t('twofaccounts.forms.algorithm.help')" />
                        <!-- TOTP period -->
                        <form-field v-if="form.otp_type === 'totp'" pattern="[0-9]{1,4}" :class="'is-third-width-field'" :form="form" fieldName="period" inputType="text" :label="$t('twofaccounts.forms.period.label')" :placeholder="$t('twofaccounts.forms.period.placeholder')" :help="$t('twofaccounts.forms.period.help')" />
                        <!-- HOTP counter -->
                        <form-field v-if="form.otp_type === 'hotp'" pattern="[0-9]{1,4}" :class="'is-third-width-field'" :form="form" fieldName="counter" inputType="text" :label="$t('twofaccounts.forms.counter.label')" :placeholder="$t('twofaccounts.forms.counter.placeholder')" :help="$t('twofaccounts.forms.counter.help')" />
                    </div>
                </div>
                <vue-footer :showButtons="true">
                    <p class="control">
                        <v-button :isLoading="form.isBusy" class="is-rounded" >{{ $t('commons.create') }}</v-button>
                    </p>
                    <p class="control" v-if="form.otp_type && form.secret">
                        <button type="button" class="button is-success is-rounded" @click="previewAccount">{{ $t('twofaccounts.forms.test') }}</button>
                    </p>
                    <p class="control">
                        <button type="button" class="button is-text is-rounded" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
                    </p>
                </vue-footer>
            </form>
            <!-- modal -->
            <modal v-model="ShowTwofaccountInModal">
                <otp-displayer ref="AdvancedFormOtpDisplayer" v-bind="form.data()" @increment-hotp="incrementHotp" @validation-error="mapDisplayerErrors">
                </otp-displayer>
            </modal>
        </form-wrapper>
        <!-- alternatives -->
        <modal v-model="showAlternatives">
            <div class="too-bad"></div>
            <div class="block">
                {{ $t('errors.data_of_qrcode_is_not_valid_URI') }}
            </div>
            <div class="block mb-6" :class="$root.showDarkMode ? 'has-text-light':'has-text-grey-dark'" v-html="uri"></div>
            <!-- Copy to clipboard -->
            <div class="block has-text-link">
                <button class="button is-link is-outlined is-rounded" v-clipboard="() => uri" v-clipboard:success="clipboardSuccessHandler">
                    {{ $t('commons.copy_to_clipboard') }}
                </button>
            </div>
            <!-- Open in browser -->
            <div class="block has-text-link" v-if="isUrl(uri)" @click="openInBrowser(uri)">
                <button class="button is-link is-outlined is-rounded">
                    <span>{{ $t('commons.open_in_browser') }}</span>
                    <span class="icon is-small">
                        <font-awesome-icon :icon="['fas', 'external-link-alt']" />
                    </span>
                </button>
            </div>
        </modal>
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
     *  Both design use the otpDisplayer component to preview the account with an otp rotation.
     *
     *  input : [optional, for the Quick Form] an URI previously decoded by the Start view
     *  submit : post account data to php backend to create the account
     */

    import Modal from '../../components/Modal'
    import Form from './../../components/Form'
    import OtpDisplayer from '../../components/OtpDisplayer'

    export default {
        data() {
            return {
                showQuickForm: false,
                showAdvancedForm: false,
                ShowTwofaccountInModal : false,
                showAlternatives : false,
                tempIcon: '',
                uri: '',
                form: new Form({
                    service: '',
                    account: '',
                    otp_type: '',
                    icon: '',
                    secret: '',
                    algorithm: '',
                    digits: null,
                    counter: null,
                    period: null,
                    image: '',
                    qrcode: null,
                }),
                otp_types: [
                    { text: 'TOTP', value: 'totp' },
                    { text: 'HOTP', value: 'hotp' },
                    { text: 'STEAM', value: 'steamtotp' },
                ],
                digitsChoices: [
                    { text: 6, value: 6 },
                    { text: 7, value: 7 },
                    { text: 8, value: 8 },
                    { text: 9, value: 9 },
                    { text: 10, value: 10 },
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
                    this.$refs.QuickFormOtpDisplayer.internal_icon = val
                }
            },

            'form.otp_type' : function(to, from) {
                this.setFormState(from, to) 
            },
        },

        mounted: function () {
            if( this.$route.params.decodedUri ) {
                this.uri = this.$route.params.decodedUri

                // the Start view provided an uri so we parse it and prefill the quick form
                this.axios.post('/api/v1/twofaccounts/preview', { uri: this.uri }).then(response => {

                    this.form.fill(response.data)
                    this.tempIcon = response.data.icon ? response.data.icon : null
                    this.showQuickForm = true
                })
                .catch(error => {
                    if( error.response.status === 422 ) {
                        if( error.response.data.errors.uri ) {
                            this.showAlternatives = true
                            this.showAdvancedForm = true
                        }
                    }
                });
            } else {
                this.showAdvancedForm = true
            }

            this.$on('modalClose', function() {
                this.showAlternatives = false;

                if( this.showAdvancedForm ) {
                    this.$refs.AdvancedFormOtpDisplayer.stopLoop()
                }
            });
        },

        components: {
            Modal,
            OtpDisplayer,
        },

        methods: {

            async createAccount() {
                // set current temp icon as account icon
                this.form.icon = this.tempIcon

                await this.form.post('/api/v1/twofaccounts')

                if( this.form.errors.any() === false ) {
                    this.$notify({ type: 'is-success', text: this.$t('twofaccounts.account_created') })
                    this.$router.push({name: 'accounts', params: { toRefresh: true }});
                }

            },

            previewAccount() {
                this.form.clear()
                this.$refs.AdvancedFormOtpDisplayer.show()
            },

            cancelCreation: function() {

                if( this.form.service ) {
                    if( confirm(this.$t('twofaccounts.confirm.cancel')) === false ) {
                        return
                    }
                }

                // clean possible uploaded temp icon
                this.deleteIcon()

                this.$router.push({name: 'accounts'});
            },

            uploadQrcode(event) {

                let imgdata = new FormData();
                imgdata.append('qrcode', this.$refs.qrcodeInput.files[0]);
                imgdata.append('inputFormat', 'fileUpload');

                // First we get the uri encoded in the qrcode
                this.form.upload('/api/v1/qrcode/decode', imgdata, {returnError: true}).then(response => {
                    this.uri = response.data.data
                    
                    // Then the otp described by the uri
                    this.axios.post('/api/v1/twofaccounts/preview', { uri: this.uri }).then(response => {
                        this.form.fill(response.data)
                        this.tempIcon = response.data.icon ? response.data.icon : null
                    })
                    .catch(error => {
                        if( error.response.status === 422 ) {
                            if( error.response.data.errors.uri ) {
                                this.showAlternatives = true
                            }
                        }
                    });
                })
                .catch(error => {
                    this.$notify({type: 'is-danger', text: this.$t(error.response.data.message) })
                    return false
                });

            },

            uploadIcon(event) {

                // clean possible already uploaded temp icon
                this.deleteIcon()

                let imgdata = new FormData();
                imgdata.append('icon', this.$refs.iconInput.files[0]);

                this.form.upload('/api/v1/icons', imgdata, {returnError: true}).then(response => {
                    this.tempIcon = response.data.filename;
                })
                .catch(error => {
                    this.$notify({type: 'is-danger', text: this.$t(error.response.data.message) })
                });
            },

            fetchLogo() {
                if (this.$root.userPreferences.getOfficialIcons) {
                    this.axios.post('/api/v1/icons/default', {service: this.form.service}, {returnError: true}).then(response => {
                        if (response.status === 201) {
                            // clean possible already uploaded temp icon
                            this.deleteIcon()
                            this.tempIcon = response.data.filename;
                        }
                        else this.$notify({type: 'is-warning', text: this.$t('errors.no_logo_found_for_x', {service: this.form.service}) })
                    })
                    .catch(error => {
                        this.$notify({type: 'is-warning', text: this.$t('errors.no_logo_found_for_x', {service: this.form.service}) })
                    });
                }
            },

            deleteIcon(event) {
                if(this.tempIcon) {
                    this.axios.delete('/api/v1/icons/' + this.tempIcon)
                    this.tempIcon = ''
                }
            },

            incrementHotp(payload) {
                // The quick form or the preview feature has incremented the HOTP counter so we get the new value from
                // the component.
                // This could desynchronized the HOTP verification server and our local counter if the user never verified the HOTP but this
                // is acceptable (and HOTP counter can be edited by the way)
                this.form.counter = payload.nextHotpCounter
            },

            clipboardSuccessHandler ({ value, event }) {

                if(this.$root.appSettings.userPreferences == -1) {
                    this.appLogout()
                }

                this.$notify({ type: 'is-success', text: this.$t('commons.copied_to_clipboard') })
            },

            clipboardErrorHandler ({ value, event }) {
                console.log('error', value)
            },

            setFormState (from, to) {
                this.form.otp_type = to

                if (to === 'steamtotp') {
                    this.form.service = 'Steam'
                    this.fetchLogo()
                }
                else if (from === 'steamtotp') {
                    this.form.service = ''
                    this.deleteIcon()
                }
            },

            mapDisplayerErrors (event) {
                this.form.errors.set(this.form.extractErrors(event))
            }
            
        },

    }
</script>