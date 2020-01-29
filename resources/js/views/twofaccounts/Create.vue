<template>
    <form-wrapper :title="$t('twofaccounts.forms.new_account')">
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
                    <input class="input" type="text" :placeholder="$t('twofaccounts.forms.service.placeholder')" v-model="form.service"  autofocus />
                </div>
                <field-error :form="form" field="service" />
            </div>
            <div class="field">
                <label class="label">{{ $t('twofaccounts.account') }}</label>
                <div class="control">
                    <input class="input" type="text" :placeholder="$t('twofaccounts.forms.account.placeholder')" v-model="form.account"  />
                </div>
                <field-error :form="form" field="account" />
            </div>
            <div class="field" style="margin-bottom: 0.5rem;">
                <label class="label">{{ $t('twofaccounts.forms.totp_uri') }}</label>
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
                <div class="control">
                    <button class="button is-text" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
                </div>
            </div>
        </form>
    </form-wrapper>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data() {
            return {
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

        methods: {

            async createAccount() {
                // set current temp icon as account icon
                this.form.icon = this.tempIcon

                await this.form.post('/api/twofaccounts')

                if( this.form.errors.any() === false ) {
                    this.$router.push({name: 'accounts', params: { InitialEditMode: false }});
                }

            },

            cancelCreation: function() {
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