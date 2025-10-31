<script setup>
    import Form from '@/components/formElements/Form'
    import twofaccountService from '@/services/twofaccountService'
    import { FormTextarea } from '@2fauth/formcontrols'
    import { useNotify, OtpDisplay } from '@2fauth/ui'
    import { useUserStore } from '@/stores/user'
    import { useBusStore } from '@/stores/bus'
    import { useTwofaccounts } from '@/stores/twofaccounts'
    import { UseColorMode } from '@vueuse/components'
    import { useI18n } from 'vue-i18n'
    import { LucideCheck, LucideCircleAlert, LucideCircleX, LucideFileText, LucideQrCode, LucideTextCursorInput, LucideTriangleAlert, LucideX } from 'lucide-vue-next'

    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const notify = useNotify()
    const user = useUserStore()
    const bus = useBusStore()
    const twofaccounts = useTwofaccounts()
    const otpDisplay = ref(null)
    const fileInput = ref(null)
    const qrcodeInput = ref(null)
    const directInput = ref(null)
    const directInputError = ref(null)
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
    const fileForm = reactive(new Form({
        file: null,
        withSecret: true
    }))
    const qrcodeForm = reactive(new Form({
        qrcode: null,
        withSecret: true
    }))
    const showTwofaccountInModal = ref(false)
    const supportedSources = [
        {app: '2FAuth', format: 'JSON'},
        {app: 'Google Auth', format: t('label.qr_code')},
        {app: 'Aegis Auth', format: 'JSON'},
        {app: 'Aegis Auth', format: t('label.plain_text')},
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
    async function migrate(payload, form = null) {
        isFetching.value = true

        await twofaccountService.migrate(payload, { returnError: true }).then(response => {
            response.data.forEach((data) => {
                data.imported = -1;
                exportedAccounts.value.push(data)
            })
            notifyValidAccountFound()
            directInput.value = directInputError.value = null
        })
        .catch(error => {
            notify.alert({ text: t(error.response.data.message) })

            if(form != null && error.response.status === 422) {
                form.clear()
                form.errors.set(form.extractErrors(error.response))
            }
        })
        .finally(() => {
            isFetching.value = false
        })
    }

    /**
     * Removes all duplicates from the accounts list
     */
    function discardDuplicates() {
        if(confirm(t('confirmation.discard_duplicates'))) {
            notify.clear()
            otpDisplay.value?.clearOTP()
            exportedAccounts.value = exportedAccounts.value.filter(account => account.id !== -1)
        }
    }

    /**
     * Clears the accounts list
     */
    function discardAccounts() {
        if(confirm(t('confirmation.discard_all'))) {
            notify.clear()
            otpDisplay.value?.clearOTP()
            exportedAccounts.value = []
        }
    }

    /**
     * Removes one duplicate from the accounts list
     */
    function discardAccount(accountIndex) {
        if(confirm(t('confirmation.discard'))) {
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
        twofaccounts.sortDefault()
    }

    /**
     * Stores the provided account
     */
    async function createAccount(accountIndex) {
        form.fill(exportedAccounts.value[accountIndex])

        await form.post('/api/v1/twofaccounts', {returnError: true})
        .then(response => {
            exportedAccounts.value[accountIndex].imported = 1
            exportedAccounts.value[accountIndex].id = response.data.id
            delete response.data.secret
            twofaccounts.items.push(response.data)
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
        form.fill(exportedAccounts.value[accountIndex])
        showTwofaccountInModal.value = true

        nextTick().then(() => {
            otpDisplay.value.show()
        })
    }

    /**
     * Uploads the submitted file to the backend for parsing
     */
    function submitFile() {
        fileForm.clear()
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
                if (error.response.data.errors?.file == undefined) {
                    notify.alert({ text: t('error.invalid_2fa_data') })
                }
            }
            else notify.alert({ text: error.response.data.message})
        })
        .finally(() => {
            isFetching.value = false
        })
        
    }

    /**
     * Uploads the submitted QR code file to the backend for decoding
     */
    function submitQrCode() {
        qrcodeForm.clear()
        isFetching.value = true
        qrcodeForm.qrcode = qrcodeInput.value.files[0]

        qrcodeForm.upload('/api/v1/qrcode/decode', { returnError: true }).then(response => {
            migrate(response.data.data, qrcodeForm)
        })
        .catch(error => {
            if( error.response.status === 422 ) {
                if (error.response.data.errors?.qrcode == undefined) {
                    notify.alert({ text: t('error.invalid_2fa_data') })
                }
            }
            else notify.alert({ text: error.response.data.message})
        })
        .finally(() => {
            isFetching.value = false
        })
    }

    /**
     * Notifies that valid account(s) have been found for import
     */
    function notifyValidAccountFound() {
        notify.success({ text: t('notification.x_valid_accounts_found', { count: importableCount.value }) })
    }

    /**
     * Submits the directInput form to the backend
     */
    function submitDirectInput() {
        directInputError.value = null
        
        if (! directInput.value) {
            directInputError.value = t('validation.required', { attribute: 'Direct input' })
        }
        else migrate(directInput.value)
    }

</script>

<template>
    <UseColorMode v-slot="{ mode }">
    <div>
        <ResponsiveWidthWrapper>
            <h1 class="title has-text-grey-dark">
                {{ $t('heading.import') }}
            </h1>
            <div v-if="!isFetching && exportedAccounts.length === 0">
                <div class="block is-size-7-mobile">
                    <p class="mb-2">{{ $t('message.import_legend') }}</p>
                    <p>{{ $t('message.import_legend_afterpart') }}</p>
                </div>
                <div class="columns">
                    <div class="column">
                        <div class="block">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media">
                                        <div class="media-left">
                                            <figure class="image is-32x32">
                                                <LucideQrCode class="icon-size-2" :class="mode == 'dark' ? 'has-text-grey-darker' : 'has-text-grey-lighter'" />
                                            </figure>
                                        </div>
                                        <div class="media-content">
                                            <p class="title is-5 has-text-grey">{{ $t('heading.qr_code') }}</p>
                                            <p class="subtitle is-6 is-size-7-mobile">{{ $t('message.supported_formats_for_qrcode_upload') }}</p>
                                        </div>
                                    </div>
                                    <!-- <FormFieldError v-if="qrcodeForm.errors.hasAny('qrcode')" :error="qrcodeForm.errors.get('qrcode')" :field="'qrcode'" /> -->
                                    <template v-if="qrcodeForm.errors.any()">
                                        <template v-for="(messages, error) in qrcodeForm.errors.all()" :key="error">
                                            <FormFieldError :error="qrcodeForm.errors.get(error)" :field="error" />
                                        </template>
                                    </template>
                                </div>
                                <footer class="card-footer">
                                    <RouterLink id="btnCapture" :to="{ name: 'capture' }" class="card-footer-item">
                                        {{ $t('link.scan') }}
                                    </RouterLink>
                                    <a role="button" tabindex="0" class="card-footer-item is-relative" @click="qrcodeInput.click()" @keyup.enter="qrcodeInput.click()">
                                        <input inert tabindex="-1" class="file-input" type="file" accept="image/*" v-on:change="submitQrCode" ref="qrcodeInput">
                                        {{ $t('label.upload') }}
                                    </a>
                                </footer>
                            </div>
                        </div>
                        <div class="block">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media">
                                        <div class="media-left">
                                            <figure class="image is-32x32">
                                                <LucideFileText class="icon-size-2" :class="mode == 'dark' ? 'has-text-grey-darker' : 'has-text-grey-lighter'" />
                                            </figure>
                                        </div>
                                        <div class="media-content">
                                            <p class="title is-5 has-text-grey">{{ $t('label.text_file') }}</p>
                                            <p class="subtitle is-6 is-size-7-mobile">{{ $t('message.supported_formats_for_file_upload') }}</p>
                                        </div>
                                    </div>
                                    <template v-if="fileForm.errors.any()">
                                        <template v-for="(messages, error) in fileForm.errors.all()" :key="error">
                                            <FormFieldError :error="fileForm.errors.get(error)" :field="error" />
                                        </template>
                                    </template>
                                </div>
                                <footer class="card-footer">
                                    <a role="button" tabindex="0" class="card-footer-item is-relative" @click="fileInput.click()" @keyup.enter="fileInput.click()">
                                        <input inert tabindex="-1" class="file-input" type="file" accept="text/plain,application/json,text/csv,.2fas" v-on:change="submitFile" ref="fileInput">
                                        {{ $t('label.upload') }}
                                    </a>
                                </footer>
                            </div>
                        </div>
                        <div class="block">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media">
                                        <div class="media-left">
                                            <figure class="image is-32x32">
                                                <LucideTextCursorInput class="icon-size-2" :class="mode == 'dark' ? 'has-text-grey-darker' : 'has-text-grey-lighter'" />
                                            </figure>
                                        </div>
                                        <div class="media-content">
                                            <p class="title is-5 has-text-grey">{{ $t('message.direct_input') }}</p>
                                            <p class="subtitle is-6 is-size-7-mobile">{{ $t('message.expected_format_for_direct_input') }}</p>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <FormTextarea v-model="directInput" :errorMessage="directInputError" fieldName="payload" rows="5" :size="'is-small'" />
                                    </div>
                                </div>
                                <footer class="card-footer">
                                    <a role="button" tabindex="0" class="card-footer-item is-relative" @click.stop="submitDirectInput">
                                        {{ $t('label.submit') }}
                                    </a>
                                </footer>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Supported migration resources -->
                <h2 class="title is-5 has-text-grey-dark">{{ $t('heading.supported_migration_formats') }}</h2>
                <div class="block is-size-7-mobile">
                    <LucideTriangleAlert class="inline icon-size-1 has-text-warning-dark" />
                    {{  $t('message.do_not_set_password_or_encryption') }}
                </div>
                <table class="table is-size-7-mobile is-fullwidth">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Plain text</th>
                            <th>QR code</th>
                            <th>JSON</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Google Authenticator</th>
                            <td></td>
                            <td><LucideCheck stroke-width="3" class="icon-size-1" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Aegis Auth</th>
                            <td><LucideCheck stroke-width="3" class="icon-size-1" /></td>
                            <td></td>
                            <td><LucideCheck stroke-width="3" class="icon-size-1" /></td>
                        </tr>
                        <tr>
                            <th>2FAS auth</th>
                            <td></td>
                            <td></td>
                            <td><LucideCheck stroke-width="3" class="icon-size-1" /></td>
                        </tr>
                        <tr>
                            <th>FreeOTP+</th>
                            <td><LucideCheck stroke-width="3" class="icon-size-1" /></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>2FAuth</th>
                            <td></td>
                            <td></td>
                            <td><LucideCheck stroke-width="3" class="icon-size-1" /></td>
                        </tr>
                        <tr>
                            <th>Bitwarden</th>
                            <td></td>
                            <td></td>
                            <td><LucideCheck stroke-width="3" class="icon-size-1" /></td>
                        </tr>
                        <tr>
                            <th>Bitwarden Authenticator</th>
                            <td></td>
                            <td></td>
                            <td><LucideCheck stroke-width="3" class="icon-size-1" /></td>
                        </tr>
                    </tbody>
                </table>           
            </div>
            <div v-else-if="isFetching && exportedAccounts.length === 0">
                <Spinner :type="'fullscreen-overlay'" :isVisible="true" message="message.parsing_data" />
            </div>
            <div v-else>
                <div class="block is-size-7-mobile">
                    <p class="mb-2">{{ $t('message.submitted_data_parsed_now_accounts_are_awaiting_import') }}</p>
                    <p>{{ $t('message.use_buttons_to_save_or_discard') }}</p>
                </div>
                <div v-for="(account, index) in exportedAccounts" :key="account.name" class="group-item is-size-5 is-size-6-mobile">
                    <div class="is-flex is-justify-content-space-between">
                        <!-- Account name -->
                        <div v-if="account.id > -2 && account.imported !== 0" class="is-flex-grow-1 has-ellipsis is-clickable" @click="previewAccount(index)" :title="$t('tooltip.generate_a_test_password')">
                            <img role="presentation" v-if="account.icon && user.preferences.showAccountsIcons" class="import-icon" :src="$2fauth.config.subdirectory + '/storage/icons/' + account.icon" alt="">
                            {{ account.account }}
                        </div>
                        <div v-else class="is-flex-grow-1 has-ellipsis">{{ account.account }}</div>
                        <!-- buttons -->
                        <div v-if="account.imported === -1" class="tags is-flex-wrap-nowrap">
                            <!-- import button -->
                            <button v-if="account.id > -2" type="button" class="button tag is-link" @click="createAccount(index)"  :title="$t('tooltip.import_this_account')">
                                {{ $t('label.to_import') }}
                            </button>
                            <!-- discard button -->
                            <button type="button" class="button tag" :class="{'is-dark has-text-grey-light' : mode == 'dark'}" @click="discardAccount(index)"  :title="$t('tooltip.discard_this_account')">
                                <LucideX class="icon-size-1" />
                            </button>
                        </div>
                        <!-- result label -->
                        <div v-else class="has-nowrap">
                            <span v-if="account.imported === 1" class="has-text-success">
                                {{ $t('message.imported') }} <LucideCheck class="inline" />
                            </span>
                            <span v-else class="has-text-danger">
                                {{ $t('message.failure') }} <LucideX class="inline" />
                            </span>
                        </div>
                    </div>
                    <div class="is-size-6 is-size-7-mobile">
                        <!-- service name -->
                        <div class="is-family-primary has-text-grey">{{ $t('label.issuer') }}: {{ account.service }}</div>
                        <!-- reasons to invalid G-Auth data -->
                        <div v-if="account.id === -2" class="has-text-danger">
                            <LucideCircleX class="mr-1 icon-size-1 inline" />{{ account.secret }}
                        </div>
                        <!-- possible duplicates -->
                        <div v-if="account.id === -1 && account.imported !== 1 && !account.errors" class="has-text-warning">
                            <LucideCircleAlert class="mr-1 icon-size-1 inline" />{{ $t('message.possible_duplicate') }}
                        </div>
                        <!-- errors during account creation -->
                        <ul v-if="account.errors">
                            <li v-for="(error) in account.errors" :key="error" class="has-text-danger">{{ error }}</li>
                        </ul>
                    </div>
                </div>
                <!-- discard links -->
                <div v-if="importableCount > 0" class="mt-2 is-size-7 is-pulled-right">
                    <button v-if="duplicateCount" @click="discardDuplicates()" type="button" class="has-text-grey button is-small is-ghost">{{ $t('label.discard_duplicates') }} ({{duplicateCount}})</button>
                    <button @click="discardAccounts()" type="button" class="has-text-grey button is-small is-ghost">{{ $t('label.discard_all') }}</button>
                </div>
                <div v-if="importedCount == exportedAccounts.length"  class="mt-2 is-size-7 is-pulled-right">
                    <button @click="exportedAccounts = []" type="button" class="has-text-grey button is-small is-ghost">{{ $t('label.clear') }}</button>
                </div>
            </div>
            <!-- footer -->
            <VueFooter>
                <template #default>
                    <!-- Import all button -->
                    <p class="control" v-if="importableCount > 0">
                        <button type="button" class="button is-link is-rounded is-focus" @click="createAccounts">
                            <span>{{ $t('label.import_all') }} ({{ importableCount }})</span>
                        </button>
                    </p>
                    <NavigationButton v-if="importableCount > 0" action="cancel" @canceled="router.push({ name: 'accounts' })" />
                    <NavigationButton v-else action="close" @closed="router.push({ name: 'accounts' })" :current-page-title="$t('title.importAccounts')" />
                </template>
            </VueFooter>
        </ResponsiveWidthWrapper>
        <!-- modal -->
        <Modal v-model="showTwofaccountInModal">
            <OtpDisplay
                ref="otpDisplay"
                :accountParams="form.data()"
                :preferences="user.preferences"
                :twofaccountService="twofaccountService"
                :iconPathPrefix="$2fauth.config.subdirectory"
                @please-close-me="showTwofaccountInModal = false"
                @otp-copied-to-clipboard="notify.success({ text: t('notification.copied_to_clipboard') })"
                @error="(error) => errorHandler.show(error)"
            />
        </Modal>
    </div>
    </UseColorMode>
</template>
