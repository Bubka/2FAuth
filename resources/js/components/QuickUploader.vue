<template>
    <div id="quick-uploader">
        <!-- static landing UI -->
        <div v-show="!(showStream && canStream)" class="container has-text-centered">
            <div class="columns quick-uploader">
                <!-- trailer phrase that invite to add an account -->
                <div class="column is-full quick-uploader-header" :class="{ 'is-invisible' : !showTrailer }">
                    {{ $t('twofaccounts.no_account_here') }}<br>
                    {{ $t('twofaccounts.add_first_account') }}
                </div>
                <!-- add button -->
                <div class="column is-full quick-uploader-button" >
                    <div class="quick-uploader-centerer">
                        <!-- scan button that launch camera stream -->
                        <label v-if="canStream" class="button is-link is-medium is-rounded is-focused" @click="enableStream()">
                            {{ $t('twofaccounts.forms.scan_qrcode') }}
                        </label>
                        <!-- or classic input field -->
                        <form v-else @submit.prevent="createAccount" @keydown="form.onKeydown($event)">
                            <label :class="{'is-loading' : form.isBusy}" class="button is-link is-medium is-rounded is-focused">
                                <input v-if="$root.appSettings.useBasicQrcodeReader" class="file-input" type="file" accept="image/*" v-on:change="uploadQrcode" ref="qrcodeInput">
                                <qrcode-capture v-else @decode="uploadQrcode" class="file-input" ref="qrcodeInput" />
                                {{ $t('twofaccounts.forms.use_qrcode.val') }}
                            </label>
                            <field-error :form="form" field="qrcode" />
                            <field-error :form="form" field="uri" />
                        </form>
                    </div>
                </div>
                <!-- Fallback link to classic form -->
                <div class="column is-full quick-uploader-footer">
                    <router-link :to="{ name: 'create' }" class="is-link">{{ $t('twofaccounts.use_full_form') }}</router-link>
                </div>
                <div v-if="showError" class="column is-full quick-uploader-footer">
                    <notification :message="errorText" :isDeletable="false" type="is-danger" />
                </div>
            </div>
        </div>
        <!-- camera stream fullscreen scanner -->
        <div v-show="showStream && canStream">
            <div class="fullscreen-alert has-text-centered">
                <span class="is-size-4 has-text-light">
                    <font-awesome-icon :icon="['fas', 'spinner']" size="2x" spin />
                </span>
            </div>
            <div class="fullscreen-streamer">
                <qrcode-stream @decode="uploadQrcode" @init="onStreamerInit" :camera="camera" />
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

    import Form from './Form'

    export default {
        name: 'QuickUploader',

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

        computed: {

            debugMode: function() {
                return process.env.NODE_ENV
            },

            showError: function() {
                return this.debugMode == 'development' && this.errorName == 'NotAllowedError'
            },
        },

        props: {
            showTrailer: {
                type: Boolean,
                default: false
            },

            directStreaming: {
                type: Boolean,
                default: true
            },
        },

        created() {
            if( this.$root.appSettings.useBasicQrcodeReader ) {
                // User has set the basic QR code reader so we disable the modern one
                this.canStream = this.showStream = false
            }
            else {
                if( this.directStreaming ) {
                    this.enableStream()
                }
            }
        },

        beforeDestroy() {
            this.form.clear()
        },

        methods: {

            async enableStream() {

                this.$parent.$emit('initStreaming')

                this.camera = 'auto'
                this.showStream = true

                console.log('stream enabled')
            },

            async disableStream() {

                this.camera = 'off'
                this.showStream = false

                this.$parent.$emit('stopStreaming')
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

                this.setUploader()
            },

            setUploader() {

                if( this.errorName ) {
                    this.canStream = false
                    console.log(this.errorText)
                }

                if( !this.errorName && !this.showStream ) {
                    this.camera = 'off'
                }

                if( this.canStream && this.showStream) {
                    this.$parent.$emit('startStreaming')
                }
            },

            async uploadQrcode(event) {

                var response

                if(this.$root.appSettings.useBasicQrcodeReader) {
                    let imgdata = new FormData();
                    imgdata.append('qrcode', this.$refs.qrcodeInput.files[0]);

                    response = await this.form.upload('/api/qrcode/decode', imgdata)
                }
                else {
                    // We post the decoded URI instead of an image to decode
                    this.form.uri = event

                    if( !this.form.uri ) {
                        return false
                    }

                    response = await this.form.post('/api/qrcode/decode')
                }

                this.$router.push({ name: 'create', params: { qrAccount: response.data } });

            },

        }
    };

</script>