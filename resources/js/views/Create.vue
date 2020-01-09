<template>
    <div class="section">
        <div class="columns is-mobile is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">New account</h1>
                <form @submit.prevent="createAccount">
                    <div class="field">
                        <div class="file is-dark is-boxed">
                            <label class="file-label" title="Use a QR code to fill the form magically">
                                <input class="file-input" type="file" accept="image/*" v-on:change="uploadQrcode" ref="qrcodeInput">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <font-awesome-icon :icon="['fas', 'qrcode']" size="lg" />
                                    </span>
                                    <span class="file-label">Use a qrcode</span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <p class="help is-danger help-for-file" v-if="errors.qrcode">{{ errors.qrcode.toString() }}</p>
                    <div class="field">
                        <label class="label">Service</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="example.com" v-model="twofaccount.service"  autofocus />
                        </div>
                        <p class="help is-danger" v-if="errors.service">{{ errors.service.toString() }}</p>
                    </div>
                    <div class="field">
                        <label class="label">Account</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="John DOE" v-model="twofaccount.account"  />
                        </div>
                        <p class="help is-danger" v-if="errors.account">{{ errors.account.toString() }}</p>
                    </div>
                    <div class="field" style="margin-bottom: 0.5rem;">
                        <label class="label">TOTP Uri</label>
                    </div>
                    <div class="field has-addons">
                        <div class="control is-expanded">
                            <input class="input" type="text" placeholder="otpauth://totp/..." v-model="twofaccount.uri" :disabled="uriIsLocked" />
                        </div>
                        <div class="control" v-if="uriIsLocked">
                            <a class="button is-dark field-lock" @click="uriIsLocked = false" title="Unlock it (at your own risk)">
                                <span class="icon">
                                    <font-awesome-icon :icon="['fas', 'lock']" />
                                </span>
                            </a>
                        </div>
                        <div class="control" v-else>
                            <a class="button is-dark field-unlock"  @click="uriIsLocked = true" title="Lock it">
                                <span class="icon has-text-danger">
                                    <font-awesome-icon :icon="['fas', 'lock-open']" />
                                </span>
                            </a>
                        </div>
                    </div>
                    <p class="help is-danger help-for-file" v-if="errors.uri">{{ errors.uri.toString() }}</p>
                    <div class="field">
                        <label class="label">Icon</label>
                        <div class="file is-dark">
                            <label class="file-label">
                                <input class="file-input" type="file" accept="image/*" v-on:change="uploadIcon" ref="iconInput">
                                <span class="file-cta">
                                    <span class="file-icon">
                                        <font-awesome-icon :icon="['fas', 'image']" />
                                    </span>
                                    <span class="file-label">Choose an image…</span>
                                </span>
                            </label>
                            <span class="tag is-black is-large" v-if="tempIcon">
                                <img class="icon-preview" :src="'storage/icons/' + tempIcon" >
                                <button class="delete is-small" @click.prevent="deleteIcon"></button>
                            </span>
                        </div>
                    </div>
                    <p class="help is-danger help-for-file" v-if="errors.icon">{{ errors.icon.toString() }}</p>
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-link">Create</button>
                        </div>
                        <div class="control">
                            <button class="button is-text" @click="cancelCreation">Cancel</button>
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
                errors: {}
            }
        },

        methods: {

            createAccount: function() {
                // set current temp icon as account icon
                this.twofaccount.icon = this.tempIcon

                // store the account
                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.post('/api/twofaccounts', this.twofaccount)
                .then(response => {
                    this.$router.push({name: 'accounts', params: { InitialEditMode: false }});
                })
                .catch(error => {
                    if (error.response.status === 400) {
                        this.errors = error.response.data.error
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

                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                let imgdata = new FormData();

                imgdata.append('qrcode', this.$refs.qrcodeInput.files[0]); 

                let config = {
                    header : {
                        'Content-Type' : 'multipart/form-data',
                    }
                }

                axios.post('/api/qrcode/decode', imgdata, config)
                    .then(response => {
                        this.twofaccount = response.data;
                        this.errors['qrcode'] = '';
                    })
                    .catch(error => {
                        if (error.response.status === 400) {
                            this.errors = error.response.data.error
                        }
                    });
            },

            uploadIcon(event) {

                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

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

                axios.post('/api/icon/upload', imgdata, config)
                    .then(response => {
                        console.log('icon path > ', response);
                        this.tempIcon = response.data;
                        this.errors['icon'] = '';
                    })
                    .catch(error => {
                        if (error.response.status === 400) {
                            this.errors = error.response.data.error
                        }
                    });

            },

            deleteIcon(event) {

                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.delete('/api/icon/delete/' + this.tempIcon).then(response => {
                        this.tempIcon = ''
                    }
                )
            }
            
        },

    }
</script>