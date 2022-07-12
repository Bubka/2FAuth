<template>
    <div>
        <div class="columns is-centered">
            <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
                <h1 class="title">
                    {{ $t('twofaccounts.import.import') }}
                </h1>
                <div class="is-size-7-mobile" v-html="$t('twofaccounts.import.import_legend')">
                </div>
                <div class="mt-3 mb-6">
                    <router-link class="is-link" :to="{ name: 'start', params: {showAdvancedFormButton: false, returnToView: 'importAccounts'} }">
                        <span class="tag is-black">
                            <font-awesome-icon :icon="['fas', 'qrcode']" size="lg" class="mr-1" />{{ $t('twofaccounts.import.use_the_gauth_qr_code') }}
                        </span>
                    </router-link>
                </div>
                <div>
                    <div v-if="exportedAccounts.length > 0">
                        <div v-for="(account, index) in exportedAccounts" :key="account.name" class="group-item has-text-light is-size-5 is-size-6-mobile">
                            <div class="is-flex is-justify-content-space-between">
                                <!-- Account name -->
                                <div v-if="account.id > -2 && account.imported !== 0" class="has-ellipsis is-clickable" @click="previewAccount(index)" :title="$t('twofaccounts.import.generate_a_test_password')">
                                    {{ account.account }}
                                </div>
                                <div v-else class="has-ellipsis">{{ account.account }}</div>
                                <!-- buttons -->
                                <div v-if="account.imported === -1" class="tags is-flex-wrap-nowrap">
                                    <!-- discard button -->
                                    <a class="tag is-dark has-text-grey-light" @click="discardAccount(index)"  :title="$t('twofaccounts.import.discard_this_account')">
                                        <font-awesome-icon :icon="['fas', 'trash']" />
                                    </a>
                                    <!-- import button -->
                                    <a v-if="account.id > -2" class="tag is-link" @click="createAccount(index)"  :title="$t('twofaccounts.import.import_this_account')">
                                        {{ $t('twofaccounts.import.to_import') }}
                                    </a>
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
                            <span v-if="duplicateCount">
                                <a @click="discardDuplicates()" class="has-text-grey">{{ $t('twofaccounts.import.discard_duplicates') }} ({{duplicateCount}})</a> - 
                            </span>
                            <a @click="discardAccounts()" class="has-text-grey">{{ $t('twofaccounts.import.discard_all') }}</a>
                        </div>
                    </div>
                    <div v-if="isFetching && exportedAccounts.length === 0" class="has-text-centered">
                        <span class="is-size-4">
                            <font-awesome-icon :icon="['fas', 'spinner']" spin />
                        </span>
                    </div>
                </div>
                <!-- footer -->
                <vue-footer :showButtons="true">
                    <!-- Import all button -->
                    <p class="control" v-if="importableCount > 0">
                        <a class="button is-link is-rounded is-focus" @click="createAccounts">
                            <span>{{ $t('twofaccounts.import.import_all') }} ({{importableCount}})</span>
                            <!-- <span class="icon is-small">
                                <font-awesome-icon :icon="['fas', 'qrcode']" />
                            </span> -->
                        </a>
                    </p>
                    <!-- close button -->
                    <p class="control">
                        <router-link  :to="{ name: 'accounts', params: { toRefresh: true } }" class="button is-dark is-rounded" v-html="importableCount > 0 ? $t('commons.cancel') : $t('commons.close')"></router-link>
                    </p>
                </vue-footer>
            </div>
        </div>
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
                migrationUri: '',
                exportedAccounts: [],
                isFetching: false,
                form: new Form({
                    service: '',
                    account: '',
                    otp_type: '',
                    icon: '',
                    secret: '',
                    secretIsBase32Encoded: 1,
                    algorithm: '',
                    digits: null,
                    counter: null,
                    period: null,
                    image: '',
                    qrcode: null,
                }),
                ShowTwofaccountInModal : false,
            }
        },

        computed: {
            importableCount() {
                return this.exportedAccounts.filter(account => account.imported == -1 && account.id > -2).length;
            },

            duplicateCount() {
                return this.exportedAccounts.filter(account => account.id === -1 && account.imported === -1).length;
            },
        },

        mounted: async function() {
            // A migration URI is provided as route parameter, we extract the accounts from the URI and
            // list them in the view
            if( this.$route.params.migrationUri ) {
                this.migrationUri = this.$route.params.migrationUri
                this.isFetching = true

                await this.axios.post('/api/v1/twofaccounts/import', { uri: this.migrationUri }).then(response => {
                    response.data.forEach((data) => {
                        data.imported = -1;
                        this.exportedAccounts.push(data)
                    })
                });

                this.$notify({type: 'is-success', text: this.$t('twofaccounts.import.x_valid_accounts_found', { count: this.importableCount }) })
                this.isFetching = false
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
        }
    }

</script>