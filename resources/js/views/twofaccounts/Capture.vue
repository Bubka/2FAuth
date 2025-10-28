<script setup>
    import Form from '@/components/formElements/Form'
    import QrContentDisplay from '@/components/QrContentDisplay.vue'
    import { useBusStore } from '@/stores/bus'
    import { UseColorMode } from '@vueuse/components'
    import { useNotify } from '@2fauth/ui'
    import { QrcodeStream } from 'vue-qrcode-reader'
    import { useI18n } from 'vue-i18n'
    import { useErrorHandler } from '@2fauth/stores'
    import { LucideLock, LucideVideoOff, LucideStar, LucideCamera } from 'lucide-vue-next'

    const errorHandler = useErrorHandler()
    const { t } = useI18n()
    const router = useRouter()
    const bus = useBusStore()
    const notify = useNotify()

    const cameraIsOn = ref(false)
    const selectedCamera = ref(null)
    const cameras = ref([])
    const errorPhrase = ref('')
    const form = reactive(new Form({
        qrcode: null,
        uri: '',
    }))
    const showQrContent = ref(false)

    onMounted(async () => {
        if (!navigator.mediaDevices?.enumerateDevices) {
            errorPhrase.value  = 'secured_context_required'
        } else {
            await navigator.mediaDevices.enumerateDevices().then((devices) => {
                cameras.value = devices.filter(({ kind }) => kind === 'videoinput')

                if (cameras.value.length > 0) {
                    selectedCamera.value = cameras.value[0]
                }
                else errorPhrase.value  = 'no_cam_on_device'
            })
            .catch((err) => {
                onError(err)
            })
        }
    })

    const onError = error => {
        if (error.name === 'NotAllowedError') {
            errorPhrase.value = 'need_grant_permission'
        } else if (error.name === 'NotFoundError') {
            errorPhrase.value = 'no_cam_on_device'
        } else if (error.name === 'NotSupportedError' || error.name === 'InsecureContextError') {
            errorPhrase.value  = 'secured_context_required'
        } else if (error.name === 'NotReadableError') {
            errorPhrase.value = 'not_readable'
        } else if (error.name === 'OverconstrainedError') {
            errorPhrase.value = 'camera_not_suitable'
        } else if (error.name === 'StreamApiNotSupportedError') {
            errorPhrase.value = 'stream_api_not_supported'
        } else {
            errorHandler.show(error)
        }
    }

    /**
     * Pushes a decoded URI to the Create or Import form
     * 
     * The basicQRcodeReader option is Off, so qrcode decoding has already be done by vue-qrcode-reader, whether
     * from livescan or file input.
     * We simply check the uri validity to prevent useless push to the form, but the form will check uri validity too.
     */
    const onDetect = async (detectedCodes) => {
        const [ firstCode ] = detectedCodes
        form.uri = firstCode.rawValue

        if (! form.uri) {
            notify.warn({ text: t('error.qrcode_cannot_be_read') })
        }
        else if (form.uri.slice(0, 33).toLowerCase() == "otpauth-migration://offline?data=") {
            bus.migrationUri = form.uri
            router.push({ name: 'importAccounts' })
        }
        else if (form.uri.slice(0, 15).toLowerCase() !== "otpauth://totp/" && form.uri.slice(0, 15).toLowerCase() !== "otpauth://hotp/") {
            showQrContent.value = true
            notify.warn({ text: t('error.no_valid_otp') })
        }
        else {
            bus.decodedUri = form.uri
            router.push({ name: 'createAccount' })
        }
    }

    /**
     * Triggered when camera goes On
     */
    function cameraOn(event) {
        cameraIsOn.value = true
    }

    /**
     * Triggered when camera goes Off
     */
    function cameraOff(event) {
        cameraIsOn.value = false
    }

    /**
     * Exits the stream view
     */
    function exitStream() {
        // this.camera = 'off'
        router.go(-1)
    }

    /**
     * Paints the red outline during scan
     */
    const paintOutline = (detectedCodes, ctx) => {
        for (const detectedCode of detectedCodes) {
            const [firstPoint, ...otherPoints] = detectedCode.cornerPoints

            ctx.strokeStyle = 'red'

            ctx.beginPath()
            ctx.moveTo(firstPoint.x, firstPoint.y)
            for (const { x, y } of otherPoints) {
            ctx.lineTo(x, y)
            }
            ctx.lineTo(firstPoint.x, firstPoint.y)
            ctx.closePath()
            ctx.stroke()
        }
    }

    function reloadLocation() {
        location.reload()
    }
</script>

<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <section class="section">
                <div class="columns is-centered">
                    <div class="column is-three-quarters">
                        <div class="modal-slot has-text-centered is-shadowless">
                            <UseColorMode v-slot="{ mode }">
                                <div v-if="errorPhrase">
                                    <p class="block is-size-5">{{ $t('message.stream.live_scan_cant_start') }}</p>
                                    <p class="block" :class="{'has-text-light': mode == 'dark'}">{{ $t('message.stream.' + errorPhrase + '.reason') }}</p>
                                    <div v-if="errorPhrase == 'need_grant_permission'" >
                                        <p class="is-size-7 mb-3">{{ $t('message.stream.need_grant_permission.solution') }}</p>
                                        <p class="is-size-7 mb-3">{{ $t('message.stream.need_grant_permission.click_camera_icon') }}</p>
                                        
                                        <div class="addressbar columns is-mobile is-gapless">
                                            <div class="column is-narrow has-text-left circled">
                                                <LucideLock class="ml-1 inline icon-size-1-15" />
                                                <LucideVideoOff class="ml-3 inline icon-size-1-15" />
                                            </div>
                                            <div class="column has-text-left ml-3">
                                                http://my.2fauth.app/...
                                            </div>
                                            <div class="column is-narrow has-text-right">
                                                <LucideStar class="mr-1 inline icon-size-1-15" />
                                            </div>
                                        </div>
                                        <p>
                                            <a @click.stop="reloadLocation">{{ $t('label.refresh') }}</a>
                                        </p>
                                    </div>
                                    <p v-else class="is-size-7">{{ $t('message.stream.' + errorPhrase + '.solution') }}</p>
                                </div>
                                <span v-else class="is-size-4" :class="mode == 'dark' ? 'has-text-light':'has-text-grey-dark'">
                                    <Spinner :isVisible="true" :type="'raw'" :rawSize="32" />
                                </span>
                            </UseColorMode>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div v-show="!errorPhrase" class="fullscreen-streamer">
            <qrcode-stream
                v-if="selectedCamera !== null"
                :track="paintOutline"
                @detect="onDetect"
                @error="onError"
                @camera-on="cameraOn"
                @camera-off="cameraOff"
            ></qrcode-stream>
            <!-- device selector -->
            <div v-if="cameraIsOn && cameras.length > 1" class="field has-addons has-addons-centered mt-3">
                <p class="control has-icons-left">
                    <span class="select">
                        <select v-model="selectedCamera">
                            <option v-for="camera in cameras" :key="camera.label" :value="camera">
                                {{ camera.label ? camera.label : $t('label.default') }}
                            </option>
                        </select>
                    </span>
                    <span class="icon is-small is-left">
                        <LucideCamera />
                    </span>
                </p>
            </div>
        </div>
        <div class="fullscreen-footer">
            <NavigationButton action="cancel" :isCapture="true" :useLinkTag="false" @canceled="exitStream()" />
        </div>
    </div>
    <Modal v-model="showQrContent">
        <QrContentDisplay :qrContent="form.uri" />
    </Modal>
</template>