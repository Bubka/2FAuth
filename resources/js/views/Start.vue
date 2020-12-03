<template>
    <!-- static landing UI -->
    <div class="container has-text-centered">
        <div class="columns quick-uploader">
            <!-- trailer phrase that invite to add an account -->
            <div class="column is-full quick-uploader-header" :class="{ 'is-invisible' : accountCount !== 0 }">
                {{ $t('twofaccounts.no_account_here') }}<br>
                {{ $t('twofaccounts.add_first_account') }}
            </div>
            <!-- Livescan button -->
            <div class="column is-full quick-uploader-button" >
                <div class="quick-uploader-centerer">
                    <!-- upload a qr code (with basic file field and backend decoding) -->
                    <label v-if="$root.appSettings.useBasicQrcodeReader" class="button is-link is-medium is-rounded is-focused" ref="qrcodeInputLabel">
                        <input class="file-input" type="file" accept="image/*" v-on:change="submitQrCode" ref="qrcodeInput">
                        {{ $t('twofaccounts.forms.upload_qrcode') }}
                    </label>
                    <!-- scan button that launch camera stream -->
                    <label v-else class="button is-link is-medium is-rounded is-focused" @click="capture()">
                        {{ $t('twofaccounts.forms.scan_qrcode') }}
                    </label>
                </div>
            </div>
            <!-- alternative methods -->
            <div class="column is-full">
                <div class="block has-text-light">{{ $t('twofaccounts.forms.alternative_methods') }}</div>
                <!-- upload a qr code -->
                <div class="block has-text-link" v-if="!$root.appSettings.useBasicQrcodeReader">
                    <label class="button is-link is-outlined is-rounded" ref="qrcodeInputLabel">
                        <input class="file-input" type="file" accept="image/*" v-on:change="submitQrCode" ref="qrcodeInput">
                        {{ $t('twofaccounts.forms.upload_qrcode') }}
                    </label>
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
        <vue-footer :showButtons="true" >
            <!-- back button -->
            <p class="control" v-if="accountCount > 0">
                <router-link class="button is-dark is-rounded" :to="{ name: 'accounts' }" >
                    {{ $t('commons.back') }}
                </router-link>
            </p>
        </vue-footer>
    </div>
</template>

<script>

    /**
     *  Start view
     *  
     *  route: '/start'
     *  
     *  Offer the user all available possibilities for capturing an account :
     *  - By sending the user to the live scanner
     *  - By decoding a QR code submitted with a form 'File' field
     *  - By sending the user to the advanced form
     *
     */

    import Form from './../components/Form'

    export default {
        name: 'Start',

        data(){
            return {
                accountCount: null,
                form: new Form(),
            }
        },

        mounted() {

            this.axios.get('api/twofaccounts/count').then(response => {
                this.accountCount = response.data.count
            })
        },

        created() {

            this.$nextTick(() => {
                if( this.$root.appSettings.useDirectCapture && this.$root.appSettings.defaultCaptureMode === 'upload' ) {
                    this.$refs.qrcodeInputLabel.click()
                }
            })
        },

        methods: {

            /**
             * Send the submitted QR code to the backend for decoding then push ser to the create form
             */
            async submitQrCode() {

                let imgdata = new FormData();
                imgdata.append('qrcode', this.$refs.qrcodeInput.files[0]);
                imgdata.append('inputFormat', 'fileUpload');

                const { data } = await this.form.upload('/api/qrcode/decode', imgdata)

                this.$router.push({ name: 'createAccount', params: { decodedUri: data.uri } });
            },

            /**
             * Push user to the dedicated capture view for live scan
             */
            capture() {
                this.$router.push({ name: 'capture' });
            },

        }
    };

</script>