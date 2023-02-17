<template>
    <div>
        <responsive-width-wrapper>
            <h1 class="title has-text-grey-dark">
                {{ $t('twofaccounts.import.import') }}
            </h1>
            <div v-if="exportedAccounts.length == 0">
                <div class="block is-size-7-mobile" v-html="$t('twofaccounts.import.import_legend')"></div>
                <h5 class="title is-5 mb-3 has-text-grey">{{ $t('twofaccounts.import.qr_code') }}</h5>
                <!-- scan button that launch camera stream -->
                <div class="block">
                    <div class="buttons mb-0">
                        <button tabindex="0" class="button is-link is-rounded mr-0" @click="capture()">
                            <span class="icon">
                                <font-awesome-icon :icon="['fas', 'camera']" />
                            </span>
                            <span>{{ $t('twofaccounts.import.scan') }}</span>
                        </button>
                        <span class="p-2 mb-2">{{ $t('commons.or') }}</span>
                        <!-- </div> -->
                        <!-- upload a qr code (with basic file field and backend decoding) -->
                        <!-- <div class="block"> -->
                        <label role="button" tabindex="0" class="button is-link is-rounded is-outlined" ref="qrcodeInputLabel"  @keyup.enter="$refs.qrcodeInputLabel.click()">
                            {{ $t('twofaccounts.import.upload') }}
                            <input aria-hidden="true" tabindex="-1" class="file-input" type="file" accept="image/*" v-on:change="submitQrCode" ref="qrcodeInput">
                        </label>
                    </div>
                    <field-error :form="uploadForm" field="qrcode" />
                    <p class="help">{{ $t('twofaccounts.import.supported_formats_for_qrcode_upload') }}</p>
                </div>
                <h5 class="title is-5 mb-3 has-text-grey">{{ $t('commons.file') }}</h5>
                <!-- upload a file -->
                <div class="block mb-6">
                    <label role="button" tabindex="0" class="button is-link is-rounded is-outlined" ref="fileInputLabel" @keyup.enter="$refs.fileInputLabel.click()">
                        <input aria-hidden="true" tabindex="-1" class="file-input" type="file" accept="text/plain,application/json,text/csv,.2fas" v-on:change="submitFile" ref="fileInput">
                        {{ $t('twofaccounts.import.upload') }}
                    </label>
                    <field-error :form="uploadForm" field="file" />
                    <p class="help">{{ $t('twofaccounts.import.supported_formats_for_file_upload') }}</p>
                </div>
                <!-- Supported migration resources -->
                <h5 class="title is-6 mb-3 has-text-grey-dark">{{ $t('twofaccounts.import.supported_migration_formats') }}</h5>
                <div class="field is-grouped is-grouped-multiline pt-0">
                    <div v-for="(source, index) in supportedSources" :key="index" class="control">
                        <div class="tags has-addons">
                        <span class="tag" :class="$root.showDarkMode ? 'is-dark' : 'is-white'">{{ source.app }}</span>
                        <span class="tag" :class="$root.showDarkMode ? 'is-black' : 'has-background-grey-lighter has-text-black'">{{ source.format }}</span>
                        </div>
                    </div>
                </div>
                <span class="is-size-7" v-html="$t('twofaccounts.import.do_not_set_password_or_encryption')"></span>
            </div>
            <div v-else>
                <div v-for="(account, index) in exportedAccounts" :key="account.name" class="group-item is-size-5 is-size-6-mobile">
                    <div class="is-flex is-justify-content-space-between">
                        <!-- Account name -->
                        <div v-if="account.id > -2 && account.imported !== 0" class="is-flex-grow-1 has-ellipsis is-clickable" @click="previewAccount(index)" :title="$t('twofaccounts.import.generate_a_test_password')">
                            <img v-if="account.icon && $root.userPreferences.showAccountsIcons" class="import-icon" :src="$root.appConfig.subdirectory + '/storage/icons/' + account.icon" :alt="$t('twofaccounts.icon_for_account_x_at_service_y', {account: account.account, service: account.service})">
                            {{ account.account }}
                        </div>
                        <div v-else class="is-flex-grow-1 has-ellipsis">{{ account.account }}</div>
                        <!-- buttons -->
                        <div v-if="account.imported === -1" class="tags is-flex-wrap-nowrap">
                            <!-- discard button -->
                            <button class="button tag" :class="{'is-dark has-text-grey-light' : $root.showDarkMode}" @click="discardAccount(index)"  :title="$t('twofaccounts.import.discard_this_account')">
                                <font-awesome-icon :icon="['fas', 'trash']" />
                            </button>
                            <!-- import button -->
                            <button v-if="account.id > -2" class="button tag is-link" @click="createAccount(index)"  :title="$t('twofaccounts.import.import_this_account')">
                                {{ $t('twofaccounts.import.to_import') }}
                            </button>
                        </div>
                        <!-- result label -->
                        <div v-else class="has-nowrap">
                            <span v-if="account.imported === 1" class="has-text-success">
                                {{ $t('twofaccounts.import.imported') }} <font-awesome-icon :icon="['fas', 'check']" />
                            </span>
                            <span v-else class="has-text-danger">
                                {{ $t('twofaccounts.import.failure') }} <font-awesome-icon :icon="['fas', 'times']" />
                            </span>
                        </div>
                    </div>
                    <div class="is-size-6 is-size-7-mobile">
                        <!-- service name -->
                        <div class="is-family-primary has-text-grey">{{ $t('twofaccounts.import.issuer') }}: {{ account.service }}</div>
                        <!-- reasons to invalid G-Auth data -->
                        <div v-if="account.id === -2" class="has-text-danger">
                            <font-awesome-icon class="mr-1" :icon="['fas', 'times-circle']" />{{ account.secret }}
                        </div>
                        <!-- possible duplicates -->
                        <div v-if="account.id === -1 && account.imported !== 1 && !account.errors" class="has-text-warning">
                            <font-awesome-icon class="mr-1" :icon="['fas', 'exclamation-circle']" />{{ $t('twofaccounts.import.possible_duplicate') }}
                        </div>
                        <!-- errors during account creation -->
                        <ul v-if="account.errors">
                            <li v-for="(error) in account.errors" :key="error" class="has-text-danger">{{ error }}</li>
                        </ul>
                    </div>
                </div>
                <!-- discard links -->
                <div v-if="importableCount > 0" class="mt-2 is-size-7 is-pulled-right">
                    <button v-if="duplicateCount" @click="discardDuplicates()" class="has-text-grey button is-small is-ghost">{{ $t('twofaccounts.import.discard_duplicates') }} ({{duplicateCount}})</button>
                    <button @click="discardAccounts()" class="has-text-grey button is-small is-ghost">{{ $t('twofaccounts.import.discard_all') }}</button>
                </div>
                <div v-if="importedCount == exportedAccounts.length"  class="mt-2 is-size-7 is-pulled-right">
                    <button @click="exportedAccounts = []" class="has-text-grey button is-small is-ghost">{{ $t('commons.clear') }}</button>
                </div>
            </div>
            <div v-if="isFetching && exportedAccounts.length === 0" class="has-text-centered">
                <span class="is-size-4">
                    <font-awesome-icon :icon="['fas', 'spinner']" spin />
                </span>
            </div>
            <!-- footer -->
            <vue-footer :showButtons="true">
                <!-- Import all button -->
                <p class="control" v-if="importableCount > 0">
                    <button class="button is-link is-rounded is-focus" @click="createAccounts">
                        <span>{{ $t('twofaccounts.import.import_all') }} ({{importableCount}})</span>
                        <!-- <span class="icon is-small">
                            <font-awesome-icon :icon="['fas', 'qrcode']" />
                        </span> -->
                    </button>
                </p>
                <!-- close button -->
                <p class="control">
                    <router-link  :to="{ name: 'accounts', params: { toRefresh: true } }" class="button is-rounded" :class="{'is-dark' : $root.showDarkMode}" v-html="importableCount > 0 ? $t('commons.cancel') : $t('commons.close')"></router-link>
                </p>
            </vue-footer>
        </responsive-width-wrapper>
        <!-- modal -->
        <modal v-model="ShowTwofaccountInModal">
            <otp-displayer ref="AdvancedFormOtpDisplayer" v-bind="form.data()">
            </otp-displayer>
        </modal>
    </div>
</template>

<script>
    import Modal from '../../components/Modal'
    import Form from './../../components/Form'
    import OtpDisplayer from '../../components/OtpDisplayer'

    export default {
        data() {
            return {
                migrationPayload: '',
                exportedAccounts: [],
                isFetching: false,
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
                }),
                uploadForm: new Form(),
                ShowTwofaccountInModal : false,
                supportedSources: [
                    {app: '2FAuth', format: 'JSON'},
                    {app: 'Google Auth', format: this.$t('twofaccounts.import.qr_code')},
                    {app: 'Aegis Auth', format: 'JSON'},
                    {app: 'Aegis Auth', format: this.$t('twofaccounts.import.plain_text')},
                    {app: '2FAS auth', format: 'JSON'},
                ]
            }
        },

        computed: {
            importableCount() {
                return this.exportedAccounts.filter(account => account.imported == -1 && account.id > -2).length;
            },

            duplicateCount() {
                return this.exportedAccounts.filter(account => account.id === -1 && account.imported === -1).length;
            },

            importedCount() {
                return this.exportedAccounts.filter(account => account.imported === 1).length;
            }
        },

        mounted: async function() {
            // A migration URI is provided as route parameter, we extract the accounts from the URI and
            // list them in the view
            if( this.$route.params.migrationUri ) {
                this.migrate(this.$route.params.migrationUri)
            }

            this.$on('modalClose', function() {
                this.$refs.AdvancedFormOtpDisplayer.clearOTP()
            });
        },

        components: {
            Modal,
            OtpDisplayer,
        },

        methods: {

            /**
             * Post the migration payload
             */
            async migrate(migrationPayload) {
                this.migrationPayload = migrationPayload
                this.isFetching = true

                await this.axios.post('/api/v1/twofaccounts/migration', {payload: this.migrationPayload, withSecret: true}, {returnError: true}).then(response => {
                    response.data.forEach((data) => {
                        data.imported = -1;
                        this.exportedAccounts.push(data)
                    })

                    this.notifyValidAccountFound()
                })
                .catch(error => {
                    this.$notify({type: 'is-danger', text: this.$t(error.response.data.message) })
                });

                this.isFetching = false
            },

            /**
             * Remove all duplicates from the accounts list
             */
            discardDuplicates() {
                if(confirm(this.$t('twofaccounts.confirm.discard_duplicates'))) {
                    this.$notify({ clean: true })
                    this.$refs.AdvancedFormOtpDisplayer.clearOTP()
                    // console.log(this.exportedAccounts.filter(account => account.id >= 0 && account.imported > -1))
                    this.exportedAccounts = this.exportedAccounts.filter(account => account.id !== -1)
                }
            },

            /**
             * Clear the accounts list
             */
            discardAccounts() {
                if(confirm(this.$t('twofaccounts.confirm.discard_all'))) {
                    this.$notify({ clean: true })
                    this.$refs.AdvancedFormOtpDisplayer.clearOTP()
                    this.exportedAccounts = []
                }
            },

            /**
             * Remove one duplicate from the accounts list
             */
            discardAccount(accountIndex) {
                if(confirm(this.$t('twofaccounts.confirm.discard'))) {
                    this.exportedAccounts.splice(accountIndex, 1)
                }
            },

            /**
             * Batch store valid accounts, even duplicates
             */
            async createAccounts() {
                for (let index = 0; index < this.exportedAccounts.length; index++) {
                    if (this.exportedAccounts[index].imported == -1) {
                        await this.createAccount(index)
                    }
                }
            },

            /**
             * Store the provided account
             */
            async createAccount(accountIndex) {

                let twofaccount = this.exportedAccounts[accountIndex]
                this.mapAccountToForm(twofaccount)

                await this.form.post('/api/v1/twofaccounts', {returnError: true})
                    .then(response => {
                        this.exportedAccounts[accountIndex].imported = 1
                        this.exportedAccounts[accountIndex].id = response.data.id
                    })
                    .catch(error => {
                        this.exportedAccounts[accountIndex].imported = 0
                        this.exportedAccounts[accountIndex].id = 0
                        this.exportedAccounts[accountIndex].errors = this.form.errors.flatten()
                    });

            },

            /**
             * Generate a fresh OTP password and display it
             */
            previewAccount(accountIndex) {
                this.mapAccountToForm(this.exportedAccounts[accountIndex])
                    .then(() => {
                        this.$refs.AdvancedFormOtpDisplayer.$forceUpdate()
                        this.$refs.AdvancedFormOtpDisplayer.show()
                    })

            },

            /**
             * Map account field with the Form object
             */
            async mapAccountToForm(twofaccount) {
                this.form.account = twofaccount.account
                this.form.service = twofaccount.service
                this.form.otp_type = twofaccount.otp_type
                this.form.secret = twofaccount.secret
                this.form.algorithm = twofaccount.algorithm
                this.form.digits = twofaccount.digits
                this.form.counter = twofaccount.otp_type === 'hotp' ? twofaccount.counter : null
                this.form.period = twofaccount.otp_type === 'totp' ? twofaccount.period : null
            },

            /**
             * Upload the submitted file to the backend for parsing
             */
            submitFile() {
                this.isFetching = true

                let filedata = new FormData();
                filedata.append('file', this.$refs.fileInput.files[0]);
                filedata.append('withSecret', true);

                this.uploadForm.upload('/api/v1/twofaccounts/migration', filedata, {returnError: true}).then(response => {
                    response.data.forEach((data) => {
                        data.imported = -1;
                        this.exportedAccounts.push(data)
                    })

                    this.notifyValidAccountFound()
                })
                .catch(error => {
                    if( error.response.status === 422 ) {
                        if (error.response.data.errors.file == undefined) {
                            this.$notify({type: 'is-danger', text: this.$t('errors.invalid_2fa_data') })
                        }
                    }
                    else this.$notify({type: 'is-danger', text: error.response.data.message})
                });
                
                this.isFetching = false
            },

            /**
             * Upload the submitted QR code file to the backend for decoding
             */
            submitQrCode() {

                let imgdata = new FormData();
                imgdata.append('qrcode', this.$refs.qrcodeInput.files[0]);
                imgdata.append('withSecret', true);

                this.uploadForm.upload('/api/v1/qrcode/decode', imgdata, {returnError: true}).then(response => {
                    this.migrate(response.data.data)
                })
                .catch(error => {
                    if( error.response.status === 422 ) {
                        if (error.response.data.errors.qrcode == undefined) {
                            this.$notify({type: 'is-danger', text: this.$t('errors.invalid_2fa_data') })
                        }
                    }
                    else this.$notify({type: 'is-danger', text: error.response.data.message})

                    // if( error.response.status !== 422 ) {
                    //     this.$notify({type: 'is-danger', text: this.$t(error.response.data.message) })
                    // }
                });
            },


            /**
             * Push user to the dedicated capture view for live scan
             */
            capture() {
                this.$router.push({ name: 'capture' });
            },

            /**
             * Notify that valid account(s) have been found for import
             */
            notifyValidAccountFound() {
                this.$notify({type: 'is-success', text: this.$t('twofaccounts.import.x_valid_accounts_found', { count: this.importableCount }) })
            }
        }
    }

</script>