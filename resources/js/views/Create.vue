<template>
    <div class="modal modal-otp is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <section class="section">
                <div class="columns is-centered">
                    <div class="column is-three-quarters">
                        <div class="box has-background-black-ter ">
                            <form @submit.prevent="createAccount">
                                <h1 class="subtitle is-2">New account</h1>
                                <div class="field">
                                    <label class="label">qr code</label>
                                    <div class="control">
                                        
                                    </div>
                                </div>
                                <div class="file has-name is-fullwidth">
                                    <label class="file-label">
                                        <input type="file" class="file-input" accept="image/*" v-on:change="uploadQrcode" ref="fileInput">
                                        <span class="file-cta">
                                            <span class="file-icon">
                                                <i class="fas fa-qrcode"></i>
                                            </span>
                                            <span class="file-label">Use a QR Code</span>
                                        </span>
                                        <span class="file-name">Screen Shot 2017-07-29 at 15.54.25.png</span>
                                    </label>
                                </div>
                                <div class="field">
                                    <label class="label">Service</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="Name" v-model="twofaccount.name" required autofocus />
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Email</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="Email" v-model="twofaccount.email"  />
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Uri</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="Uri" v-model="twofaccount.uri" />
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Icon</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="Icon" v-model="twofaccount.icon" />
                                    </div>
                                </div>
                                <div class="field is-grouped">
                                    <div class="control">
                                        <router-link :to="{ name: 'accounts' }" class="button is-light">Cancel</router-link>
                                    </div>
                                    <div class="control">
                                        <button type="submit" class="button is-link">Create</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                twofaccount: {}
            }
        },

        methods: {

            createAccount: function() {
                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.post('/api/twofaccounts', this.twofaccount).then(response => {
                    this.$router.push({name: 'accounts'});
                })
            },

            uploadQrcode(event) {

                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                 let files = this.$refs.fileInput.files

                 if (!files.length) {
                     console.log('no files');
                     return false;
                 }
                 else {
                    console.log(files.length + ' file(s) found');
                 }

                let imgdata = new FormData();

                imgdata.append('qrcode', files[0]); 

                let config = {
                    header : {
                        'Content-Type' : 'multipart/form-data',
                    }
                }

                axios.post('/api/qrcode/decode', imgdata, config).then(response => {
                        console.log('image upload response > ', response);
                        this.twofaccount = response.data;
                    }
                )
            }
        },

    }
</script>