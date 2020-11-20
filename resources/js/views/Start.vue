<template>
    <div id="quick-uploader">
        <!-- static landing UI -->
        <div v-show="!(showStream && canStream)" class="container has-text-centered">
            <div class="columns quick-uploader">
                <!-- trailer phrase that invite to add an account -->
                <div class="column is-full quick-uploader-header" :class="{ 'is-invisible' : accountCount > 0 }">
                    {{ $t('twofaccounts.no_account_here') }}<br>
                    {{ $t('twofaccounts.add_first_account') }}
                </div>
                <!-- Livescan button -->
                <div class="column is-full quick-uploader-button" >
                    <div class="quick-uploader-centerer">
                        <!-- upload a qr code (with basic file field and backend decoding) -->
                        <label v-if="$root.appSettings.useBasicQrcodeReader" class="button is-link is-medium is-rounded is-focused" >
                            <input class="file-input" type="file" accept="image/*" v-on:change="submitQrCode" ref="qrcodeInput">
                            {{ $t('twofaccounts.forms.upload_qrcode') }}
                        </label>
                        <!-- scan button that launch camera stream -->
                        <label v-else class="button is-link is-medium is-rounded is-focused" @click="enableStream()">
                            {{ $t('twofaccounts.forms.scan_qrcode') }}
                        </label>
                    </div>
                </div>
                <!-- alternative methods -->
                <div class="column is-full">
                    <div class="block has-text-light">Alternative methods</div>
                    <!-- upload a qr code (with qrcode-capture component) -->
                    <div class="block has-text-link" v-if="!$root.appSettings.useBasicQrcodeReader">
                        <form @submit.prevent="createAccount" @keydown="form.onKeydown($event)">
                            <label :class="{'is-loading' : form.isBusy}" class="button is-link is-outlined is-rounded">
                                <qrcode-capture @decode="submitUri" class="file-input" ref="qrcodeInput" />
                                {{ $t('twofaccounts.forms.upload_qrcode') }}
                            </label>
                            <field-error :form="form" field="qrcode" />
                            <field-error :form="form" field="uri" />
                        </form>
                    </div>
                    <!-- link to advanced form -->
                    <div class="block has-text-link">
                        <router-link class="button is-link is-outlined is-rounded" :to="{ name: 'createAccount' }" >
                            {{ $t('twofaccounts.forms.use_advanced_form') }}
                        </router-link>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <vue-footer :showButtons="true">
                <!-- back button -->
                <p class="control">
                    <router-link class="button is-dark is-rounded" :to="{ name: '/' }" >
                        {{ $t('commons.back') }}
                    </router-link>
                </p>
            </vue-footer>
        </div>
        <!-- camera stream fullscreen scanner -->
        <div v-show="showStream && canStream">
            <div class="fullscreen-alert has-text-centered">
                <span class="is-size-4 has-text-light">
                    <font-awesome-icon :icon="['fas', 'spinner']" size="2x" spin />
                </span>
            </div>
            <div class="fullscreen-streamer">
                <qrcode-stream @decode="submitUri" @init="onStreamerInit" :camera="camera" />
            </div>
            <div class="fullscreen-footer">
                <!-- Cancel button -->
                <label class="button is-large is-warning is-rounded" @click="disableStream()">
                    {{ $t('commons.cancel') }}
                </label>
            </div>
        </div>
    </div>
</template>

<script>

    /**
     *  Start view
     *  
     *  route: '/start'
     *  
     *  Offer the user all available possibilities for capturing an account :
     *  - By decoding a QR code acquired by live scan
     *  - By decoding a QR code submitted with a form 'File' field
     *  - By browsing to the advanced form
     *
     *  2 QR code decoders are implemented :
     *  - vue-qrcode-reader  (the default one)
     *    ~ QR codes are acquired by live scan or with a 'File' field by the js front-end
     *    ~ Decoding is done by the js front-end, there is no call to the back-end API
     *  - chillerlan/php-qrcode  (aka 'BasicQrcodeReader')
     *    ~ QR codes are acquired with a 'File' field and uploaded to the php backend
     *    ~ Decoding is done by the php back-end
     *
     *  Output : both decoders provide an URI and push it the Create form
     * 
     *  The view behaviour is affected by the user options :
     *  - 'appSettings.useBasicQrcodeReader' totally disable the vue-qrcode-reader decoder
     *  - 'appSettings.useDirectCapture' trigger the acquisition mode set by 'appSettings.defaultCaptureMode' automatically at vue @created event
     *
     */

    import Form from './../components/Form'

    export default {
        name: 'Start',

        data(){
            return {
                form: new Form({
                    qrcode: null,
                    uri: '',
                }),
                errorName: '',
                errorText: '',
                showStream: false,
                canStream: true,
                camera: 'auto',
            }
        },

        props: ['accountCount'],

        created() {
            if( this.$root.appSettings.useBasicQrcodeReader ) {
                // User has set the basic QR code reader (run by backend) so we disable the other one (run by js)
                this.canStream = this.showStream = false
            }
            else {
                if( this.accountCount > 0 && this.$root.appSettings.useDirectCapture ) {
                    this.enableStream()
                }
            }
        },

        beforeDestroy() {
            this.form.clear()
        },

        methods: {

            async enableStream() {

                this.setUploader()

                this.camera = 'auto'
                this.showStream = true

                console.log('stream enabled')
            },

            async disableStream() {

                this.camera = 'off'
                this.showStream = false

                this.$parent.$emit('stopStreaming')

                console.log('stream disabled')
            },

            async onStreamerInit (promise) {

                this.errorText = ''
                this.errorName = ''

                try {
                    await promise
                }
                catch (error) {

                    this.errorName = error.name

                    if (error.name === 'NotAllowedError') {
                        this.errorText = this.$t('twofaccounts.stream.need_grant_permission')

                    } else if (error.name === 'NotReadableError') {
                        this.errorText = this.$t('twofaccounts.stream.not_readable')

                    } else if (error.name === 'NotFoundError') {
                        this.errorText = this.$t('twofaccounts.stream.no_cam_on_device')

                    } else if (error.name === 'NotSupportedError' || error.name === 'InsecureContextError') {
                        this.errorText = this.$t('twofaccounts.stream.secured_context_required')

                    } else if (error.name === 'OverconstrainedError') {
                        this.errorText = this.$t('twofaccounts.stream.camera_not_suitable')

                    } else if (error.name === 'StreamApiNotSupportedError') {
                        this.errorText = this.$t('twofaccounts.stream.stream_api_not_supported')
                    }
                }
            },

            setUploader() {

                if( this.errorName ) {
                    console.log('fail to stream : ' + this.errorText)

                    this.canStream = false
                    this.$notify({ type: 'is-warning', text: this.errorText })
                }
                else
                {
                    if( !this.errorName && !this.showStream ) {
                        this.camera = 'off'

                        console.log('stream stopped')
                    }

                    console.log('stream started')
                }

            },


            /**
             * the basicQRcodeReader option is On, so qrcode decoding has to be done by the backend, which in return
             * send the corresponding URI
             */
            async submitQrCode() {

                let imgdata = new FormData();
                imgdata.append('qrcode', this.$refs.qrcodeInput.files[0]);
                imgdata.append('inputFormat', 'fileUpload');

                const { data } = await this.form.upload('/api/qrcode/decode', imgdata)

                this.pushUriToCreateForm(data.uri)
            },


            /**
             * The basicQRcodeReader option is Off, so qrcode decoding has already be done by vue-qrcode-reader, whether
             * from livescan or file input.
             * We simply check the uri validity to prevent useless push to the Create form, but the form will check uri validity too.
             */
            async submitUri(event) {
                // We post the URI automatically decoded by vue-qrcode-reader
                this.form.uri = event

                if( !this.form.uri ) {
                    this.$notify({type: 'is-warning', text: this.$t('errors.qrcode_cannot_be_read') })
                }
                else if( this.form.uri.slice(0, 15 ).toLowerCase() !== "otpauth://totp/" && this.form.uri.slice(0, 15 ).toLowerCase() !== "otpauth://hotp/" ) {
                    this.$notify({type: 'is-warning', text: this.$t('errors.no_valid_otp') })
                }
                else {
                    this.pushUriToCreateForm(this.form.uri)
                }
            },

            pushUriToCreateForm(data) {
                this.$router.push({ name: 'createAccount', params: { decodedUri: data } });
            }

        }
    };

</script>