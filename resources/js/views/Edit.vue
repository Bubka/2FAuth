<template>
    <div class="section">
        <div class="columns is-mobile is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">Edit account</h1>
                <form @submit.prevent="updateAccount">
                    <div class="field">
                        <label class="label">Service</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="example.com" v-model="twofaccount.service" required autofocus />
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Account</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="John DOE" v-model="twofaccount.account" />
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
                            <span class="tag is-black is-large" v-if="tempIcon">
                                <img class="icon-preview" :src="'../storage/icons/' + tempIcon" >
                                <button class="delete is-small" @click.prevent="deleteIcon"></button>
                            </span>
                        </div>
                    </div>
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-link">Save</button>
                        </div>
                        <div class="control">
                            <button class="button is-text" @click.prevent="cancelCreation">Cancel</button>
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
                tempIcon: ''
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

                    // set account icon as temp icon
                    this.tempIcon = this.twofaccount.icon
                })
            },

            updateAccount: function() {

                // Set new icon and delete old one
                if( this.tempIcon !== this.twofaccount.icon ) {
                    let oldIcon = ''

                    oldIcon = this.twofaccount.icon

                    this.twofaccount.icon = this.tempIcon
                    this.tempIcon = oldIcon
                    this.deleteIcon()
                }

                // store the account
                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.put('/api/twofaccounts/' + this.$route.params.twofaccountId, this.twofaccount).then(response => {
                    this.$router.push({name: 'accounts', params: { InitialEditMode: true }});
                })
            },

            cancelCreation: function() {
                // clean new temp icon
                if( this.tempIcon ) {
                    this.deleteIcon()
                }

                this.$router.push({name: 'accounts', params: { InitialEditMode: true }});
            },

            uploadIcon(event) {

                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                let files = this.$refs.iconInput.files

                if (!files.length) {
                    return false;
                }

                // clean possible tempIcon but keep original one
                if( this.tempIcon && this.tempIcon !== this.twofaccount.icon ) {
                    this.deleteIcon()
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
                        this.tempIcon = response.data;
                    }
                )
            },

            deleteIcon(event) {

                if( this.tempIcon !== this.twofaccount.icon ) {
                    let token = localStorage.getItem('jwt')

                    axios.defaults.headers.common['Content-Type'] = 'application/json'
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                    axios.delete('/api/icon/delete/' + this.tempIcon).then(response => {
                            this.tempIcon = ''
                        }
                    )
                }

                this.tempIcon = ''
            },

        },

    }
</script>