<template>
    <div class="section">
        <div class="columns is-mobile is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">Edit account</h1>
                <form @submit.prevent="updateAccount">
                    <div class="field">
                        <label class="label">Service</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="Account name" v-model="twofaccount.name" required autofocus />
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="account email" v-model="twofaccount.email" />
                        </div>
                    </div>
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
                        </div>
                    </div>
                    <div class="field">
                        <div class="control">
                            <input class="input" type="text" placeholder="Icon" v-model="twofaccount.icon" />
                        </div>
                    </div>
                    <div class="field is-grouped">
                        <div class="control">
                            <router-link :to="{ name: 'accounts' }" class="button is-light">Cancel</router-link>
                        </div>
                        <div class="control">
                            <button type="submit" class="button is-link">Save</button>
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
                twofaccount: {}
            }
        },

        created: function(){
            this.getAccount();
        },

        methods: {
            getAccount: function () {
                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.get('/api/twofaccounts/' + this.$route.params.twofaccountId).then(response => {
                    this.twofaccount = response.data
                })
            },

            updateAccount: function() {
                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.put('/api/twofaccounts/' + this.$route.params.twofaccountId, this.twofaccount).then(response => {
                    this.$router.push({name: 'accounts'});
                })
            },

            uploadIcon(event) {

                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                 let files = this.$refs.iconInput.files

                 if (!files.length) {
                     console.log('no files');
                     return false;
                 }
                 else {
                    console.log(files.length + ' file(s) found');
                 }

                let imgdata = new FormData();

                imgdata.append('icon', files[0]); 

                let config = {
                    header : {
                        'Content-Type' : 'multipart/form-data',
                    }
                }

                axios.post('/api/icon/upload', imgdata, config).then(response => {
                        console.log('icon path > ', response);
                        this.twofaccount.icon = response.data;
                    }
                )
            }
        },

    }
</script>