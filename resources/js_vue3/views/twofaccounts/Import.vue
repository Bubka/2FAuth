<script setup>
    import Form from '@/components/formElements/Form'
    import twofaccountService from '@/services/twofaccountService'
    import OtpDisplay from '@/components/OtpDisplay.vue'
    import { useNotifyStore } from '@/stores/notify'
    import { useUserStore } from '@/stores/user'
    import { useBusStore } from '@/stores/bus'
    import { UseColorMode } from '@vueuse/components'

    const $2fauth = inject('2fauth')
    const notify = useNotifyStore()
    const user = useUserStore()
    const bus = useBusStore()
    const router = useRouter()
    const otpDisplay = ref(null)
    const fileInput = ref(null)
    const fileInputLabel = ref(null)
    const qrcodeInput = ref(null)
    const qrcodeInputLabel = ref(null)
    const form = reactive(new Form({
        service: '',
        account: '',
        otp_type: '',
        icon: '',
        secret: '',
        algorithm: '',
        digits: null,
        counter: null,
        period: null,
    }))
    const showTwofaccountInModal = ref(false)
    const supportedSources = [
        {app: '2FAuth', format: 'JSON'},
        {app: 'Google Auth', format: trans('twofaccounts.import.qr_code')},
        {app: 'Aegis Auth', format: 'JSON'},
        {app: 'Aegis Auth', format: trans('twofaccounts.import.plain_text')},
        {app: '2FAS auth', format: 'JSON'},
    ]
    const exportedAccounts = ref([])
    const isFetching = ref(false)

    const importableCount = computed(() => {
        return exportedAccounts.value.filter(account => account.imported == -1 && account.id > -2).length
    })

    const duplicateCount = computed(() => {
        return exportedAccounts.value.filter(account => account.id === -1 && account.imported === -1).length
    })

    const importedCount = computed(() => {
        return exportedAccounts.value.filter(account => account.imported === 1).length
    })

    watch(showTwofaccountInModal, (val) => {
        if (val == false) {
            otpDisplay.value?.clearOTP()
        }
    })

    onMounted(() => {
        // A migration URI has been provided by the Start view using the bus store
        // We extract the accounts from the URI and list them in the view
        if( bus.migrationUri ) {
            migrate(bus.migrationUri)
            bus.migrationUri = null
        }
    })

    /**
     * Posts the migration payload
     */
    async function migrate(payload) {
        isFetching.value = true

        await twofaccountService.migrate(payload, { returnError: true }).then(response => {
            response.data.forEach((data) => {
                data.imported = -1;
                exportedAccounts.value.push(data)
            })
            notifyValidAccountFound()
        })
        .catch(error => {
            notify.alert({ text: trans(error.response.data.message) })
        });

        isFetching.value = false
    }

    /**
     * Removes all duplicates from the accounts list
     */
    function discardDuplicates() {
        if(confirm(trans('twofaccounts.confirm.discard_duplicates'))) {
            notify.clear()
            otpDisplay.value?.clearOTP()
            exportedAccounts.value = exportedAccounts.value.filter(account => account.id !== -1)
        }
    }

    /**
     * Clears the accounts list
     */
    function discardAccounts() {
        if(confirm(trans('twofaccounts.confirm.discard_all'))) {
            notify.clear()
            otpDisplay.value?.clearOTP()
            exportedAccounts.value = []
        }
    }

    /**
     * Removes one duplicate from the accounts list
     */
    function discardAccount(accountIndex) {
        if(confirm(trans('twofaccounts.confirm.discard'))) {
            exportedAccounts.value.splice(accountIndex, 1)
        }
    }

    /**
     * Batch stores valid accounts, even duplicates
     */
    async function createAccounts() {
        for (let index = 0; index < exportedAccounts.value.length; index++) {
            if (exportedAccounts.value[index].imported == -1) {
                await createAccount(index)
            }
        }
    }

    /**
     * Stores the provided account
     */
    async function createAccount(accountIndex) {
        let twofaccount = exportedAccounts.value[accountIndex]
        mapAccountToForm(twofaccount)

        await form.post('/api/v1/twofaccounts', {returnError: true})
        .then(response => {
            exportedAccounts.value[accountIndex].imported = 1
            exportedAccounts.value[accountIndex].id = response.data.id
        })
        .catch(error => {
            exportedAccounts.value[accountIndex].imported = 0
            exportedAccounts.value[accountIndex].id = 0
            exportedAccounts.value[accountIndex].errors = form.errors.flatten()
        })
    }

    /**
     * Generates a fresh OTP password and displays it
     */
    function previewAccount(accountIndex) {
        mapAccountToForm(exportedAccounts.value[accountIndex])
        .then(() => {
            // this.$refs.OtpDisplayForAdvancedForm.$forceUpdate()
            otpDisplay.value?.show()
        })

    }

    /**
     * Maps account field with the Form object
     */
    function mapAccountToForm(twofaccount) {
        form.account = twofaccount.account
        form.service = twofaccount.service
        form.otp_type = twofaccount.otp_type
        form.icon = twofaccount.icon
        form.secret = twofaccount.secret
        form.algorithm = twofaccount.algorithm
        form.digits = twofaccount.digits
        form.counter = twofaccount.otp_type === 'hotp' ? twofaccount.counter : null
        form.period = twofaccount.otp_type === 'totp' ? twofaccount.period : null
    }

    const fileForm = new Form({
        file: null,
        withSecret: true
    })

    /**
     * Uploads the submitted file to the backend for parsing
     */
    function submitFile() {
        isFetching.value = true
        fileForm.file = fileInput.value.files[0]

        fileForm.upload('/api/v1/twofaccounts/migration', { returnError: true }).then(response => {
            response.data.forEach((data) => {
                data.imported = -1;
                exportedAccounts.value.push(data)
            })
            notifyValidAccountFound()
        })
        .catch(error => {
            if (error.response.status === 422) {
                if (error.response.data.errors.file == undefined) {
                    notify.alert({ text: trans('errors.invalid_2fa_data') })
                }
            }
            else notify.alert({ text: error.response.data.message})
        })
        
        isFetching.value = false
    }

    const qrcodeForm = new Form({
        qrcode: null,
        withSecret: true
    })

    /**
     * Uploads the submitted QR code file to the backend for decoding
     */
    function submitQrCode() {
        isFetching.value = true
        qrcodeForm.qrcode = qrcodeInput.value.files[0]

        qrcodeForm.upload('/api/v1/qrcode/decode', { returnError: true }).then(response => {
            migrate(response.data.data)
        })
        .catch(error => {
            if( error.response.status === 422 ) {
                if (error.response.data.errors.qrcode == undefined) {
                    notify.alert({ text: this.$t('errors.invalid_2fa_data') })
                }
            }
            else notify.alert({ text: error.response.data.message})
        })
        
        isFetching.value = false
    }

    /**
     * Pushes user to the dedicated capture view for live scan
     */
    function capture() {
        router.push({ name: 'capture' })
    }

    /**
     * Notifies that valid account(s) have been found for import
     */
    function notifyValidAccountFound() {
        notify.success({ text: trans('twofaccounts.import.x_valid_accounts_found', { count: importableCount.value }) })
    }

</script>

<template>
    <div>
        <ResponsiveWidthWrapper>
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
                                <FontAwesomeIcon :icon="['fas', 'camera']" />
                            </span>
                            <span>{{ $t('twofaccounts.import.scan') }}</span>
                        </button>
                        <span class="p-2 mb-2">{{ $t('commons.or') }}</span>
                        <!-- </div> -->
                        <!-- upload a qr code (with basic file field and backend decoding) -->
                        <!-- <div class="block"> -->
                        <label role="button" tabindex="0" class="button is-link is-rounded is-outlined" ref="qrcodeInputLabel"  @keyup.enter="qrcodeInputLabel.click()">
                            {{ $t('twofaccounts.import.upload') }}
                            <input aria-hidden="true" tabindex="-1" class="file-input" type="file" accept="image/*" v-on:change="submitQrCode" ref="qrcodeInput">
                        </label>
                    </div>
                    <FieldError v-if="qrcodeForm.errors.get('qrcode') != undefined" :error="qrcodeForm.errors.get('qrcode')" :field="qrcodeForm.qrcode" />
                    <p class="help">{{ $t('twofaccounts.import.supported_formats_for_qrcode_upload') }}</p>
                </div>
                <h5 class="title is-5 mb-3 has-text-grey">{{ $t('commons.file') }}</h5>
                <!-- upload a file -->
                <div class="block mb-6">
                    <label role="button" tabindex="0" class="button is-link is-rounded is-outlined" ref="fileInputLabel" @keyup.enter="fileInputLabel.click()">
                        <input aria-hidden="true" tabindex="-1" class="file-input" type="file" accept="text/plain,application/json,text/csv,.2fas" v-on:change="submitFile" ref="fileInput">
                        {{ $t('twofaccounts.import.upload') }}
                    </label>
                    <FieldError v-if="fileForm.errors.get('file') != undefined" :error="fileForm.errors.get('file')" :field="fileForm.file" />
                    <p class="help">{{ $t('twofaccounts.import.supported_formats_for_file_upload') }}</p>
                </div>
                <!-- Supported migration resources -->
                <h5 class="title is-6 mb-3 has-text-grey-dark">{{ $t('twofaccounts.import.supported_migration_formats') }}</h5>
                <div class="field is-grouped is-grouped-multiline pt-0">
                    <div v-for="(source, index) in supportedSources" :key="index" class="control">
                        <div class="tags has-addons">
                        <UseColorMode v-slot="{ mode }">
                            <span class="tag" :class="mode == 'dark' ? 'is-dark' : 'is-white'">{{ source.app }}</span>
                            <span class="tag" :class="mode == 'dark' ? 'is-black' : 'has-background-grey-lighter has-text-black'">{{ source.format }}</span>
                        </UseColorMode>
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
                            <img v-if="account.icon && user.preferences.showAccountsIcons" class="import-icon" :src="$2fauth.config.subdirectory + '/storage/icons/' + account.icon" :alt="$t('twofaccounts.icon_for_account_x_at_service_y', {account: account.account, service: account.service})">
                            {{ account.account }}
                        </div>
                        <div v-else class="is-flex-grow-1 has-ellipsis">{{ account.account }}</div>
                        <!-- buttons -->
                        <div v-if="account.imported === -1" class="tags is-flex-wrap-nowrap">
                            <!-- discard button -->
                            <UseColorMode v-slot="{ mode }">
                                <button class="button tag" :class="{'is-dark has-text-grey-light' : mode == 'dark'}" @click="discardAccount(index)"  :title="$t('twofaccounts.import.discard_this_account')">
                                    <FontAwesomeIcon :icon="['fas', 'trash']" />
                                </button>
                            </UseColorMode>
                            <!-- import button -->
                            <button v-if="account.id > -2" class="button tag is-link" @click="createAccount(index)"  :title="$t('twofaccounts.import.import_this_account')">
                                {{ $t('twofaccounts.import.to_import') }}
                            </button>
                        </div>
                        <!-- result label -->
                        <div v-else class="has-nowrap">
                            <span v-if="account.imported === 1" class="has-text-success">
                                {{ $t('twofaccounts.import.imported') }} <FontAwesomeIcon :icon="['fas', 'check']" />
                            </span>
                            <span v-else class="has-text-danger">
                                {{ $t('twofaccounts.import.failure') }} <FontAwesomeIcon :icon="['fas', 'times']" />
                            </span>
                        </div>
                    </div>
                    <div class="is-size-6 is-size-7-mobile">
                        <!-- service name -->
                        <div class="is-family-primary has-text-grey">{{ $t('twofaccounts.import.issuer') }}: {{ account.service }}</div>
                        <!-- reasons to invalid G-Auth data -->
                        <div v-if="account.id === -2" class="has-text-danger">
                            <FontAwesomeIcon class="mr-1" :icon="['fas', 'times-circle']" />{{ account.secret }}
                        </div>
                        <!-- possible duplicates -->
                        <div v-if="account.id === -1 && account.imported !== 1 && !account.errors" class="has-text-warning">
                            <FontAwesomeIcon class="mr-1" :icon="['fas', 'exclamation-circle']" />{{ $t('twofaccounts.import.possible_duplicate') }}
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
                    <FontAwesomeIcon :icon="['fas', 'spinner']" spin />
                </span>
            </div>
            <!-- footer -->
            <VueFooter :showButtons="true">
                <!-- Import all button -->
                <p class="control" v-if="importableCount > 0">
                    <button class="button is-link is-rounded is-focus" @click="createAccounts">
                        <span>{{ $t('twofaccounts.import.import_all') }} ({{importableCount}})</span>
                        <!-- <span class="icon is-small">
                            <FontAwesomeIcon :icon="['fas', 'qrcode']" />
                        </span> -->
                    </button>
                </p>
                <ButtonBackCloseCancel :action="importableCount > 0 ? 'cancel' : 'close'" />
            </VueFooter>
        </ResponsiveWidthWrapper>
        <!-- modal -->
        <modal v-model="showTwofaccountInModal">
            <OtpDisplay
                ref="otpDisplay"
                v-bind="form.data()"
                @increment-hotp=""
                @validation-error=""
                @please-close-me="showTwofaccountInModal = false">
            </OtpDisplay>
        </modal>
    </div>
</template>
