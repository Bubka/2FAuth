<template>
    <div>
        <div class="container" v-if="this.showAccounts">
            <!-- header -->
            <div class="columns is-gapless is-mobile is-centered">
                <div class="column is-three-quarters-mobile is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                    <!-- toolbar -->
                    <div class="toolbar has-text-centered" v-if="editMode">
                        <a class="button" :class="{ 'is-dark': selectedAccounts.length === 0, 'is-danger': selectedAccounts.length > 0 }" :disabled="selectedAccounts.length == 0" @click="destroyAccounts">
                            <span class="icon is-small">
                                <font-awesome-icon :icon="['fas', 'trash']" />
                            </span>
                            <span>{{ $t('commons.delete') }}</span>
                        </a>
                    </div>
                    <!-- search -->
                    <div class="field" v-else>
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
            <!-- accounts -->
            <div class="accounts columns is-multiline is-centered">
                <div class="tfa column is-narrow has-text-white" v-for="account in filteredAccounts">
                    <div class="tfa-container">
                        <div class="tfa-checkbox" v-if="editMode">
                            <div class="field">
                                <input class="is-checkradio is-small is-white" :id="'ckb_' + account.id" :value="account" type="checkbox" :name="'ckb_' + account.id" v-model="selectedAccounts">
                                <label :for="'ckb_' + account.id"></label>
                            </div>
                        </div>
                        <div class="tfa-content is-size-3 is-size-4-mobile" @click.stop="showAccount(account.id)">  
                            <div class="tfa-text has-ellipsis">
                                <img :src="'/storage/icons/' + account.icon" v-if="account.icon">
                                {{ account.service }}
                                <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey ">{{ account.account }}</span>
                            </div>
                        </div>
                        <div class="tfa-dots has-text-grey" v-if="editMode">
                            <router-link :to="{ name: 'edit', params: { twofaccountId: account.id }}" class="tag is-dark is-rounded">
                                {{ $t('commons.edit') }}
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- No account -->
        <div class="container has-text-centered" v-show="this.showNoAccount">
            <p class="no-account"></p>
            <p class="subtitle is-5 has-text-grey">
                {{ $t('twofaccounts.no_account_here') }}
            </p>
            <router-link :to="{ name: 'create' }" class="button is-medium is-link is-focused">{{ $t('twofaccounts.add_one') }}</router-link>
        </div>
        <!-- modal -->
        <modal v-model="ShowTwofaccountInModal">
            <twofaccount-show ref="TwofaccountShow" ></twofaccount-show>
        </modal>
        <!-- footer -->
        <footer class="has-background-black-ter">
            <div class="columns is-gapless" v-if="this.accounts.length > 0">
                <div class="column has-text-centered">
                    <div class="field is-grouped">
                        <p class="control" v-if="!editMode">
                            <router-link :to="{ name: 'create' }" class="button is-link is-rounded is-focus">
                                <span>{{ $t('twofaccounts.new') }}</span>
                                <span class="icon is-small">
                                    <font-awesome-icon :icon="['fas', 'qrcode']" />
                                </span>
                            </router-link>
                        </p>
                        <p class="control">
                            <a class="button is-dark is-rounded" @click="setEditModeTo(true)" v-if="!editMode">{{ $t('twofaccounts.manage') }}</a>
                            <a class="button is-success is-rounded" @click="setEditModeTo(false)" v-if="editMode">
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
                {{ $t('auth.hello', {username: username}) }} <router-link :to="{ name: 'profile' }" class="has-text-grey">{{ $t('commons.profile') }}</router-link> - <a class="has-text-grey" @click="logout">{{ $t('auth.sign_out') }}</a>
            </div>
        </footer>
    </div>
</template>


<script>

    import Modal from '../components/Modal'
    import TwofaccountShow from '../components/TwofaccountShow'

    export default {
        data(){
            return {
                accounts : [],
                selectedAccounts: [],
                ShowTwofaccountInModal : false,
                search: '',
                username : null,
                editMode: this.InitialEditMode,
                showAccounts: null,
                showNoAccount: null,
            }
        },

        computed: {
            filteredAccounts() {
                return this.accounts.filter(
                    item => {
                        return item.service.toLowerCase().includes(this.search.toLowerCase()) || item.account.toLowerCase().includes(this.search.toLowerCase());
                    }
                );
            },
            
        },

        props: ['InitialEditMode'],

        created() {

            this.username = localStorage.getItem('user')
            this.fetchAccounts()

            // stop OTP generation on modal close
            this.$on('modalClose', function() {
                console.log('modalClose triggered')
                this.$refs.TwofaccountShow.clearOTP()
            });

        },

        components: {
            Modal,
            TwofaccountShow,
        },

        methods: {

            fetchAccounts() {
                this.accounts = []
                this.selectedAccounts = []

                this.axios.get('api/twofaccounts').then(response => {
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
            },

            showAccount(id) {
                if( id ) {
                    this.$refs.TwofaccountShow.getAccount(id)
                }
                else {
                    let err = new Error("Id missing")
                    this.$router.push({ name: 'genericError', params: { err: err } });
                }
            },

            deleteAccount:  function (id) {
                if(confirm(this.$t('twofaccounts.confirm.delete'))) {
                    this.axios.delete('/api/twofaccounts/' + id)

                    // Remove the deleted account from the collection
                    this.accounts = this.accounts.filter(a => a.id !== id)

                    this.showAccounts = this.accounts.length > 0 ? true : false
                    this.showNoAccount = !this.showAccounts
                }
            },

            async destroyAccounts() {
                if(confirm(this.$t('twofaccounts.confirm.delete'))) {

                    let ids = []
                    this.selectedAccounts.forEach(account => ids.push(account.id))

                    // Backend will delete all accounts at the same time
                    await this.axios.delete('/api/twofaccounts/batch', {data: ids} )

                    // we fetch the accounts again to prevent the js collection being
                    // desynchronize from the backend php collection
                    this.fetchAccounts()
                }
            },

            async logout(evt) {
                if(confirm(this.$t('auth.confirm.logout'))) {

                    await this.axios.get('api/logout')

                    localStorage.removeItem('jwt');
                    localStorage.removeItem('user');

                    delete this.axios.defaults.headers.common['Authorization'];

                    this.$router.go('/login');

                }
            },

            setEditModeTo(state) {
                if( state === false ) {
                    this.selectedAccounts = []
                }

                this.editMode = state
                this.$parent.showToolbar = state
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

