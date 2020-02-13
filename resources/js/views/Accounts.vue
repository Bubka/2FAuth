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
            <vue-pull-refresh :on-refresh="onRefresh" :config="{
                errorLabel: 'error',
                startLabel: '',
                readyLabel: '',
                loadingLabel: 'refreshing'
                }" class="accounts columns is-multiline is-centered">
                <div class="tfa column is-narrow has-text-white" v-for="account in filteredAccounts">
                    <div class="tfa-container">
	                    <transition name="slideCheckbox">
	                        <div class="tfa-checkbox" v-if="editMode">
	                            <div class="field">
	                                <input class="is-checkradio is-small is-white" :id="'ckb_' + account.id" :value="account.id" type="checkbox" :name="'ckb_' + account.id" v-model="selectedAccounts">
	                                <label :for="'ckb_' + account.id"></label>
	                            </div>
	                        </div>
	                    </transition>
                        <div class="tfa-content is-size-3 is-size-4-mobile" @click.stop="showAccount(account)">  
                            <div class="tfa-text has-ellipsis">
                                <img :src="'/storage/icons/' + account.icon" v-if="account.icon">
                                {{ account.service }}
                                <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey ">{{ account.account }}</span>
                            </div>
                        </div>
	                    <transition name="fadeInOut">
	                        <div class="tfa-dots has-text-grey" v-if="editMode">
	                            <router-link :to="{ name: 'edit', params: { twofaccountId: account.id }}" class="tag is-dark is-rounded">
	                                {{ $t('commons.edit') }}
	                            </router-link>
	                        </div>
	                    </transition>
                    </div>
                </div>
            </vue-pull-refresh>
        </div>
        <!-- No account -->
        <div class="container has-text-centered" v-show="showQuickForm">
            <div class="columns is-mobile" :class="{ 'is-invisible' : this.accounts.length > 0}">
                <div class="column quickform-header">
                    {{ $t('twofaccounts.no_account_here') }}<br>
                    {{ $t('twofaccounts.add_first_account') }}
                </div>
            </div>
            <div class="container">
                <form @submit.prevent="createAccount" @keydown="form.onKeydown($event)">
                    <div class="columns is-mobile no-account is-vcentered">
                        <div class="column has-text-centered">
                            <label :class="{'is-loading' : form.isBusy}" class="button is-link is-medium is-rounded is-focused">
                                <input class="file-input" type="file" accept="image/*" v-on:change="uploadQrcode" ref="qrcodeInput">
                                {{ $t('twofaccounts.forms.use_qrcode.val') }}
                            </label>
                        </div>
                    </div>
                    <field-error :form="form" field="qrcode" />
                </form>
            </div>
            <div class="columns is-mobile">
                <div class="column quickform-footer">
                    <router-link :to="{ name: 'create' }" class="is-link">{{ $t('twofaccounts.use_full_form') }}</router-link>
                </div>
            </div>
        </div>
        <!-- modal -->
        <modal v-model="ShowTwofaccountInModal">
            <twofaccount-show ref="TwofaccountShow" ></twofaccount-show>
        </modal>
        <!-- footer -->
        <vue-footer :showButtons="this.accounts.length > 0">
            <!-- New item buttons -->
            <p class="control" v-if="!showQuickForm && !editMode">
                <a class="button is-link is-rounded is-focus" @click="showQuickForm = true">
                    <span>{{ $t('twofaccounts.new') }}</span>
                    <span class="icon is-small">
                        <font-awesome-icon :icon="['fas', 'qrcode']" />
                    </span>
                </a>
            </p>
            <!-- Manage button -->
            <p class="control" v-if="!showQuickForm && !editMode">
                <a class="button is-dark is-rounded" @click="setEditModeTo(true)">{{ $t('twofaccounts.manage') }}</a>
            </p>
            <!-- Done button -->
            <p class="control" v-if="!showQuickForm && editMode">
                <a class="button is-success is-rounded" @click="setEditModeTo(false)">
                    <span>{{ $t('twofaccounts.done') }}</span>
                    <span class="icon is-small">
                        <font-awesome-icon :icon="['fas', 'check']" />
                    </span>
                </a>
            </p>
            <!-- Cancel QuickFormButton -->
            <p class="control" v-if="showQuickForm">
                <a class="button is-dark is-rounded" @click="cancelQuickForm">
                    {{ $t('commons.cancel') }}
                </a>
            </p>
        </vue-footer>
    </div>
</template>


<script>

    import Modal from '../components/Modal'
    import TwofaccountShow from '../components/TwofaccountShow'
    import Form from './../components/Form'
    import vuePullRefresh from 'vue-pull-refresh';

    export default {
        data(){
            return {
                accounts : [],
                selectedAccounts: [],
                ShowTwofaccountInModal : false,
                search: '',
                editMode: this.InitialEditMode,
                showQuickForm: false,
                form: new Form({
                    qrcode: null
                }),
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

            showAccounts() {
                return this.accounts.length > 0 && !this.showQuickForm ? true : false
            },
        },

        props: ['InitialEditMode'],

        created() {

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
            'vue-pull-refresh': vuePullRefresh
        },

        methods: {

            onRefresh() {
                var that = this

                return new Promise(function (resolve, reject) {
                    setTimeout(function () {
                        that.fetchAccounts()
                        resolve();
                    }, 1000);
                });
            },

            async uploadQrcode(event) {

                let imgdata = new FormData();
                imgdata.append('qrcode', this.$refs.qrcodeInput.files[0]);

                const { data } = await this.form.upload('/api/qrcode/decode', imgdata)

                this.$router.push({ name: 'create', params: { qrAccount: data } });

            },

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
                    
                    this.showQuickForm = response.data.length === 0 ? true: false
                })
            },

            showAccount(account) {
                if(this.editMode) {

                    for (var i=0 ; i<this.selectedAccounts.length ; i++) {
                        if ( this.selectedAccounts[i] === account.id ) {
                            this.selectedAccounts.splice(i,1);
                            return
                        }
                    }

                    this.selectedAccounts.push(account.id)
                }
                else {
                    this.$refs.TwofaccountShow.showAccount(account.id)
                }
            },

            deleteAccount:  function (id) {
                if(confirm(this.$t('twofaccounts.confirm.delete'))) {
                    this.axios.delete('/api/twofaccounts/' + id)

                    // Remove the deleted account from the collection
                    this.accounts = this.accounts.filter(a => a.id !== id)
                }
            },

            async destroyAccounts() {
                if(confirm(this.$t('twofaccounts.confirm.delete'))) {

                    let ids = []
                    this.selectedAccounts.forEach(id => ids.push(id))

                    // Backend will delete all accounts at the same time
                    await this.axios.delete('/api/twofaccounts/batch', {data: ids} )

                    // we fetch the accounts again to prevent the js collection being
                    // desynchronize from the backend php collection
                    this.fetchAccounts()
                }
            },

            setEditModeTo(state) {
                if( state === false ) {
                    this.selectedAccounts = []
                }
                else {
                    this.search = '';
                }

                this.editMode = state
                this.$parent.showToolbar = state
            },

            cancelQuickForm() {
                this.form.clear()
                this.showQuickForm = false
            }
        },
        
        beforeRouteEnter (to, from, next) {
            if ( ! localStorage.getItem('jwt')) {
                return next('login')
            }

            next()
        }
    };

</script>