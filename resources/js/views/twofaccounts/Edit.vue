<template>
    <div class="section" v-if="twofaccountExists">
        <div class="columns is-mobile is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('twofaccounts.forms.edit_account') }}</h1>
                <form @submit.prevent="updateAccount" @keydown="form.onKeydown($event)">
                    <div class="field">
                        <label class="label">{{ $t('twofaccounts.service') }}</label>
                        <div class="control">
                            <input class="input" type="text" :placeholder="$t('twofaccounts.forms.service.placeholder')" v-model="form.service" autofocus />
                        </div>
                        <field-error :form="form" field="service" />
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('twofaccounts.account') }}</label>
                        <div class="control">
                            <input class="input" type="text" :placeholder="$t('twofaccounts.forms.account.placeholder')" v-model="form.account" />
                        </div>
                        <field-error :form="form" field="account" />
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
                                <img class="icon-preview" :src="'../storage/icons/' + tempIcon" >
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
                            <button class="button is-text" @click.prevent="cancelCreation">{{ $t('commons.cancel') }}</button>
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
                twofaccountExists: false,
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

        created: function() {
            this.getAccount();
        },

        methods: {
            getAccount () {

                axios.get('/api/twofaccounts/' + this.$route.params.twofaccountId)
                .then(response => {
                    this.form.fill(response.data)
                    this.twofaccountExists = true

                    // set account icon as temp icon
                    this.tempIcon = this.form.icon
                })
                .catch(error => {
                    this.$router.push({ name: 'genericError', params: { err: error.response } });
                });

            },

            updateAccount() {

                // Set new icon and delete old one
                if( this.tempIcon !== this.form.icon ) {
                    let oldIcon = ''

                    oldIcon = this.form.icon

                    this.form.icon = this.tempIcon
                    this.tempIcon = oldIcon
                    this.deleteIcon()
                }

                this.form.put('/api/twofaccounts/' + this.$route.params.twofaccountId)
                .then(response => {
                    this.$router.push({name: 'accounts', params: { InitialEditMode: true }});
                })
                .catch(error => {
                    if( error.response.status !== 422 ) {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });

            },

            cancelCreation: function() {
                // clean new temp icon
                this.deleteIcon()

                this.$router.push({name: 'accounts', params: { InitialEditMode: true }});
            },

            uploadIcon(event) {

                // clean possible tempIcon but keep original one
                this.deleteIcon()

                let imgdata = new FormData();
                imgdata.append('icon', this.$refs.iconInput.files[0]);

                this.form.upload('/api/icon/upload', imgdata)
                .then(response => {
                    this.tempIcon = response.data;
                })
                .catch(error => {
                    if( error.response.status !== 422 ) {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });

            },

            deleteIcon(event) {

                if( this.tempIcon && this.tempIcon !== this.form.icon ) {
                    axios.delete('/api/icon/delete/' + this.tempIcon)
                }

                this.tempIcon = ''
            },

        },

    }
</script>