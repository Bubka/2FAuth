<template>
    <div class="section">
        <div class="columns is-mobile is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('twofaccounts.forms.new_account') }}</h1>
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
                    <div class="field">
                        <label class="label">{{ $t('twofaccounts.service') }}</label>
                        <div class="control">
                            <input class="input" type="text" :placeholder="$t('twofaccounts.forms.service.placeholder')" v-model="twofaccount.service"  autofocus />
                        </div>
                        <field-error :form="form" field="service" />
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('twofaccounts.account') }}</label>
                        <div class="control">
                            <input class="input" type="text" :placeholder="$t('twofaccounts.forms.account.placeholder')" v-model="twofaccount.account"  />
                        </div>
                        <field-error :form="form" field="account" />
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
                                <img class="icon-preview" :src="'storage/icons/' + tempIcon" >
                                <button class="delete is-small" @click.prevent="deleteIcon"></button>
                            </span>
                        </div>
                    </div>
                    <field-error :form="form" field="icon" class="help-for-file" />
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-link" :disabled="form.busy" >{{ $t('twofaccounts.forms.create') }}</button>
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

    import Form from './../../components/Form'

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
                validationErrors: {},
                form: new Form({
                    service: '',
                    account: '',
                    uri: '',
                    icon: '',
                    qrcode: null
                })
            }
        },

        methods: {

            createAccount() {
                // set current temp icon as account icon
                this.twofaccount.icon = this.tempIcon

                this.form.post('/api/twofaccounts', this.twofaccount)
                .then(response => {
                    this.$router.push({name: 'accounts', params: { InitialEditMode: false }});
                })
                .catch(error => {
                    if( error.response.status == 422 ) {
                        this.validationErrors = error.response.data.errors
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response.data.message } });
                    }
                });

            },

            cancelCreation: function() {
                // clean possible uploaded temp icon
                if( this.tempIcon ) {
                    this.deleteIcon()
                }

                this.$router.push({name: 'accounts', params: { InitialEditMode: false }});
            },

            uploadQrcode(event) {

                let imgdata = new FormData();

                imgdata.append('qrcode', this.$refs.qrcodeInput.files[0]);

                this.form.upload('/api/qrcode/decode', imgdata)
                .then(response => {
                    this.twofaccount = response.data;
                    this.validationErrors['qrcode'] = '';
                })
                .catch(error => {
                    if( error.response.status == 422 ) {
                        this.validationErrors = error.response.data.errors
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response.data.message } });
                    }
                });

            },

            uploadIcon(event) {

                // clean possible already uploaded temp icon
                if( this.tempIcon ) {
                    this.deleteIcon()
                }

                let imgdata = new FormData();

                imgdata.append('icon', this.$refs.iconInput.files[0]);

                this.form.upload('/api/icon/upload', imgdata)
                .then(response => {
                    this.tempIcon = response.data;
                    this.validationErrors['icon'] = '';
                })
                .catch(error => {
                    if( error.response.status == 422 ) {
                        this.validationErrors = error.response.data.errors
                    }
                    else {
                        this.$router.push({ name: 'genericError', params: { err: error.response.data.message } });
                    }
                });

            },

            deleteIcon(event) {
                if(this.tempIcon) {
                    axios.delete('/api/icon/delete/' + this.tempIcon)
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