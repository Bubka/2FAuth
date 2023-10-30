<script setup>
    import Form from '@/components/formElements/Form'
    import OtpDisplay from '@/components/OtpDisplay.vue'
    import FormLockField from '@/components/formelements/FormLockField.vue'
    import twofaccountService from '@/services/twofaccountService'
    import { useUserStore } from '@/stores/user'
    import { useBusStore } from '@/stores/bus'
    import { useNotifyStore } from '@/stores/notify'
    import { UseColorMode } from '@vueuse/components'
    import { useIdGenerator } from '@/composables/helpers'
    
    const { copy } = useClipboard({ legacy: true })
    const $2fauth = inject('2fauth')
    const router = useRouter()
    const route = useRoute()
    const user = useUserStore()
    const bus = useBusStore()
    const notify = useNotifyStore()
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
        image: '',
        qrcode: null,
    }))
    const otp_types = [
        { text: 'TOTP', value: 'totp' },
        { text: 'HOTP', value: 'hotp' },
        { text: 'STEAM', value: 'steamtotp' },
    ]
    const digitsChoices = [
        { text: '6', value: 6 },
        { text: '7', value: 7 },
        { text: '8', value: 8 },
        { text: '9', value: 9 },
        { text: '10', value: 10 },
    ]
    const algorithms = [
        { text: 'sha1', value: 'sha1' },
        { text: 'sha256', value: 'sha256' },
        { text: 'sha512', value: 'sha512' },
        { text: 'md5', value: 'md5' },
    ]
    const uri = ref()
    const tempIcon = ref()
    const showQuickForm = ref(false)
    const showAlternatives = ref(false)
    const showAdvancedForm = ref(false)
    const ShowTwofaccountInModal = ref(false)
    const fetchingLogo = ref(false)
    const secretIsLocked = ref(false)

    // $refs
    const iconInput = ref(null)
    const OtpDisplayForQuickForm = ref(null)
    const OtpDisplayForAdvancedForm = ref(null)
    const qrcodeInputLabel = ref(null)
    const qrcodeInput = ref(null)
    const iconInputLabel = ref(null)
    
    const props = defineProps({
        twofaccountId: [Number, String]
    })

    const isEditMode = computed(() => {
        return props.twofaccountId != undefined
    })

    onMounted(() => {
        if (route.name == 'editAccount') {
            twofaccountService.get(props.twofaccountId).then(response => {
                form.fill(response.data)
                // set account icon as temp icon
                tempIcon.value = form.icon
                showAdvancedForm.value = true
            })
        }
        else if( bus.decodedUri ) {
            // the Start view provided an uri via the bus store so we parse it and prefill the quick form
            uri.value = bus.decodedUri
            bus.decodedUri = null

            twofaccountService.preview(uri.value).then(response => {
                form.fill(response.data)
                tempIcon.value = response.data.icon ? response.data.icon : null
                showQuickForm.value = true
                nextTick().then(() => {
                    OtpDisplayForQuickForm.value.show()
                })
            })
            .catch(error => {
                if( error.response.data.errors.uri ) {
                    showAlternatives.value = true
                    showAdvancedForm.value = true
                }
            })
        } else {
            showAdvancedForm.value = true
        }
    })

    watch(tempIcon, (val) => {
        if( showQuickForm.value ) {
            nextTick().then(() => {
                OtpDisplayForQuickForm.value.icon = val
            })
        }
    })

    watch(ShowTwofaccountInModal, (val) => {
        if (val == false) {
            OtpDisplayForAdvancedForm.value?.clearOTP()
            OtpDisplayForQuickForm.value?.clearOTP()
        }
    })

    watch(
        () => form.otp_type,
        (to, from) => {
            if (to === 'steamtotp') {
                form.service = 'Steam'
                fetchLogo()
            }
            else if (from === 'steamtotp') {
                form.service = ''
                deleteIcon()
            }
        }
    )

    /**
     * Wrapper to call the appropriate function at form submit
     */
     function handleSubmit() {
        isEditMode.value ? updateAccount() : createAccount()
    }

    /**
     * Submits the form to the backend to store the new account
     */
    async function createAccount() {
        // set current temp icon as account icon
        form.icon = tempIcon.value

        await form.post('/api/v1/twofaccounts')

        if (form.errors.any() === false) {
            notify.success({ text: trans('twofaccounts.account_created') })
            router.push({ name: 'accounts' });
        }
    }

    /**
     * Submits the form to the backend to save the edited account
     */
    async function updateAccount() {
        // Set new icon and delete old one
        if( tempIcon.value !== form.icon ) {
            let oldIcon = ''

            oldIcon = form.icon

            form.icon = tempIcon.value
            tempIcon.value = oldIcon
            deleteIcon()
        }

        await form.put('/api/v1/twofaccounts/' + props.twofaccountId)

        if( form.errors.any() === false ) {
            notify.success({ text: trans('twofaccounts.account_updated') })
            router.push({ name: 'accounts' })
        }
    }

    /**
     * Shows an OTP generated with the infos filled in the form
     * in order to preview or validated the password/the form data
     */
    function previewOTP() {
        form.clear()
        ShowTwofaccountInModal.value = true
        OtpDisplayForAdvancedForm.value.show()
    }

    /**
     * Exits the view with user confirmation
     */
    function cancelCreation() {
        if( form.hasChanged() ) {
            if( confirm(trans('twofaccounts.confirm.cancel')) === false ) {
                return
            }
        }
        // clean possible uploaded temp icon
        deleteIcon()

        router.push({name: 'accounts'});
    }

    /**
     * Uploads the submited image resource to the backend
     */
    function uploadIcon() {
        // clean possible already uploaded temp icon
        deleteIcon()

        const iconForm = new Form({
            icon: iconInput.value.files[0]
        })

        iconForm.upload('/api/v1/icons', { returnError: true })
        .then(response => {
            tempIcon.value = response.data.filename;
        })
        .catch(error => {
            notify.alert({ text: trans(error.response.data.message) })
        })
    }

    /**
     * Deletes the temp icon from backend
     */
    function deleteIcon() {
        if (tempIcon.value) {
            twofaccountService.deleteIcon(tempIcon.value)
            tempIcon.value = ''
        }
    }

    /**
     * Increments the HOTP counter of the form after a preview
     * 
     * @param {object} payload 
     */
    function incrementHotp(payload) {
        // The quick form or the preview feature has incremented the HOTP counter so we get the new value from
        // the OtpDisplay component.
        // This could desynchronized the HOTP verification server and our local counter if the user never verified the HOTP but this
        // is acceptable (and HOTP counter can be edited by the way)
        form.counter = payload.nextHotpCounter
        
        //form.uri = payload.nextUri
    }
    
    /**
     * Maps errors received by the OtpDisplay to the form errors instance
     * 
     * @param {object} errorResponse 
     */
    function mapDisplayerErrors(errorResponse) {
        form.errors.set(form.extractErrors(errorResponse))
    }

    /**
     * Sends a QR code to backend for decoding and prefill the form with the qr data
     */
    function uploadQrcode() {
        const qrcodeForm = new Form({
            qrcode: qrcodeInput.value.files[0]
        })

        // First we get the uri encoded in the qrcode
        qrcodeForm.upload('/api/v1/qrcode/decode', { returnError: true })
        .then(response => {
            uri.value = response.data.data
            
            // Then the otp described by the uri
            twofaccountService.preview(uri.value, { returnError: true }).then(response => {
                form.fill(response.data)
                tempIcon.value = response.data.icon ? response.data.icon : null
            })
            .catch(error => {
                if( error.response.status === 422 ) {
                    if( error.response.data.errors.uri ) {
                        showAlternatives.value = true
                    }
                    else notify.alert({ text: trans(error.response.data.message) })
                } else {
                    notify.error(error)
                }
            })
        })
        .catch(error => {
            notify.alert({ text: trans(error.response.data.message) })
            return false
        })
    }

    /**
     * Tries to get the official logo/icon of the Service filled in the form
     */
    function fetchLogo() {
        if (user.preferences.getOfficialIcons) {
            fetchingLogo.value = true

            twofaccountService.getLogo(form.service, { returnError: true })
            .then(response => {
                console.log('enter fetchLogo response')
                if (response.status === 201) {
                    // clean possible already uploaded temp icon
                    deleteIcon()
                    tempIcon.value = response.data.filename;
                }
                else notify.warn( {text: trans('errors.no_logo_found_for_x', {service: strip_tags(form.service)}) })
            })
            .catch(() => {
                notify.warn({ text: trans('errors.no_logo_found_for_x', {service: strip_tags(form.service)}) })
            })
            .finally(() => {
                fetchingLogo.value = false
            })
        }
    }

    /**
     * Strips html tags to prevent code injection
     * 
     * @param {*} str 
     */
    function strip_tags(str) {
        return str.replace(/(<([^> ]+)>)/ig, "")
    }

    /**
     * Checks if a string is an url
     * 
     * @param {string} str 
     */
    function isUrl(str) {
        var strRegex = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/
        var re = new RegExp(strRegex)

        return re.test(str)
    }

    /**
     * Opens an uri in a new browser window
     * 
     * @param {string} uri 
     */
    function openInBrowser(uri) {
        const a = document.createElement('a')
        a.setAttribute('href', uri)
        a.dispatchEvent(new MouseEvent("click", { 'view': window, 'bubbles': true, 'cancelable': true }))
    }

    /**
     * Copies to clipboard and notify
     * 
     * @param {*} data 
     */
    function copyToClipboard(data) {
        copy(data)
        notify.success({ text: trans('commons.copied_to_clipboard') })
    }

</script>

<template>
    <div>
        <!-- Quick form -->
        <form @submit.prevent="createAccount" @keydown="form.onKeydown($event)" v-if="!isEditMode && showQuickForm">
            <div class="container preview has-text-centered">
                <div class="columns is-mobile">
                    <div class="column">
                        <label class="add-icon-button" v-if="!tempIcon">
                            <input class="file-input" type="file" accept="image/*" v-on:change="uploadIcon" ref="iconInput">
                            <FontAwesomeIcon :icon="['fas', 'image']" size="2x" />
                        </label>
                        <button class="delete delete-icon-button is-medium" v-if="tempIcon" @click.prevent="deleteIcon"></button>
                        <OtpDisplay
                            ref="OtpDisplayForQuickForm"
                            v-bind="form.data()"
                            @increment-hotp="incrementHotp"
                            @validation-error="mapDisplayerErrors"
                            @please-close-me="ShowTwofaccountInModal = false">
                        </OtpDisplay>
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
                                <VueButton :isLoading="form.isBusy" >{{ $t('commons.save') }}</VueButton>
                            </div>
                            <div class="control">
                                <button id="btnCancel" type="button" class="button is-text" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- Full form -->
        <FormWrapper :title="$t(isEditMode ? 'twofaccounts.forms.edit_account' : 'twofaccounts.forms.new_account')" v-if="showAdvancedForm">
            <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
                <!-- qcode fileupload -->
                <div v-if="!isEditMode" class="field is-grouped">
                    <div class="control">
                        <div role="button" tabindex="0" class="file is-black is-small" @keyup.enter="qrcodeInputLabel.click()">
                            <label class="file-label" :title="$t('twofaccounts.forms.use_qrcode.title')" ref="qrcodeInputLabel">
                                <input aria-hidden="true" tabindex="-1" class="file-input" type="file" accept="image/*" v-on:change="uploadQrcode" ref="qrcodeInput">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <FontAwesomeIcon :icon="['fas', 'qrcode']" size="lg" />
                                    </span>
                                    <span class="file-label">{{ $t('twofaccounts.forms.prefill_using_qrcode') }}</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <FieldError v-if="!isEditMode && form.errors.hasAny('qrcode')" :error="form.errors.get('qrcode')" :field="'qrcode'" class="help-for-file" />
                <!-- service -->
                <FormField v-model="form.service" fieldName="service" :fieldError="form.errors.get('email')" :isDisabled="form.otp_type === 'steamtotp'" label="twofaccounts.service" :placeholder="$t('twofaccounts.forms.service.placeholder')" autofocus />
                <!-- account -->
                <FormField v-model="form.account" fieldName="account" :fieldError="form.errors.get('account')" :isDisabled="form.otp_type === 'steamtotp'" label="twofaccounts.account" :placeholder="$t('twofaccounts.forms.account.placeholder')" />
                <!-- icon upload -->
                <label class="label">{{ $t('twofaccounts.icon') }}</label>
                <div class="field is-grouped">
                    <!-- Try my luck button -->
                    <div class="control" v-if="user.preferences.getOfficialIcons">
                        <UseColorMode v-slot="{ mode }">
                            <VueButton @click="fetchLogo" :color="mode == 'dark' ? 'is-dark' : ''" :nativeType="'button'" :is-loading="fetchingLogo" :isDisabled="form.service.length < 1">
                                <span class="icon is-small">
                                    <FontAwesomeIcon :icon="['fas', 'globe']" />
                                </span>
                                <span>{{ $t('twofaccounts.forms.i_m_lucky') }}</span>
                            </VueButton>
                        </UseColorMode>
                    </div>
                    <!-- upload button -->
                    <div class="control">
                        <UseColorMode v-slot="{ mode }">
                            <div role="button" tabindex="0" class="file" :class="mode == 'dark' ? 'is-dark' : 'is-white'" @keyup.enter="iconInputLabel.click()">
                                <label class="file-label" ref="iconInputLabel">
                                    <input aria-hidden="true" tabindex="-1" class="file-input" type="file" accept="image/*" v-on:change="uploadIcon" ref="iconInput">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <FontAwesomeIcon :icon="['fas', 'upload']" />
                                        </span>
                                        <span class="file-label">{{ $t('twofaccounts.forms.choose_image') }}</span>
                                    </span>
                                </label>
                                <span class="tag is-large" :class="mode =='dark' ? 'is-dark' : 'is-white'" v-if="tempIcon">
                                    <img class="icon-preview" :src="$2fauth.config.subdirectory + '/storage/icons/' + tempIcon" :alt="$t('twofaccounts.icon_to_illustrate_the_account')">
                                    <button class="clear-selection delete is-small" @click.prevent="deleteIcon" :aria-label="$t('twofaccounts.remove_icon')"></button>
                                </span>
                            </div>
                        </UseColorMode>
                    </div>
                </div>
                <div class="field">
                    <FieldError v-if="form.errors.hasAny('icon')" :error="form.errors.get('icon')" :field="'icon'" class="help-for-file" />
                    <p v-if="user.preferences.getOfficialIcons" class="help" v-html="$t('twofaccounts.forms.i_m_lucky_legend')"></p>
                </div>
                <!-- otp type -->
                <FormToggle v-model="form.otp_type" :isDisabled="isEditMode" :choices="otp_types" fieldName="otp_type" :fieldError="form.errors.get('otp_type')" label="twofaccounts.forms.otp_type.label" help="twofaccounts.forms.otp_type.help" :hasOffset="true" />
                <div v-if="form.otp_type != ''">
                    <!-- secret -->
                    <FormLockField :isEditMode="isEditMode" v-model="form.secret" fieldName="secret" :fieldError="form.errors.get('secret')" label="twofaccounts.forms.secret.label" help="twofaccounts.forms.secret.help" />
                    <!-- Options -->
                    <div v-if="form.otp_type !== 'steamtotp'">
                        <h2 class="title is-4 mt-5 mb-2">{{ $t('commons.options') }}</h2>
                        <p class="help mb-4">
                            {{ $t('twofaccounts.forms.options_help') }}
                        </p>
                        <!-- digits -->
                        <FormToggle v-model="form.digits" :choices="digitsChoices" fieldName="digits" :fieldError="form.errors.get('digits')" label="twofaccounts.forms.digits.label" help="twofaccounts.forms.digits.help" />
                        <!-- algorithm -->
                        <FormToggle v-model="form.algorithm" :choices="algorithms" fieldName="algorithm" :fieldError="form.errors.get('algorithm')" label="twofaccounts.forms.algorithm.label" help="twofaccounts.forms.algorithm.help" />
                        <!-- TOTP period -->
                        <FormField v-if="form.otp_type === 'totp'" pattern="[0-9]{1,4}" :class="'is-third-width-field'" v-model="form.period" fieldName="period" :fieldError="form.errors.get('period')" label="twofaccounts.forms.period.label" help="twofaccounts.forms.period.help" :placeholder="$t('twofaccounts.forms.period.placeholder')" />
                        <!-- HOTP counter -->
                        <FormLockField v-if="form.otp_type === 'hotp'" pattern="[0-9]{1,4}" :isEditMode="isEditMode" :isExpanded="false" v-model="form.counter" fieldName="counter" :fieldError="form.errors.get('counter')" label="twofaccounts.forms.counter.label" :placeholder="$t('twofaccounts.forms.counter.placeholder')" :help="isEditMode ? 'twofaccounts.forms.counter.help_lock' : 'twofaccounts.forms.counter.help'" />
                    </div>
                </div>
                <VueFooter :showButtons="true">
                    <p class="control">
                        <VueButton :id="isEditMode ? 'btnUpdate' : 'btnCreate'" :isLoading="form.isBusy" class="is-rounded" >{{ isEditMode ? $t('commons.save') : $t('commons.create') }}</VueButton>
                    </p>
                    <p class="control" v-if="form.otp_type && form.secret">
                        <button id="btnPreview" type="button" class="button is-success is-rounded" @click="previewOTP">{{ $t('twofaccounts.forms.test') }}</button>
                    </p>
                    <p class="control">
                        <button id="btnCancel" type="button" class="button is-text is-rounded" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
                    </p>
                </VueFooter>
            </form>
            <!-- modal -->
            <modal v-model="ShowTwofaccountInModal">
                <OtpDisplay
                    ref="OtpDisplayForAdvancedForm"
                    v-bind="form.data()"
                    @increment-hotp="incrementHotp"
                    @validation-error="mapDisplayerErrors"
                    @please-close-me="ShowTwofaccountInModal = false">
                </OtpDisplay>
            </modal>
        </FormWrapper>
        <!-- alternatives -->
        <modal v-model="showAlternatives">
            <div class="too-bad"></div>
            <div class="block">
                {{ $t('errors.data_of_qrcode_is_not_valid_URI') }}
            </div>
            <UseColorMode v-slot="{ mode }">
                <div class="block mb-6" :class="mode == 'dark' ? 'has-text-light':'has-text-grey-dark'">{{ uri }}</div>
            </UseColorMode>
            <!-- Copy to clipboard -->
            <div class="block has-text-link">
                <button class="button is-link is-outlined is-rounded" @click.stop="copyToClipboard(uri)">
                    {{ $t('commons.copy_to_clipboard') }}
                </button>
            </div>
            <!-- Open in browser -->
            <div class="block has-text-link" v-if="isUrl(uri)" @click="openInBrowser(uri)">
                <button class="button is-link is-outlined is-rounded">
                    <span>{{ $t('commons.open_in_browser') }}</span>
                    <span class="icon is-small">
                        <FontAwesomeIcon :icon="['fas', 'external-link-alt']" />
                    </span>
                </button>
            </div>
        </modal>
    </div>
</template>
