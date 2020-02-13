<template>
    <!-- Quick form -->
    <form @submit.prevent="createAccount" @keydown="form.onKeydown($event)" v-if="isQuickForm">
        <div class="container preview has-text-centered">
            <div class="columns is-mobile">
                <div class="column">
                    <label class="add-icon-button" v-if="!tempIcon">
                        <input class="file-input" type="file" accept="image/*" v-on:change="uploadIcon" ref="iconInput">
                        <font-awesome-icon :icon="['fas', 'image']" size="2x" />
                    </label>
                    <button class="delete delete-icon-button is-medium" v-if="tempIcon" @click.prevent="deleteIcon"></button>
                    <twofaccount-show ref="TwofaccountShow"
                        :service="form.service"
                        :account="form.account"
                        :uri="form.uri">
                    </twofaccount-show>
                </div>
            </div>
            <div class="columns is-mobile">
                <div class="column quickform-footer">
                    <div class="field is-grouped is-grouped-centered">
                        <div class="control">
                            <v-button :isLoading="form.isBusy" >{{ $t('twofaccounts.forms.save') }}</v-button>
                        </div>
                        <div class="control">
                            <button type="button" class="button is-text" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Full form -->
    <form-wrapper :title="$t('twofaccounts.forms.new_account')" v-else>
        <form @submit.prevent="createAccount" @keydown="form.onKeydown($event)">
            <div class="field">
                <div class="file is-dark is-boxed">
                    <label class="file-label" :title="$t('twofaccounts.forms.use_qrcode.title')">
                        <input class="file-input" type="file" accept="image/*" v-on:change="uploadQrcode" ref="qrcodeInput">
                        <span class="file-cta">
                            <span class="file-icon">
                                <font-awesome-icon :icon="['fas', 'qrcode']" size="lg" />
                            </span>
                            <span class="file-label">{{ $t('twofaccounts.forms.use_qrcode.val') }}</span>
                        </span>
                    </label>
                </div>
            </div>
            <field-error :form="form" field="qrcode" class="help-for-file" />
            <form-field :form="form" fieldName="service" inputType="text" :label="$t('twofaccounts.service')" :placeholder="$t('twofaccounts.forms.service.placeholder')" autofocus />
            <form-field :form="form" fieldName="account" inputType="text" :label="$t('twofaccounts.account')" :placeholder="$t('twofaccounts.forms.account.placeholder')" />
            <div class="field" style="margin-bottom: 0.5rem;">
                <label class="label">{{ $t('twofaccounts.forms.otp_uri') }}</label>
            </div>
            <div class="field has-addons">
                <div class="control is-expanded">
                    <input class="input" type="text" placeholder="otpauth://totp/..." v-model="form.uri" :disabled="uriIsLocked" />
                </div>
                <div class="control" v-if="uriIsLocked">
                    <a class="button is-dark field-lock" @click="uriIsLocked = false" :title="$t('twofaccounts.forms.unlock.title')">
                        <span class="icon">
                            <font-awesome-icon :icon="['fas', 'lock']" />
                        </span>
                    </a>
                </div>
                <div class="control" v-else>
                    <a class="button is-dark field-unlock"  @click="uriIsLocked = true" :title="$t('twofaccounts.forms.lock.title')">
                        <span class="icon has-text-danger">
                            <font-awesome-icon :icon="['fas', 'lock-open']" />
                        </span>
                    </a>
                </div>
            </div>
            <field-error :form="form" field="uri" class="help-for-file" />
            <div class="field">
                <label class="label">{{ $t('twofaccounts.icon') }}</label>
                <div class="file is-dark">
                    <label class="file-label">
                        <input class="file-input" type="file" accept="image/*" v-on:change="uploadIcon" ref="iconInput">
                        <span class="file-cta">
                            <span class="file-icon">
                                <font-awesome-icon :icon="['fas', 'image']" />
                            </span>
                            <span class="file-label">{{ $t('twofaccounts.forms.choose_image') }}</span>
                        </span>
                    </label>
                    <span class="tag is-black is-large" v-if="tempIcon">
                        <img class="icon-preview" :src="'/storage/icons/' + tempIcon" >
                        <button class="delete is-small" @click.prevent="deleteIcon"></button>
                    </span>
                </div>
            </div>
            <field-error :form="form" field="icon" class="help-for-file" />
            <div class="field is-grouped">
                <div class="control">
                    <v-button :isLoading="form.isBusy" >{{ $t('twofaccounts.forms.create') }}</v-button>
                </div>
                <div class="control" v-if="form.uri">
                    <button type="button" class="button is-success" @click="previewAccount">{{ $t('twofaccounts.forms.test') }}</button>
                </div>
                <div class="control">
                    <button type="button" class="button is-text" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
                </div>
            </div>
        </form>
        <!-- modal -->
        <modal v-model="ShowTwofaccountInModal">
            <twofaccount-show ref="TwofaccountPreview" 
                :service="form.service"
                :account="form.account"
                :uri="form.uri"
                :icon="tempIcon">
            </twofaccount-show>
        </modal>
    </form-wrapper>
</template>

<script>

    import Modal from '../../components/Modal'
    import Form from './../../components/Form'
    import TwofaccountShow from '../../components/TwofaccountShow'

    export default {
        data() {
            return {
                isQuickForm: false,
                ShowTwofaccountInModal : false,
                uriIsLocked: true,
                tempIcon: '',
                form: new Form({
                    service: '',
                    account: '',
                    uri: '',
                    icon: '',
                    qrcode: null
                })
            }
        },

        watch: {
            tempIcon: function(val) {
                if( this.isQuickForm ) {
                    this.$refs.TwofaccountShow.internal_icon = val
                }
            },
        },

        mounted: function () {
            if( this.$route.params.qrAccount ) {

                this.isQuickForm = true
                this.form.fill(this.$route.params.qrAccount)

            }

            // stop TOTP generation on modal close
            this.$on('modalClose', function() {
                this.$refs.TwofaccountPreview.stopLoop()
            });
        },

        components: {
            Modal,
            TwofaccountShow,
        },

        methods: {

            async createAccount() {
                // set current temp icon as account icon
                this.form.icon = this.tempIcon

                // The quick form (possibly the preview feature too) has incremented the HOTP counter so the next_uri property
                // must be used as the uri to store
                // This could desynchronized the HOTP verification server and our local counter if the user never verified the HOTP but this
                // is acceptable (and HOTP counter can be edited by the way)
                if( this.isQuickForm && this.$refs.TwofaccountShow.next_uri ) {
                    this.form.uri = this.$refs.TwofaccountShow.next_uri
                }
                else if( this.$refs.TwofaccountPreview && this.$refs.TwofaccountPreview.next_uri ) {
                    this.form.uri = this.$refs.TwofaccountPreview.next_uri
                }

                await this.form.post('/api/twofaccounts')

                if( this.form.errors.any() === false ) {
                    this.$router.push({name: 'accounts', params: { InitialEditMode: false }});
                }

            },

            previewAccount() {
                // preview is possible only if we have an uri
                if( this.form.uri ) {
                    this.$refs.TwofaccountPreview.showAccount()
                }
            },

            cancelCreation: function() {

                if( this.form.service && this.form.uri ) {
                    if( confirm(this.$t('twofaccounts.confirm.cancel')) === false ) {
                        return
                    }
                }

                // clean possible uploaded temp icon
                this.deleteIcon()

                this.$router.push({name: 'accounts', params: { InitialEditMode: false }});
            },

            async uploadQrcode(event) {

                let imgdata = new FormData();
                imgdata.append('qrcode', this.$refs.qrcodeInput.files[0]);

                const { data } = await this.form.upload('/api/qrcode/decode', imgdata)

                this.form.fill(data)

            },

            async uploadIcon(event) {

                // clean possible already uploaded temp icon
                this.deleteIcon()

                let imgdata = new FormData();
                imgdata.append('icon', this.$refs.iconInput.files[0]);

                const { data } = await this.form.upload('/api/icon/upload', imgdata)

                this.tempIcon = data;

            },

            deleteIcon(event) {
                if(this.tempIcon) {
                    this.axios.delete('/api/icon/delete/' + this.tempIcon)
                    this.tempIcon = ''
                }
            },
            
        },

    }
</script>