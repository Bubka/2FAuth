<template>
    <form-wrapper :title="$t('twofaccounts.forms.edit_account')">
        <form @submit.prevent="updateAccount" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="service" inputType="text" :label="$t('twofaccounts.service')" :placeholder="$t('twofaccounts.forms.service.placeholder')" autofocus />
            <form-field :form="form" fieldName="account" inputType="text" :label="$t('twofaccounts.account')" :placeholder="$t('twofaccounts.forms.account.placeholder')" />
            <div v-if="form.type === 'hotp'">
                <div class="field" style="margin-bottom: 0.5rem;">
                    <label class="label">{{ $t('twofaccounts.forms.hotp_counter') }}</label>
                </div>
                <div class="field has-addons">
                    <div class="control is-expanded">
                        <input class="input" type="text" placeholder="" v-model="form.counter" :disabled="counterIsLocked" />
                    </div>
                    <div class="control" v-if="counterIsLocked">
                        <a class="button is-dark field-lock" @click="counterIsLocked = false" :title="$t('twofaccounts.forms.unlock.title')">
                            <span class="icon">
                                <font-awesome-icon :icon="['fas', 'lock']" />
                            </span>
                        </a>
                    </div>
                    <div class="control" v-else>
                        <a class="button is-dark field-unlock"  @click="counterIsLocked = true" :title="$t('twofaccounts.forms.lock.title')">
                            <span class="icon has-text-danger">
                                <font-awesome-icon :icon="['fas', 'lock-open']" />
                            </span>
                        </a>
                    </div>
                </div>
                <field-error :form="form" field="uri" class="help-for-file" />
            </div>
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
                    <v-button :isLoading="form.isBusy" >{{ $t('twofaccounts.forms.save') }}</v-button>
                </div>
                <div class="control">
                    <button type="button" class="button is-text" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
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
                counterIsLocked: true,
                twofaccountExists: false,
                tempIcon: '',
                form: new Form({
                    service: '',
                    account: '',
                    uri: '',
                    icon: '',
                    qrcode: null,
                    type: '',
                    counter: null
                })
            }
        },

        created: function() {
            this.getAccount();
        },

        methods: {
            async getAccount () {

                const { data } = await this.axios.get('/api/twofaccounts/' + this.$route.params.twofaccountId)

                this.form.fill(data)
                this.twofaccountExists = true

                // set account icon as temp icon
                this.tempIcon = this.form.icon
                
            },

            async updateAccount() {

                // Set new icon and delete old one
                if( this.tempIcon !== this.form.icon ) {
                    let oldIcon = ''

                    oldIcon = this.form.icon

                    this.form.icon = this.tempIcon
                    this.tempIcon = oldIcon
                    this.deleteIcon()
                }

                await this.form.put('/api/twofaccounts/' + this.$route.params.twofaccountId)

                if( this.form.errors.any() === false ) {
                    this.$router.push({name: 'accounts', params: { InitialEditMode: true }})
                }

            },

            cancelCreation: function() {
                // clean new temp icon
                this.deleteIcon()

                this.$router.push({name: 'accounts', params: { InitialEditMode: true }});
            },

            async uploadIcon(event) {

                // clean possible tempIcon but keep original one
                this.deleteIcon()

                let imgdata = new FormData();
                imgdata.append('icon', this.$refs.iconInput.files[0]);

                const { data } = await this.form.upload('/api/icon/upload', imgdata)

                this.tempIcon = data;

            },

            deleteIcon(event) {

                if( this.tempIcon && this.tempIcon !== this.form.icon ) {
                    this.axios.delete('/api/icon/delete/' + this.tempIcon)
                }

                this.tempIcon = ''
            },

        },

    }
</script>