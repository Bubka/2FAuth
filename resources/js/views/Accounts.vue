<template>
    <div>
        <div class="container" v-if="this.showAccounts">
            <div class="columns is-gapless is-mobile is-centered">
                <div class="column is-three-quarters-mobile is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                    <div class="field">
                        <div class="control has-icons-right">
                            <input type="text" class="input is-rounded is-search" v-model="search">
                            <span class="icon is-small is-right">
                                <font-awesome-icon :icon="['fas', 'search']"  v-if="!search" />
                                <a class="delete" v-if="search" @click="search = '' "></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="buttons are-large is-centered">
                <span v-for="account in filteredAccounts" class="button is-black has-background-black-bis twofaccount" >
                    <span @click.stop="getAccount(account.id)">
                        <img :src="'storage/icons/' + account.icon" v-if="account.icon">
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
        <div class="container has-text-centered" v-show="this.showNoAccount">
            <p class="no-account"></p>
            <p class="subtitle is-5 has-text-grey">
                {{ $t('twofaccounts.no_account_here') }}
            </p>
            <router-link :to="{ name: 'create' }" class="button is-medium is-link is-focused">{{ $t('twofaccounts.add_one') }}</router-link>
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
                    <div class="field is-grouped">
                        <p class="control">
                            <router-link :to="{ name: 'create' }" class="button is-link is-rounded is-focus">
                                <span>{{ $t('twofaccounts.new') }}</span>
                                <span class="icon is-small">
                                    <font-awesome-icon :icon="['fas', 'qrcode']" />
                                </span>
                            </router-link>
                        </p>
                        <p class="control">
                            <a class="button is-dark is-rounded" @click="editMode = true" v-if="!editMode">{{ $t('twofaccounts.manage') }}</a>
                            <a class="button is-success is-rounded" @click="editMode = false" v-if="editMode">
                                <span>{{ $t('twofaccounts.done') }}</span>
                                <span class="icon is-small">
                                    <font-awesome-icon :icon="['fas', 'check']" />
                                </span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="content has-text-centered">
                {{ $t('auth.hello', {username: username}) }} <a class="has-text-grey" @click="logout">{{ $t('auth.sign_out') }}</a>
            </div>
        </footer>
    </div>
</template>


<script>
    // import axios from '../packages/axios'
    import Modal from '../components/Modal'
    import TwofaccountShow from '../components/TwofaccountShow'
    import OneTimePassword from '../components/OneTimePassword'

    export default {
        data(){
            return {
                accounts : [],
                ShowTwofaccountInModal : false,
                twofaccount: {},
                search: '',
                token : null,
                username : null,
                editMode: this.InitialEditMode,
                showAccounts: null,
                showNoAccount: null
            }
        },

        computed: {
            filteredAccounts() {
                return this.accounts.filter(
                    item => {
                        return item.service.toLowerCase().includes(this.search.toLowerCase()) || item.account.toLowerCase().includes(this.search.toLowerCase());
                    }
                );
            }
            
        },

        props: ['InitialEditMode'],

        mounted(){

            this.username = localStorage.getItem('user')

            axios.get('api/twofaccounts').then(response => {
                response.data.forEach((data) => {
                    this.accounts.push({
                        id : data.id,
                        service : data.service,
                        account : data.account ? data.account : '-',
                        icon : data.icon
                    })
                })
                this.showAccounts = this.accounts.length > 0 ? true : false
                this.showNoAccount = !this.showAccounts
            })

            // stop OTP generation on modal close
            this.$on('modalClose', function() {
                console.log('modalClose triggered')
                this.$refs.OneTimePassword.clearOTP()

                this.twofaccount.id = ''
                this.twofaccount.service = ''
                this.twofaccount.account = ''
                this.twofaccount.icon = ''
            });

        },

        components: {
            Modal,
            TwofaccountShow,
            OneTimePassword
        },

        methods: {
            getAccount(id) {

                axios.get('api/twofaccounts/' + id)
                .then(response => {
                    this.twofaccount.id = data.id
                    this.twofaccount.service = data.service
                    this.twofaccount.account = data.account
                    this.twofaccount.icon = data.icon

                    this.$refs.OneTimePassword.AccountId = data.id
                    this.$refs.OneTimePassword.getOTP()
                    this.ShowTwofaccountInModal = true;
                })
                .catch(error => {
                    this.$router.push({ name: 'genericError', params: { err: error.response } });
                });

            },

            deleteAccount:  function (id) {
                if(confirm(this.$t('twofaccounts.confirm.delete'))) {

                    axios.delete('/api/twofaccounts/' + id)

                    this.accounts.splice(this.accounts.findIndex(x => x.id === id), 1);
                    this.showAccounts = this.accounts.length > 0 ? true : false
                    this.showNoAccount = !this.showAccounts
                }
            },

            logout(evt) {
                if(confirm(this.$t('auth.confirm.logout'))) {

                    axios.get('api/logout')
                    .then(response => {
                        localStorage.removeItem('jwt');
                        localStorage.removeItem('user');

                        delete axios.defaults.headers.common['Authorization'];

                        this.$router.go('/login');
                    })
                    .catch(error => {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
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

