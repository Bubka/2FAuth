<template>
    <form-wrapper :title="$t('twofaccounts.forms.edit_account')">
        <form @submit.prevent="updateAccount" @keydown="form.onKeydown($event)">
            <!-- service -->
            <form-field :form="form" fieldName="service" inputType="text" :label="$t('twofaccounts.service')" :placeholder="$t('twofaccounts.forms.service.placeholder')" autofocus />
            <!-- account -->
            <form-field :form="form" fieldName="account" inputType="text" :label="$t('twofaccounts.account')" :placeholder="$t('twofaccounts.forms.account.placeholder')" />
            <!-- icon -->
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
            <!-- otp type -->
            <form-toggle class="has-uppercased-button" :isDisabled="true" :form="form" :choices="otp_types" fieldName="otp_type" :label="$t('twofaccounts.forms.otp_type.label')" :help="$t('twofaccounts.forms.otp_type.help')" :hasOffset="true" />
            <div v-if="form.otp_type">
                <!-- secret -->
                <label class="label" v-html="$t('twofaccounts.forms.secret.label')"></label>
                <div class="field has-addons">
                    <p class="control">
                        <span class="select">
                            <select v-model="form.secretIsBase32Encoded">
                                <option v-for="format in secretFormats" :value="format.value">{{ format.text }}</option>
                            </select>
                        </span>
                    </p>
                    <p class="control is-expanded">
                        <input class="input" type="text" v-model="form.secret">
                    </p>
                </div>
                <div class="field">
                    <field-error :form="form" field="secret" class="help-for-file" />
                    <p class="help" v-html="$t('twofaccounts.forms.secret.help')"></p>
                </div>
                <h2 class="title is-4 mt-5 mb-2">{{ $t('commons.options') }}</h2>
                <p class="help mb-4">
                    {{ $t('twofaccounts.forms.options_help') }}
                </p>
                <!-- digits -->
                <form-toggle :form="form" :choices="digitsChoices" fieldName="digits" :label="$t('twofaccounts.forms.digits.label')" :help="$t('twofaccounts.forms.digits.help')" />
                <!-- algorithm -->
                <form-toggle :form="form" :choices="algorithms" fieldName="algorithm" :label="$t('twofaccounts.forms.algorithm.label')" :help="$t('twofaccounts.forms.algorithm.help')" />
                <!-- TOTP period -->
                <form-field v-if="form.otp_type === 'totp'" :form="form" fieldName="period" inputType="text" :label="$t('twofaccounts.forms.period.label')" :placeholder="$t('twofaccounts.forms.period.placeholder')" :help="$t('twofaccounts.forms.period.help')" />
                <!-- HOTP counter -->
                <div v-if="form.otp_type === 'hotp'">
                    <div class="field" style="margin-bottom: 0.5rem;">
                        <label class="label">{{ $t('twofaccounts.forms.counter.label') }}</label>
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
                    <field-error :form="form" field="counter" />
                    <p class="help" v-html="$t('twofaccounts.forms.counter.help_lock')"></p>
                </div>
            </div>
            <!-- form buttons -->
            <vue-footer :showButtons="true">
                <p class="control">
                    <v-button :isLoading="form.isBusy" class="is-rounded" >{{ $t('commons.save') }}</v-button>
                </p>
                <p class="control" v-if="form.otp_type && form.secret">
                    <button type="button" class="button is-success is-rounded" @click="previewAccount">{{ $t('twofaccounts.forms.test') }}</button>
                </p>
                <p class="control">
                    <button type="button" class="button is-text is-rounded" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
                </p>
            </vue-footer>
        </form>
        <!-- modal -->
        <modal v-model="ShowTwofaccountInModal">
            <otp-displayer ref="AdvancedFormOtpDisplayer" v-bind="form.data()" @increment-hotp="incrementHotp">
            </otp-displayer>
        </modal>
    </form-wrapper>
</template>

<script>

    import Modal from '../../components/Modal'
    import Form from './../../components/Form'
    import OtpDisplayer from '../../components/OtpDisplayer'

    export default {
        data() {
            return {
                ShowTwofaccountInModal : false,
                counterIsLocked: true,
                twofaccountExists: false,
                tempIcon: '',
                form: new Form({
                    service: '',
                    account: '',
                    otp_type: '',
                    uri: '',
                    icon: '',
                    secret: '',
                    secretIsBase32Encoded: null,
                    algorithm: '',
                    digits: null,
                    counter: null,
                    period: null,
                    image: '',
                }),
                otp_types: [
                    { text: 'TOTP', value: 'totp' },
                    { text: 'HOTP', value: 'hotp' },
                ],
                digitsChoices: [
                    { text: 6, value: 6 },
                    { text: 7, value: 7 },
                    { text: 8, value: 8 },
                    { text: 9, value: 9 },
                    { text: 10, value: 10 },
                ],
                secretFormats: [
                    { text: this.$t('twofaccounts.forms.plain_text'), value: 0 },
                    { text: 'Base32', value: 1 }
                ],
                algorithms: [
                    { text: 'sha1', value: 'sha1' },
                    { text: 'sha256', value: 'sha256' },
                    { text: 'sha512', value: 'sha512' },
                    { text: 'md5', value: 'md5' },
                ],
            }
        },

        mounted: function () {

            // stop TOTP generation on modal close
            this.$on('modalClose', function() {

                this.$refs.AdvancedFormOtpDisplayer.stopLoop()
            });
        },

        created: function() {
            this.getAccount();
        },

        components: {
            Modal,
            OtpDisplayer,
        },

        methods: {
            async getAccount () {

                const { data } = await this.axios.get('/api/v1/twofaccounts/' + this.$route.params.twofaccountId)

                this.form.fill(data)
                this.form.secretIsBase32Encoded = 1
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

                await this.form.put('/api/v1/twofaccounts/' + this.$route.params.twofaccountId)

                if( this.form.errors.any() === false ) {
                    this.$router.push({name: 'accounts', params: { InitialEditMode: true, toRefresh: true }})
                }

            },

            previewAccount() {
                this.$refs.AdvancedFormOtpDisplayer.show()
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

                const { data } = await this.form.upload('/api/v1/icons', imgdata)

                this.tempIcon = data;

            },

            deleteIcon(event) {

                if( this.tempIcon && this.tempIcon !== this.form.icon ) {
                    this.axios.delete('/api/v1/icons/' + this.tempIcon)
                }

                this.tempIcon = ''
            },

            incrementHotp(payload) {
                // The quick form or the preview feature has incremented the HOTP counter so we get the new value from
                // the component.
                // This could desynchronized the HOTP verification server and our local counter if the user never verified the HOTP but this
                // is acceptable (and HOTP counter can be edited by the way)
                this.form.counter = payload.nextHotpCounter
                this.form.uri = payload.nextUri
            },

        },

    }
</script>