<template>
    <div class="section">
        <div class="columns is-mobile is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('twofaccounts.forms.new_account') }}</h1>
                <form @submit.prevent="createAccount">
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
                    <p class="help is-danger help-for-file" v-if="validationErrors.qrcode">{{ validationErrors.qrcode.toString() }}</p>
                    <div class="field">
                        <label class="label">{{ $t('twofaccounts.service') }}</label>
                        <div class="control">
                            <input class="input" type="text" :placeholder="$t('twofaccounts.forms.service.placeholder')" v-model="twofaccount.service"  autofocus />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.service">{{ validationErrors.service.toString() }}</p>
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('twofaccounts.account') }}</label>
                        <div class="control">
                            <input class="input" type="text" :placeholder="$t('twofaccounts.forms.account.placeholder')" v-model="twofaccount.account"  />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.account">{{ validationErrors.account.toString() }}</p>
                    </div>
                    <div class="field" style="margin-bottom: 0.5rem;">
                        <label class="label">{{ $t('twofaccounts.forms.totp_uri') }}</label>
                    </div>
                    <div class="field has-addons">
                        <div class="control is-expanded">
                            <input class="input" type="text" placeholder="otpauth://totp/..." v-model="twofaccount.uri" :disabled="uriIsLocked" />
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
                    <p class="help is-danger help-for-file" v-if="validationErrors.uri">{{ validationErrors.uri.toString() }}</p>
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
                                <img class="icon-preview" :src="'storage/icons/' + tempIcon" >
                                <button class="delete is-small" @click.prevent="deleteIcon"></button>
                            </span>
                        </div>
                    </div>
                    <p class="help is-danger help-for-file" v-if="validationErrors.icon">{{ validationErrors.icon.toString() }}</p>
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-link">{{ $t('twofaccounts.forms.create') }}</button>
                        </div>
                        <div class="control">
                            <button class="button is-text" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                twofaccount: {
                    'service' : '',
                    'account' : '',
                    'uri' : '',
                    'icon' : ''
                },
                uriIsLocked: true,
                tempIcon: '',
                validationErrors: {}
            }
        },

        methods: {

            async createAccount() {
                // set current temp icon as account icon
                this.twofaccount.icon = this.tempIcon

                try {
                    await axios.post('/api/twofaccounts', this.twofaccount)

                    this.$router.push({name: 'accounts', params: { InitialEditMode: false }});
                }
                catch (error) {
                    if( error.response.data.validation ) {
                        this.validationErrors = error.response.data.validation
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response.data.message } });
                    }
                }

            },

            cancelCreation: function() {
                // clean possible uploaded temp icon
                if( this.tempIcon ) {
                    this.deleteIcon()
                }

                this.$router.push({name: 'accounts', params: { InitialEditMode: false }});
            },

            async uploadQrcode(event) {

                let imgdata = new FormData();

                imgdata.append('qrcode', this.$refs.qrcodeInput.files[0]); 

                let config = {
                    header : {
                        'Content-Type' : 'multipart/form-data',
                    }
                }

                try {
                    const { data } = await axios.post('/api/qrcode/decode', imgdata, config)

                    this.twofaccount = data;
                    this.validationErrors['qrcode'] = '';
                }
                catch (error) {
                    if( error.response.data.validation ) {
                        this.validationErrors = error.response.data.validation
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response.data.message } });
                    }
                }

            },

            async uploadIcon(event) {

                // clean possible already uploaded temp icon
                if( this.tempIcon ) {
                    this.deleteIcon()
                }

                let imgdata = new FormData();

                imgdata.append('icon', this.$refs.iconInput.files[0]); 

                let config = {
                    header : {
                        'Content-Type' : 'multipart/form-data',
                    }
                }

                try {
                    const { data } = await axios.post('/api/icon/upload', imgdata, config)

                    this.tempIcon = data;
                    this.validationErrors['icon'] = '';
                }
                catch (error) {
                    if( error.response.data.validation ) {
                        this.validationErrors = error.response.data.validation
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response.data.message } });
                    }
                }

            },

            async deleteIcon(event) {

                if(this.tempIcon) {
                    await axios.delete('/api/icon/delete/' + this.tempIcon)
                    this.tempIcon = ''
                }
            },

            clearTwofaccount() {
                this.twofaccount.service = ''
                this.twofaccount.account = ''
                this.twofaccount.uri = ''
                this.twofaccount.icon = ''

                this.deleteIcon()
            }
            
        },

    }
</script>