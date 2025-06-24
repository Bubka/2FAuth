<script setup>
    import Form from '@/components/formElements/Form'
    import OtpDisplay from '@/components/OtpDisplay.vue'
    import QrContentDisplay from '@/components/QrContentDisplay.vue'
    import { FormProtectedField } from '@2fauth/formcontrols'
    import twofaccountService from '@/services/twofaccountService'
    import { useUserStore } from '@/stores/user'
    import { useTwofaccounts } from '@/stores/twofaccounts'
    import { useGroups } from '@/stores/groups'
    import { useBusStore } from '@/stores/bus'
    import { useNotify } from '@2fauth/ui'
    import { UseColorMode } from '@vueuse/components'
    import { useI18n } from 'vue-i18n'
    import { useErrorHandler } from '@2fauth/stores'

    const errorHandler = useErrorHandler()
    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const router = useRouter()
    const route = useRoute()
    const user = useUserStore()
    const twofaccounts = useTwofaccounts()
    const bus = useBusStore()
    const notify = useNotify()
    const form = reactive(new Form({
        service: '',
        account: '',
        otp_type: '',
        icon: '',
        group_id: user.preferences.defaultGroup == -1 ? user.preferences.activeGroup : user.preferences.defaultGroup,
        secret: '',
        algorithm: '',
        digits: null,
        counter: null,
        period: null,
        image: '',
    }))
    const qrcodeForm = reactive(new Form({
        qrcode: null
    }))
    const iconForm = reactive(new Form({
        icon: null
    }))
    const iconCollections = [
        { text: 'selfh.st', value: 'selfh', asVariant: true },
        { text: 'dashboardicons.com', value: 'dashboardicons', asVariant: true },
        { text: '2fa.directory', value: 'tfa', asVariant: false },
    ]
    const iconCollectionVariants = {
        selfh: [
            { text: 'message.regular', value: 'regular' },
            { text: 'message.settings.forms.light', value: 'light' },
            { text: 'message.settings.forms.dark', value: 'dark' },
        ],
        dashboardicons: [
            { text: 'message.regular', value: 'regular' },
            { text: 'message.settings.forms.light', value: 'light' },
            { text: 'message.settings.forms.dark', value: 'dark' },
        ]
    }
    const otpDisplayProps = ref({
        otp_type: '',
        account : '',
        service : '',
        icon : '',
    })
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
    const tempIcon = ref('')
    const showQuickForm = ref(false)
    const showAlternatives = ref(false)
    const showOtpInModal = ref(false)
    const showAdvancedForm = ref(false)
    const ShowTwofaccountInModal = ref(false)
    const fetchingLogo = ref(false)
    const iconCollection = ref(user.preferences.iconCollection)
    const iconCollectionVariant = ref(user.preferences.iconVariant)
    

    // $refs
    const iconInput = ref(null)
    const OtpDisplayForAutoSave = ref(null)
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

    const groups = computed(() => {
        return useGroups().items.map((item) => {
            return { text: item.id > 0 ? item.name : '- ' + t('message.groups.no_group') + ' -', value: item.id }
        })
    })

    onMounted(() => {
        if (route.name == 'editAccount') {
            twofaccountService.get(props.twofaccountId).then(response => {
                form.fill(response.data)
                if (form.group_id == null) {
                    form.group_id = 0
                }
                form.setOriginal()
                // set account icon as temp icon
                tempIcon.value = form.icon
                showAdvancedForm.value = true
            })
        }
        else if( bus.decodedUri ) {
            // The Start|Capture view provided an uri via the bus store.
            uri.value = bus.decodedUri
            bus.decodedUri = null

            if (user.preferences.AutoSaveQrcodedAccount) {
                // The user wants the account to be saved automatically.
                // We save it now and we show him a fresh otp
                twofaccountService.storeFromUri(uri.value).then(response => {
                    showOTP(response.data)
                })
                .catch(error => {
                    if( error.response.data.errors.uri ) {
                        showAlternatives.value = true
                        showAdvancedForm.value = true
                    }
                })
            }
            else {
                // We prefill and show the quick form
                twofaccountService.preview(uri.value).then(response => {
                    form.fill(response.data)
                    tempIcon.value = response.data.icon ? response.data.icon : ''
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
            }
        } else {
            showAdvancedForm.value = true
        }
    })

    watch(iconCollection, (val) => {
        iconCollectionVariant.value = Object.prototype.hasOwnProperty.call(iconCollectionVariants, val)
            ? iconCollectionVariants[val][0].value
            : ''
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

    watch(showOtpInModal, (val) => {
        if (val == false) {
            OtpDisplayForAutoSave.value?.clearOTP()
            router.push({ name: 'accounts' })
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
                deleteTempIcon()
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

        const { data } = await form.post('/api/v1/twofaccounts')

        if (form.errors.any() === false) {
            twofaccounts.items.push(data)
            notify.success({ text: t('message.twofaccounts.account_created') })
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
            deleteTempIcon()
        }

        const { data } = await form.put('/api/v1/twofaccounts/' + props.twofaccountId)

        if( form.errors.any() === false ) {
            const index = twofaccounts.items.findIndex(acc => acc.id === data.id)
            twofaccounts.items.splice(index, 1, data)

            notify.success({ text: t('message.twofaccounts.account_updated') })
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
     * Shows rotating OTP for the provided account
     */
    function showOTP(otp) {
        // Data that should be displayed quickly by the OtpDisplay
        // component are passed using props.
        otpDisplayProps.value.otp_type = otp.otp_type
        otpDisplayProps.value.service = otp.service
        otpDisplayProps.value.account = otp.account
        otpDisplayProps.value.icon = otp.icon

        nextTick().then(() => {
            showOtpInModal.value = true
            OtpDisplayForAutoSave.value.show(otp.id);
        })
    }

    /**
     * Exits the view with user confirmation
     */
    function cancelCreation() {
        if (form.hasChanged() || tempIcon.value != form.icon) {
            if (confirm(t('message.twofaccounts.confirm.cancel')) === true) {
                if (!isEditMode.value || tempIcon.value != form.icon) {
                    deleteTempIcon()
                }
                router.push({name: 'accounts'})
            }
        }
        else router.push({name: 'accounts'})
    }

    /**
     * Uploads the submited image resource to the backend
     */
    function uploadIcon() {
        // clean possible already uploaded temp icon
        deleteTempIcon()
        iconForm.icon = iconInput.value.files[0]

        iconForm.upload('/api/v1/icons', { returnError: true })
        .then(response => {
            tempIcon.value = response.data.filename
            if (showQuickForm.value) {
                form.icon = tempIcon.value
            }
        })
        .catch(error => {
            if (error.response.status !== 422) {
                notify.alert({ text: error.response.data.message})
            }
        })
    }

    /**
     * Deletes the temp icon from backend
     */
    function deleteTempIcon() {
        if (isEditMode.value) {
            if (tempIcon.value) {
                if (tempIcon.value !== form.icon) {
                    twofaccountService.deleteIcon(tempIcon.value)
                }
                tempIcon.value = ''
            }
        }
        else if (tempIcon.value) {
            twofaccountService.deleteIcon(tempIcon.value)
            tempIcon.value = ''
            if (showQuickForm.value) {
                form.icon = ''
            }
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
        qrcodeForm.qrcode = qrcodeInput.value.files[0]

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
                    else notify.alert({ text: t(error.response.data.message) })
                } else {
                    errorHandler.parse(error)
                    router.push({ name: 'genericError' })
                }
            })
        })
        .catch(error => {
            if (error.response.status !== 422) {
                notify.alert({ text: error.response.data.message})
            }
        })
    }

    /**
     * Tries to get the official logo/icon of the Service filled in the form
     */
    function fetchLogo() {
        if (user.preferences.getOfficialIcons) {
            fetchingLogo.value = true

            twofaccountService.getLogo(form.service, iconCollection.value, iconCollectionVariant.value, { returnError: true })
            .then(response => {
                if (response.status === 201) {
                    // clean possible already uploaded temp icon
                    deleteTempIcon()
                    tempIcon.value = response.data.filename;
                }
                else notify.warn( {text: t('error.no_icon_for_this_variant') })
            })
            .catch(() => {
                notify.warn({ text: t('error.no_icon_for_this_variant') })
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

</script>

<template>
    <UseColorMode v-slot="{ mode }">
    <div>
        <!-- otp display modal -->
        <Modal v-if="user.preferences.AutoSaveQrcodedAccount" v-model="showOtpInModal">
            <OtpDisplay
                ref="OtpDisplayForAutoSave"
                v-bind="otpDisplayProps"
                @please-close-me="router.push({ name: 'accounts' })">
            </OtpDisplay>
        </Modal>
        <!-- Quick form -->
        <form @submit.prevent="createAccount" @keydown="form.onKeydown($event)" v-if="!isEditMode && showQuickForm">
            <div class="container preview has-text-centered">
                <div class="columns is-mobile">
                    <div class="column">
                        <FormFieldError v-if="iconForm.errors.hasAny('icon')" :error="iconForm.errors.get('icon')" :field="'icon'" class="help-for-file" />
                        <label class="add-icon-button" v-if="!tempIcon">
                            <input inert class="file-input" type="file" accept="image/*" v-on:change="uploadIcon" ref="iconInput">
                            <FontAwesomeIcon :icon="['fas', 'image']" size="2x" />
                        </label>
                        <button type="button" class="delete delete-icon-button is-medium" v-if="tempIcon" @click.prevent="deleteTempIcon"></button>
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
                                <VueButton nativeType="submit" :isLoading="form.isBusy" >{{ $t('message.save') }}</VueButton>
                            </div>
                            <NavigationButton action="cancel" :isText="true" :isRounded="false" :useLinkTag="false" @canceled="cancelCreation" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- Full form -->
        <FormWrapper :title="isEditMode ? 'message.twofaccounts.forms.edit_account' : 'message.twofaccounts.forms.new_account'" v-if="showAdvancedForm">
            <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
                <!-- qcode fileupload -->
                <div v-if="!isEditMode" class="field is-grouped">
                    <div class="control">
                        <div role="button" tabindex="0" class="file is-small" :class="{ 'is-black': mode == 'dark' }" @keyup.enter="qrcodeInputLabel.click()">
                            <label class="file-label" :title="$t('message.twofaccounts.forms.use_qrcode.title')" ref="qrcodeInputLabel">
                                <input inert tabindex="-1" class="file-input" type="file" accept="image/*" v-on:change="uploadQrcode" ref="qrcodeInput">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <FontAwesomeIcon :icon="['fas', 'qrcode']" size="lg" />
                                    </span>
                                    <span class="file-label">{{ $t('message.twofaccounts.forms.prefill_using_qrcode') }}</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <FormFieldError v-if="qrcodeForm.errors.hasAny('qrcode')" :error="qrcodeForm.errors.get('qrcode')" :field="'qrcode'" class="help-for-file" />
                <!-- service -->
                <FormField v-model="form.service" fieldName="service" :errorMessage="form.errors.get('email')" :isDisabled="form.otp_type === 'steamtotp'" label="message.twofaccounts.service" :placeholder="$t('message.twofaccounts.forms.service.placeholder')" autofocus />
                <!-- account -->
                <FormField v-model="form.account" fieldName="account" :errorMessage="form.errors.get('account')" label="message.twofaccounts.account" :placeholder="$t('message.twofaccounts.forms.account.placeholder')" />
                <!-- icon upload -->
                <label for="filUploadIcon" class="label">{{ $t('message.twofaccounts.icon') }}</label>
                <!-- try my luck -->
                <!-- <fieldset v-if="user.preferences.getOfficialIcons" :disabled="!form.service"> -->
                    <div class="field has-addons">
                        <div class="control">
                            <div class="select">
                                <select :disabled="!form.service" name="icon-collection" v-model="iconCollection">
                                    <option v-for="collection in iconCollections" :key="collection.text" :value="collection.value">
                                        {{ collection.text }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div v-if="iconCollectionVariants[iconCollection]" class="control">
                            <div class="select">
                                <select :disabled="!form.service" name="icon-collection-variant" v-model="iconCollectionVariant">
                                    <option v-for="variant in iconCollectionVariants[iconCollection]" :key="variant.value" :value="variant.value">
                                        {{ $t(variant.text) }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                <!-- </fieldset> -->
                <div class="field is-grouped">
                    <!-- try my luck button -->
                    <div class="control">
                        <VueButton @click="fetchLogo" :color="mode == 'dark' ? 'is-dark' : ''" nativeType="button" :is-loading="fetchingLogo" :disabled="!form.service" aria-describedby="lgdTryMyLuck">
                            <span class="icon is-small">
                                <FontAwesomeIcon :icon="['fas', 'globe']" />
                            </span>
                            <span>{{ $t('message.twofaccounts.forms.i_m_lucky') }}</span>
                        </VueButton>
                    </div>
                    <!-- upload icon button -->
                    <div class="control is-flex">
                        <div role="button" tabindex="0" class="file mr-3" :class="mode == 'dark' ? 'is-dark' : 'is-white'" @keyup.enter="iconInputLabel.click()">
                            <label for="filUploadIcon" class="file-label" ref="iconInputLabel">
                                <input id="filUploadIcon" tabindex="-1" class="file-input" type="file" accept="image/*" v-on:change="uploadIcon" ref="iconInput">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <FontAwesomeIcon :icon="['fas', 'upload']" />
                                    </span>
                                    <span class="file-label">{{ $t('message.twofaccounts.forms.choose_image') }}</span>
                                </span>
                            </label>
                        </div>
                        <span class="tag is-large" :class="mode =='dark' ? 'is-dark' : 'is-white'" v-if="tempIcon">
                            <img class="icon-preview" :src="$2fauth.config.subdirectory + '/storage/icons/' + tempIcon" :alt="$t('message.twofaccounts.icon_to_illustrate_the_account')">
                            <button type="button" class="clear-selection delete is-small" @click.prevent="deleteTempIcon" :aria-label="$t('message.twofaccounts.remove_icon')"></button>
                        </span>
                    </div>
                </div>
                <div class="field">
                    <FormFieldError v-if="iconForm.errors.hasAny('icon')" :error="iconForm.errors.get('icon')" :field="'icon'" class="help-for-file" />
                    <p id="lgdTryMyLuck" v-if="user.preferences.getOfficialIcons" class="help" v-html="$t('message.twofaccounts.forms.i_m_lucky_legend')"></p>
                </div>
                <!-- group -->
                <FormSelect v-if="groups.length > 0" v-model="form.group_id" :options="groups" fieldName="group_id" label="message.twofaccounts.forms.group.label" help="message.twofaccounts.forms.group.help" />
                <!-- otp type -->
                <FormToggle v-model="form.otp_type" :isDisabled="isEditMode" :choices="otp_types" fieldName="otp_type" :errorMessage="form.errors.get('otp_type')" label="message.twofaccounts.forms.otp_type.label" help="message.twofaccounts.forms.otp_type.help" :hasOffset="true" />
                <div v-if="form.otp_type != ''">
                    <!-- secret -->
                    <FormProtectedField :enableProtection="isEditMode" v-model.trimAll="form.secret" fieldName="secret" :errorMessage="form.errors.get('secret')" label="message.twofaccounts.forms.secret.label" help="message.twofaccounts.forms.secret.help" />
                    <!-- Options -->
                    <div v-if="form.otp_type !== 'steamtotp'">
                        <h2 class="title is-4 mt-5 mb-2">{{ $t('message.options') }}</h2>
                        <p class="help mb-4">
                            {{ $t('message.twofaccounts.forms.options_help') }}
                        </p>
                        <!-- digits -->
                        <FormToggle v-model="form.digits" :choices="digitsChoices" fieldName="digits" :errorMessage="form.errors.get('digits')" label="message.twofaccounts.forms.digits.label" help="message.twofaccounts.forms.digits.help" />
                        <!-- algorithm -->
                        <FormToggle v-model="form.algorithm" :choices="algorithms" fieldName="algorithm" :errorMessage="form.errors.get('algorithm')" label="message.twofaccounts.forms.algorithm.label" help="message.twofaccounts.forms.algorithm.help" />
                        <!-- TOTP period -->
                        <FormField v-if="form.otp_type === 'totp'" pattern="[0-9]{1,4}" :class="'is-half-width-field'" v-model="form.period" fieldName="period" :errorMessage="form.errors.get('period')" label="message.twofaccounts.forms.period.label" help="message.twofaccounts.forms.period.help" :placeholder="$t('message.twofaccounts.forms.period.placeholder')" />
                        <!-- HOTP counter -->
                        <FormProtectedField v-if="form.otp_type === 'hotp'" pattern="[0-9]{1,4}" :enableProtection="isEditMode" :isExpanded="false" v-model="form.counter" fieldName="counter" :errorMessage="form.errors.get('counter')" label="message.twofaccounts.forms.counter.label" :placeholder="$t('message.twofaccounts.forms.counter.placeholder')" :help="isEditMode ? 'message.twofaccounts.forms.counter.help_lock' : 'message.twofaccounts.forms.counter.help'" />
                    </div>
                </div>
                <VueFooter>
                    <template #default>
                        <p class="control">
                            <VueButton nativeType="submit" :id="isEditMode ? 'btnUpdate' : 'btnCreate'" :isLoading="form.isBusy" class="is-rounded" >{{ isEditMode ? $t('message.save') : $t('message.create') }}</VueButton>
                        </p>
                        <p class="control" v-if="form.otp_type && form.secret">
                            <button id="btnPreview" type="button" class="button is-success is-rounded" @click="previewOTP">{{ $t('message.twofaccounts.forms.test') }}</button>
                        </p>
                        <NavigationButton action="cancel" :useLinkTag="false" @canceled="cancelCreation" />
                    </template>
                </VueFooter>
            </form>
            <!-- modal -->
            <Modal v-model="ShowTwofaccountInModal">
                <OtpDisplay
                    ref="OtpDisplayForAdvancedForm"
                    v-bind="form.data()"
                    @increment-hotp="incrementHotp"
                    @validation-error="mapDisplayerErrors"
                    @please-close-me="ShowTwofaccountInModal = false">
                </OtpDisplay>
            </Modal>
        </FormWrapper>
        <!-- alternatives -->
        <Modal v-model="showAlternatives">
            <QrContentDisplay :qrContent="uri" />
        </Modal>
    </div>
    </UseColorMode>
</template>
