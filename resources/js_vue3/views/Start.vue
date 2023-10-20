<script setup>
    import Form from '@/components/formElements/Form'
    import { useUserStore } from '@/stores/user'
    import { useBusStore } from '@/stores/bus'
    import { useNotifyStore } from '@/stores/notify'
    import { UseColorMode } from '@vueuse/components'
    import { useTwofaccounts } from '@/stores/twofaccounts'

    const router = useRouter()
    const user = useUserStore()
    const bus = useBusStore()
    const notify = useNotifyStore()
    const twofaccounts = useTwofaccounts()

    const qrcodeInput = ref(null)
    const qrcodeInputLabel = ref(null)
    const form = reactive(new Form({
        qrcode: null,
        inputFormat: 'fileUpload',
    }))
    

    /**
     * Upload the submitted QR code file to the backend for decoding, then route the user
     * to the Create or Import form with decoded URI to prefill the form
     */
    function submitQrCode() {
        form.qrcode = qrcodeInput.value.files[0]

        form.upload('/api/v1/qrcode/decode', { returnError: true }).then(response => {
            console.log(response.data)
            if (response.data.data.slice(0, 33).toLowerCase() === "otpauth-migration://offline?data=") {
                bus.migrationUri = response.data.data
                router.push({ name: 'importAccounts' })
            }
            else {
                bus.decodedUri = response.data.data
                router.push({ name: 'createAccount' })
            }
        })
        .catch(error => {
            notify.alert({ text: trans(error.response.data.message) })
        })
    }

    /**
     * Push user to the dedicated capture view for live scan
     */
    function capture() {
        router.push({ name: 'capture' });
    }

    onMounted(() => {
        if( user.preferences.useDirectCapture && user.preferences.defaultCaptureMode === 'upload' ) {
            qrcodeInputLabel.value.click()
        }
    })
</script>

<template>
    <!-- static landing UI -->
    <div class="container has-text-centered">
        <div class="columns quick-uploader">
            <!-- trailer phrase that invite to add an account -->
            <div class="column is-full quick-uploader-header" :class="{ 'is-invisible' : twofaccounts.count !== 0 }">
                {{ $t('twofaccounts.no_account_here') }}<br>
                {{ $t('twofaccounts.add_first_account') }}
            </div>
            <!-- Livescan button -->
            <div class="column is-full quick-uploader-button" >
                <div class="quick-uploader-centerer">
                    <!-- upload a qr code (with basic file field and backend decoding) -->
                    <label role="button" tabindex="0" v-if="user.preferences.useBasicQrcodeReader" class="button is-link is-medium is-rounded is-main" ref="qrcodeInputLabel" @keyup.enter="qrcodeInputLabel.click()">
                        <input aria-hidden="true" tabindex="-1" class="file-input" type="file" accept="image/*" v-on:change="submitQrCode" ref="qrcodeInput">
                        {{ $t('twofaccounts.forms.upload_qrcode') }}
                    </label>
                    <!-- scan button that launch camera stream -->
                    <button v-else class="button is-link is-medium is-rounded is-main" @click="capture()">
                        {{ $t('twofaccounts.forms.scan_qrcode') }}
                    </button>
                    <FieldError v-if="form.hasAny" :error="form.errors.get('qrcode')" :field="'qrcode'" />
                </div>
            </div>
            <!-- alternative methods -->
            <div class="column is-full">
                <UseColorMode v-slot="{ mode }">
                    <div class="block" :class="mode == 'dark' ? 'has-text-light':'has-text-grey-dark'">{{ $t('twofaccounts.forms.alternative_methods') }}</div>
                </UseColorMode>
                <!-- upload a qr code -->
                <div class="block has-text-link" v-if="!user.preferences.useBasicQrcodeReader">
                    <label role="button" tabindex="0" class="button is-link is-outlined is-rounded" ref="qrcodeInputLabel" @keyup.enter="qrcodeInputLabel.click()">
                        <input aria-hidden="true" tabindex="-1" class="file-input" type="file" accept="image/*" v-on:change="submitQrCode" ref="qrcodeInput">
                        {{ $t('twofaccounts.forms.upload_qrcode') }}
                    </label>
                </div>
                <!-- link to advanced form -->
                <div class="block has-text-link">
                    <RouterLink class="button is-link is-outlined is-rounded" :to="{ name: 'createAccount' }" >
                        {{ $t('twofaccounts.forms.use_advanced_form') }}
                    </RouterLink>
                </div>
                <!-- link to import view -->
                <div class="block has-text-link">
                    <RouterLink id="btnImport" class="button is-link is-outlined is-rounded" :to="{ name: 'importAccounts' }" >
                        {{ $t('twofaccounts.import.import') }}
                    </RouterLink>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <VueFooter :showButtons="true" >
            <!-- back button -->
            <p class="control" v-if="twofaccounts.length > 0">
                <UseColorMode v-slot="{ mode }">
                    <RouterLink id="lnkBack" class="button is-rounded" :class="{'is-dark' : mode == 'dark'}" :to="{ name: 'accounts' }" >
                        {{ $t('commons.back') }}
                    </RouterLink>
                </UseColorMode>
            </p>
        </VueFooter>
    </div>
</template>
