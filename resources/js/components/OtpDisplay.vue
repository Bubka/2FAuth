<script setup>
    import Spinner from '@/components/Spinner.vue'
    import TotpLooper from '@/components/TotpLooper.vue'
    import Dots from '@/components/Dots.vue'
    import twofaccountService from '@/services/twofaccountService'
    import { useUserStore } from '@/stores/user'
    import { useNotifyStore } from '@/stores/notify'
    import { UseColorMode } from '@vueuse/components'
    import { useDisplayablePassword } from '@/composables/helpers'

    const user = useUserStore()
    const notify = useNotifyStore()
    const $2fauth = inject('2fauth')
    const { copy, copied } = useClipboard({ legacy: true })
    const route = useRoute()
    
    const emit = defineEmits(['please-close-me', 'increment-hotp', 'validation-error'])
    const props = defineProps({
        otp_type : String,
        account : String,
        service : String,
        icon : String,
        secret : String,
        digits : Number,
        algorithm : String,
        period : null,
        counter : null,
        image : String,
        qrcode : null,
        uri : String
    })

    const id = ref(null)
    const uri = ref(null)
    const otpauthParams = ref({
        otp_type : '',
        account : '',
        service : '',
        icon : '',
        secret : '',
        digits : null,
        algorithm : '',
        period : null,
        counter : null,
        image : ''
    })
    const password = ref('')
    const generated_at = ref(null)
    const hasTOTP = ref(false)
    const showInlineSpinner = ref(false)
    const revealPassword = ref(false)

    const dots = ref()
    const totpLooper = ref()
    const otpSpanTag = ref()
    const autoCloseTimeout = ref(null)

    watch(
        () => props.icon,
        (val) => {
            if (val != undefined) {
                otpauthParams.value.icon = val
            }
        }
    )

    /***
     * 
     */
    const show = async (accountId) => {
        revealPassword.value = false

        // 3 possible cases :
        //
        //   Case 1 : When user asks for an otp of an existing account: the ID is provided so we fetch the account data
        //     from db but without the uri. This prevent the uri (a sensitive data) to transit via http request unnecessarily.
        //     In this case this.otp_type is sent by the backend.
        //
        //   Case 2 : When user uses the Quick Uploader and preview the account: No ID but we have an URI.
        //
        //   Case 3 : When user uses the Advanced form and preview the account: We should have all OTP parameter
        //     to obtain an otp, including Secret and otp_type which are required

        otpauthParams.value.otp_type = props.otp_type
        otpauthParams.value.account = props.account
        otpauthParams.value.service = props.service
        otpauthParams.value.icon = props.icon
        otpauthParams.value.secret = props.secret
        otpauthParams.value.digits = props.digits
        otpauthParams.value.algorithm = props.algorithm
        otpauthParams.value.period = props.period
        otpauthParams.value.counter = props.counter
        setLoadingState()

        // Case 1
        if (accountId) {
            id.value = accountId
            const { data } = await twofaccountService.get(id.value)

            otpauthParams.value.service = data.service
            otpauthParams.value.account = data.account
            otpauthParams.value.icon = data.icon
            otpauthParams.value.otp_type = data.otp_type

            if( isHMacBased(data.otp_type) && data.counter ) {
                otpauthParams.value.counter = data.counter
            }
        }
        // Case 2
        else if(props.uri) {
            uri.value = props.uri
            otpauthParams.value.otp_type = props.uri.slice(0, 15 ).toLowerCase() === "otpauth://totp/" ? 'totp' : 'hotp'
        }
        // Case 3
        else if (! props.secret) {
            notify.error(new Error(trans('errors.cannot_create_otp_without_secret')))
        }
        else if (! isTimeBased(otpauthParams.value.otp_type) && ! isHMacBased(otpauthParams.value.otp_type)) {
            notify.error(new Error(trans('errors.not_a_supported_otp_type')))
        }

        try {
            await getOtp()
            focusOnOTP()

            if (user.preferences.getOtpOnRequest && parseInt(user.preferences.autoCloseTimeout) > 0) {
                startAutoCloseTimer()
            }
        }
        catch(error) {
            clearOTP()
        }
    }

    /**
     * Requests and handles a fresh OTP
     */
    async function getOtp() {
        setLoadingState()

        await getOtpPromise().then(response => {
            let otp = response.data
            password.value = otp.password

            if(user.preferences.copyOtpOnDisplay) {
                copyOTP(otp.password)
            }

            if (isTimeBased(otp.otp_type)) {
                generated_at.value = otp.generated_at
                otpauthParams.value.period = otp.period
                hasTOTP.value = true

                nextTick().then(() => {
                    totpLooper.value.startLoop()
                })
            }
            else if (isHMacBased(otp.otp_type)) {
                otpauthParams.value.counter = otp.counter

                // returned counter & uri are incremented
                emit('increment-hotp', { nextHotpCounter: otp.counter, nextUri: otp.uri })
            }
        })
        .catch(error => {
            if (error.response.status === 422) {
                emit('validation-error', error.response)
            }
            //throw error
        })
        .finally(() => {
            showInlineSpinner.value = false
        })
    }

    /**
     * Shows blacked dots and a loading spinner
     */
    function setLoadingState() {
        showInlineSpinner.value = true
        dots.value.turnOff()
    }

    /**
     * Returns the appropriate promise to get a fresh OTP from backend
     */
    function getOtpPromise() {
        if(id.value) {
            return twofaccountService.getOtpById(id.value)
        }
        else if(uri.value) {
            return twofaccountService.getOtpByUri(uri.value)
        }
        else {
            return twofaccountService.getOtpByParams(otpauthParams.value)
        }
    }

    /**
     * Triggers the component closing
     */
    function closeMe() {
        emit("please-close-me");
        revealPassword.value = false
        clearOTP()
    }

    /**
     * Reset component's refs
     */
    function clearOTP() {
        id.value = otpauthParams.value.counter = generated_at.value = null
        otpauthParams.value.service = otpauthParams.value.account = otpauthParams.value.icon = otpauthParams.value.otp_type = otpauthParams.value.secret = ''
        password.value = '... ...'
        hasTOTP.value = false
        clearTimeout(autoCloseTimeout.value)

        totpLooper.value?.clearLooper();
    }

    /**
     * Put focus on the OTP html tag
     */
    function focusOnOTP() {
        nextTick().then(() => {
            otpSpanTag.value?.focus()
        })
    }

    /**
     * Copies to clipboard and notify
     * 
     * @param {string} otp The password to copy
     * @param {*} permit_closing Toggle moddle closing On-Off
     */
    function copyOTP(otp, permit_closing) {
        copy(otp.replace(/ /g, ''))

        if (copied) {
            if(user.preferences.kickUserAfter == -1 && (permit_closing || false) === true && route.name != 'importAccounts') {
                user.logout({ kicked: true})
            }
            else if(user.preferences.closeOtpOnCopy && (permit_closing || false) === true) {
                closeMe()
            }

            if(user.preferences.clearSearchOnCopy) {
                emit("please-clear-search");
            }
            if (user.preferences.viewDefaultGroupOnCopy) {
                user.preferences.activeGroup = user.preferences.defaultGroup == -1 ?
                    user.preferences.activeGroup
                    : user.preferences.defaultGroup
            }

            notify.success({ text: trans('commons.copied_to_clipboard') })
        }
    }

    /**
     * Checks OTP type is Time based (TOTP)
     * 
     * @param {string} otp_type 
     */
    function isTimeBased(otp_type) {
        return (otp_type === 'totp' || otp_type === 'steamtotp')
    }

    /**
     * Checks OTP type is HMAC based (HOTP)
     * 
     * @param {string} otp_type 
     */
    function isHMacBased(otp_type) {
        return otp_type === 'hotp'
    }
    
    /**
     * Turns dots On from the first one to the provided one
     */
    function turnDotOn(dotIndex) {
        dots.value.turnOn(dotIndex)
    }

    defineExpose({
        show,
        clearOTP
    })
    
    /**
     * Starts an auto close timer
     */
    function startAutoCloseTimer() {
        let duration = parseInt(user.preferences.autoCloseTimeout) // in minutes
        
        autoCloseTimeout.value = setTimeout(function() {
            closeMe()
        }, duration * 60 * 1000);
    }

</script>

<template>
    <div>
        <figure class="image is-64x64" :class="{ 'no-icon': !otpauthParams.icon }" style="display: inline-block">
            <img :src="$2fauth.config.subdirectory + '/storage/icons/' + otpauthParams.icon" v-if="otpauthParams.icon" :alt="$t('twofaccounts.icon_to_illustrate_the_account')">
        </figure>
        <UseColorMode v-slot="{ mode }">
            <p class="is-size-4 has-ellipsis" :class="mode == 'dark' ? 'has-text-grey-light' : 'has-text-grey'">{{ otpauthParams.service }}</p>
            <p class="is-size-6 has-ellipsis" :class="mode == 'dark' ? 'has-text-grey' : 'has-text-grey-light'">{{ otpauthParams.account }}</p>
            <p>
                <span
                    v-if="!showInlineSpinner"
                    id="otp"
                    role="log"
                    ref="otpSpanTag"
                    tabindex="0"
                    class="otp is-size-1 is-clickable px-3"
                    :class="mode == 'dark' ? 'has-text-white' : 'has-text-grey-dark'"
                    @click="copyOTP(password, true)"
                    @keyup.enter="copyOTP(password, true)"
                    :title="$t('commons.copy_to_clipboard')"
                >
                    {{ useDisplayablePassword(password, user.preferences.showOtpAsDot && user.preferences.revealDottedOTP && revealPassword) }}
                </span>
                <span v-else tabindex="0" class="otp is-size-1">
                    <Spinner :isVisible="showInlineSpinner" :type="'raw'" />
                </span>
            </p>
        </UseColorMode>
        <Dots v-show="isTimeBased(otpauthParams.otp_type)" ref="dots"></Dots>
        <p v-show="isHMacBased(otpauthParams.otp_type)">
            {{ $t('twofaccounts.forms.counter.label') }}: {{ otpauthParams.counter }}
        </p>
        <p v-if="user.preferences.showOtpAsDot && user.preferences.revealDottedOTP" class="mt-3">
            <button class="button is-ghost has-text-grey-dark" @click.stop="revealPassword = !revealPassword">
                <font-awesome-icon v-if="revealPassword" :icon="['fas', 'eye']" />
                <font-awesome-icon v-else :icon="['fas', 'eye-slash']" />
            </button>
        </p>
        <TotpLooper 
            v-if="hasTOTP"
            :period="otpauthParams.period" 
            :generated_at="generated_at" 
            :autostart="false" 
            v-on:loop-ended="getOtp()"
            v-on:loop-started="turnDotOn($event)"
            v-on:stepped-up="turnDotOn($event)"
            ref="totpLooper"
        ></TotpLooper>
    </div>
</template>
