<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <section class="section">
                <div class="columns is-centered">
                    <div class="column is-three-quarters">
                        <div class="box has-text-centered has-background-black-ter is-shadowless">
                            <div v-if="errorText">
                                <p class="block is-size-5">{{ $t('twofaccounts.stream.live_scan_cant_start') }}</p>
                                <p class="has-text-light block">{{ $t('twofaccounts.stream.' + errorText + '.reason') }}</p>
                                <p class="is-size-7">{{ $t('twofaccounts.stream.' + errorText + '.solution') }}</p>
                            </div>
                            <span v-else class="is-size-4 has-text-light">
                                <font-awesome-icon :icon="['fas', 'spinner']" size="2x" spin />
                            </span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="fullscreen-streamer">
            <qrcode-stream @decode="submitUri" @init="onStreamerInit" camera="auto" />
        </div>
        <div class="fullscreen-footer">
            <!-- Cancel button -->
            <label class="button is-large is-warning is-rounded" @click="exitStream()">
                {{ $t('commons.cancel') }}
            </label>
        </div>
    </div>
</template>

<script>

    import { QrcodeStream } from 'vue-qrcode-reader'
    import Form from './../components/Form'

    export default {
        data(){
            return {
                showStream: true,
                errorText: '',
                form: new Form({
                    qrcode: null,
                    uri: '',
                }),
            }
        },

        components: {
            QrcodeStream,
        },

        methods: {

            exitStream() {

                this.camera = 'off'
                this.$router.go(-1)

            },

            async onStreamerInit (promise) {

                try {
                    await promise
                }
                catch (error) {

                    if (error.name === 'NotAllowedError') {
                        this.errorText = 'need_grant_permission'

                    } else if (error.name === 'NotReadableError') {
                        this.errorText = 'not_readable'

                    } else if (error.name === 'NotFoundError') {
                        this.errorText = 'no_cam_on_device'

                    } else if (error.name === 'NotSupportedError' || error.name === 'InsecureContextError') {
                        this.errorText = 'secured_context_required'

                    } else if (error.name === 'OverconstrainedError') {
                        this.errorText = 'camera_not_suitable'

                    } else if (error.name === 'StreamApiNotSupportedError') {
                        this.errorText = 'stream_api_not_supported'
                    }
                }
            },

            /**
             * The basicQRcodeReader option is Off, so qrcode decoding has already be done by vue-qrcode-reader, whether
             * from livescan or file input.
             * We simply check the uri validity to prevent useless push to the Create form, but the form will check uri validity too.
             */
            async submitUri(event) {
                
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
    }

</script>