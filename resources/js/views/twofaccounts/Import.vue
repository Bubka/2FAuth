<template>
    <div class="columns is-centered">
        <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
            <h1 class="title">
                {{ $t('twofaccounts.import.import') }}
            </h1>
            <div class="is-size-7-mobile">
                {{ $t('twofaccounts.import.import_legend')}}
            </div>
            <div v-if="!migrationUri" class="mt-3 mb-6">
                <router-link class="is-link mt-5" :to="{ name: 'start' }">
                    <font-awesome-icon :icon="['fas', 'plus-circle']" /> {{ $t('twofaccounts.import.use_a_qr_code') }}
                </router-link>
            </div>
            <div v-else>
                <div v-if="exportedAccounts.length > 0">
                    <div v-for="(account, index) in exportedAccounts" :key="account.name" class="group-item has-text-light is-size-5 is-size-6-mobile">
                        {{ account.account }}
                        <!-- import button -->
                        <a class="tag is-dark is-pulled-right" @click="createAccount(index)"  :title="$t('twofaccounts.import.import')">
                            {{ $t('twofaccounts.import.import') }}
                        </a>
                        <!-- remove button -->
                        <a class="tag is-dark is-pulled-right" @click="discardAccount(index)"  :title="$t('commons.discard')">
                            {{ $t('commons.discard') }}
                        </a>
                        <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey">{{ $t('twofaccounts.import.issuer') }}: {{ account.service }}</span>
                    </div>
                    <!-- <div class="mt-2 is-size-7 is-pulled-right" v-if="exportedAccounts.length > 0">
                        {{ $t('groups.deleting_group_does_not_delete_accounts')}}
                    </div> -->
                </div>
                <div v-if="isFetching && exportedAccounts.length === 0" class="has-text-centered">
                    <span class="is-size-4">
                        <font-awesome-icon :icon="['fas', 'spinner']" spin />
                    </span>
                </div>
            </div>
            <!-- footer -->
            <vue-footer :showButtons="true">
                <!-- close button -->
                <p class="control">
                    <router-link  :to="{ name: 'accounts', params: { toRefresh: true } }" class="button is-dark is-rounded">{{ $t('commons.close') }}</router-link>
                </p>
            </vue-footer>
        </div>
    </div>
</template>

<script>
    import Form from './../../components/Form'

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
                    secretIsBase32Encoded: 0,
                    algorithm: '',
                    digits: null,
                    counter: null,
                    period: null,
                    image: '',
                    qrcode: null,
                }),
            }
        },

        mounted: async function() {
            if( this.$route.params.migrationUri ) {
                this.migrationUri = this.$route.params.migrationUri
                this.isFetching = true

                await this.axios.post('/api/v1/twofaccounts/import', { uri: this.migrationUri }).then(response => {
                    // we should receive an array of twofaccounts
                    response.data.forEach((data) => {
                        this.exportedAccounts.push(data)
                    })
                    
                })
                .catch(error => {
                    // if( error.response.status === 422 ) {
                    //     if( error.response.data.errors.uri ) {
                    //         this.showAlternatives = true
                    //         this.showAdvancedForm = true
                    //     }
                    // }
                });

                this.isFetching = false
            }
            else {
                // move to error because migration uri is missing
                // todo
            }
        },

        created: function() {
        },

        methods: {

            discardAccount(accountId) {
                this.exportedAccounts.splice(accountId, 1)
            },

            async createAccounts() {
                for (let i = 0; i < this.exportedAccounts.length; i++) {
                    await createAccount(i)
                    
                }
            },

            async createAccount(accountId) {

                let twofaccount = this.exportedAccounts[accountId]

                this.form.account = twofaccount.account
                this.form.service = twofaccount.service
                this.form.otp_type = twofaccount.otp_type
                this.form.secret = twofaccount.secret
                this.form.secretIsBase32Encoded = 1
                this.form.algorithm = twofaccount.algorithm
                this.form.digits = twofaccount.digits
                this.form.counter = twofaccount.otp_type === 'hotp' ? twofaccount.counter : null
                this.form.period = twofaccount.otp_type === 'totp' ? twofaccount.period : null

                await this.form.post('/api/v1/twofaccounts')

                if( this.form.errors.any() === false ) {
                    console.log('account #' + accountId + 'created')
                }

            },
        }
    }

</script>