<template>
    <div>
        <div class="container" v-if="this.accounts.length > 0">
            <div class="buttons are-large is-centered">
                <span v-for="account in accounts" class="button is-black twofaccount" >
                    <span @click.stop="getAccount(account.id)">
                        <img :src="account.icon">
                        {{ account.service }}
                        <span class="is-family-primary is-size-7 has-text-grey">{{ account.account }}</span>
                    </span>
                    <span v-if="editMode">
                        <router-link :to="{ name: 'edit', params: { twofaccountId: account.id }}" class="tag is-dark">
                            <font-awesome-icon :icon="['fas', 'edit']" />
                        </router-link>
                        <a class="tag is-dark" v-on:click="deleteAccount(account.id)">
                            <font-awesome-icon :icon="['fas', 'trash']" />
                        </a>
                    </span>
                </span>
            </div>
        </div>
        <div class="container has-text-centered" v-else>
            <p>
                <img class="bg" src="storage/bg.png">
            </p>
            <p class="subtitle is-5">
                No 2FA here!
            </p>
            <router-link :to="{ name: 'create' }" class="button is-medium is-link is-focused">Add one</router-link>
        </div>
        <modal v-model="ShowTwofaccountInModal">
            <twofaccount-show
                :twofaccountid='twofaccount.id'
                :service='twofaccount.service'
                :icon='twofaccount.icon'
                :account='twofaccount.account'>
                <one-time-password ref="OneTimePassword"></one-time-password>
            </twofaccount-show>
        </modal>
        <footer class="has-background-black-ter">
            <div class="columns is-gapless" v-if="this.accounts.length > 0">
                <div class="column has-text-centered">
                    <a class="button is-dark is-rounded" @click="editMode = true" v-if="!editMode">Manage</a>
                    <div class="field has-addons" v-if="editMode">
                        <p class="control">
                            <button class="button is-success is-rounded" @click="editMode = false">
                                <span class="icon is-small">
                                    <font-awesome-icon :icon="['fas', 'check']" />
                                </span>
                                <span>Done</span>
                            </button>
                        </p>
                        <p class="control">
                            <router-link :to="{ name: 'create' }" class="button is-dark is-rounded">
                                <span class="icon is-small">
                                    <font-awesome-icon :icon="['fas', 'qrcode']" />
                                </span>
                            </router-link>
                        </p>
                    </div>

                </div>
            </div>
            <div class="content has-text-centered">
                <span v-if="token">
                    Hi {{username}} ! <a class="" @click="logout">Sign out</a>
                </span>
                <span v-else>
                    <router-link :to="{ name: 'login' }" class="button is-black">
                        Sign in
                    </router-link>
                    <router-link :to="{ name: 'register' }" class="button is-black">
                        Register
                    </router-link>
                </span>
            </div>
        </footer>
    </div>
</template>


<script>
    import Modal from '../components/Modal'
    import TwofaccountShow from '../components/TwofaccountShow'
    import OneTimePassword from '../components/OneTimePassword'

    export default {
        data(){
            return {
                accounts : [],
                ShowTwofaccountInModal : false,
                twofaccount: {},
                token : null,
                username : null,
                editMode: this.InitialEditMode
            }
        },

        props: ['InitialEditMode'],

        mounted(){
            this.token = localStorage.getItem('jwt')
            this.username = localStorage.getItem('user')

            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token

            axios.get('api/twofaccounts').then(response => {
                response.data.forEach((data) => {
                    this.accounts.push({
                        id : data.id,
                        service : data.service,
                        account : data.account ? data.account : '-',
                        icon : data.icon
                    })
                })
            })

            // stop OTP generation on modal close
            this.$on('modalClose', function() {
                console.log('modalClose triggered')
                this.$refs.OneTimePassword.clearOTP()
            });

        },

        components: {
            Modal,
            TwofaccountShow,
            OneTimePassword
        },
        methods: {
            getAccount: function (id) {
                let accountId = id

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token

                axios.get('api/twofaccounts/' + id).then(response => {

                    this.twofaccount.id = response.data.id
                    this.twofaccount.service = response.data.service
                    this.twofaccount.account = response.data.account
                    this.twofaccount.icon = response.data.icon

                    this.$refs.OneTimePassword.AccountId = response.data.id
                    this.$refs.OneTimePassword.getOTP()
                    this.ShowTwofaccountInModal = true;

                })
            },

            deleteAccount:  function (id) {
                if(confirm("Are you sure you want to delete this account?")) {

                    axios.defaults.headers.common['Content-Type'] = 'application/json'
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token

                    axios.delete('/api/twofaccounts/' + id).then(response => {
                        this.accounts.splice(this.accounts.findIndex(x => x.id === id), 1);
                    })
                }
            },

            logout(evt) {
                if(confirm("Are you sure you want to log out?")) {
                    axios.post('api/logout').then(response => {

                        localStorage.removeItem('jwt');
                        delete axios.defaults.headers.common['Authorization'];

                        this.$router.go('/login');
                    })
                    .catch(error => {
                        localStorage.removeItem('jwt');
                        delete axios.defaults.headers.common['Authorization'];

                        this.$router.go('/login');
                    });       
                }
            }

        },
        beforeRouteEnter (to, from, next) {
            if ( ! localStorage.getItem('jwt')) {
                return next('login')
            }

            next()
        }
    }

</script>

