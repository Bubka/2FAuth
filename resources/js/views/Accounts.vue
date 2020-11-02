<template>
    <div>
        <!-- Group switch -->
        <div class="container groups" v-if="showGroupSwitch">
            <div class="columns is-centered">
                <div class="column is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                    <div class="columns is-multiline">
                        <div class="column is-full" v-for="group in groups" v-if="group.count > 0" :key="group.id">
                            <button :disabled="group.id == $root.appSettings.activeGroup" class="button is-fullwidth is-dark has-text-light is-outlined" @click="setActiveGroup(group.id)">{{ group.name }}</button>
                        </div>
                    </div>
                    <div class="columns is-centered">
                        <div class="column has-text-centered">
                            <router-link :to="{ name: 'groups' }" >{{ $t('groups.manage_groups') }}</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Group selector -->
        <div class="container groups with-heading" v-if="showGroupSelector">
            <div class="columns is-centered">
                <div class="column is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                    <div class="columns is-multiline">
                        <div class="column is-full" v-for="group in groups" :key="group.id">
                            <button class="button is-fullwidth is-dark has-text-light is-outlined" :class="{ 'is-link' : moveAccountsTo === group.id}" @click="moveAccountsTo = group.id">
                                <span v-if="group.id === 0">
                                    {{ $t('groups.no_group') }}
                                </span>
                                <span v-else>
                                    {{ group.name }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- show accounts list -->
        <div class="container" v-if="this.showAccounts">
            <!-- accounts -->
            <!-- <vue-pull-refresh :on-refresh="onRefresh" :config="{
                errorLabel: 'error',
                startLabel: '',
                readyLabel: '',
                loadingLabel: 'refreshing'
                }" > -->
                <draggable v-model="filteredAccounts" @start="drag = true" @end="saveOrder" ghost-class="ghost" handle=".tfa-dots" animation="200" class="accounts">
                    <transition-group class="columns is-multiline" :class="{ 'is-centered': $root.appSettings.displayMode === 'grid' }" type="transition" :name="!drag ? 'flip-list' : null">
                        <div :class="[$root.appSettings.displayMode === 'grid' ? 'tfa-grid' : 'tfa-list']" class="column is-narrow has-text-white" v-for="account in filteredAccounts" :key="account.id">
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
                                        <img :src="'/storage/icons/' + account.icon" v-if="account.icon && $root.appSettings.showAccountsIcons">
                                        {{ account.service }}
                                        <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey ">{{ account.account }}</span>
                                    </div>
                                </div>
        	                    <transition name="fadeInOut">
        	                        <div class="tfa-edit has-text-grey" v-if="editMode">
                                        <!-- <div class="tags has-addons"> -->
                                            <router-link :to="{ name: 'editAccount', params: { twofaccountId: account.id }}" class="tag is-dark is-rounded mr-1">
                                            {{ $t('commons.edit') }}
                                            </router-link>
                                            <router-link :to="{ name: 'showQRcode', params: { twofaccountId: account.id }}" class="tag is-dark is-rounded" :title="$t('twofaccounts.show_qrcode')">
                                                <font-awesome-icon :icon="['fas', 'qrcode']" />
                                            </router-link>
                                       <!-- </div> -->
        	                        </div>
        	                    </transition>
                                <transition name="fadeInOut">
                                    <div class="tfa-dots has-text-grey" v-if="editMode">
                                        <font-awesome-icon :icon="['fas', 'bars']" />
                                    </div>
                                </transition>
                            </div>
                        </div>
                    </transition-group>
                </draggable>
            <!-- </vue-pull-refresh> -->
        </div>
        <!-- header -->
        <div class="header has-background-black-ter" v-if="this.showAccounts || this.showGroupSwitch">
            <div class="columns is-gapless is-mobile is-centered">
                <div class="column is-three-quarters-mobile is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                    <!-- toolbar -->
                    <div class="toolbar has-text-centered" v-if="editMode">
                        <div class="manage-buttons tags has-addons are-medium">
                            <span class="tag is-dark">{{ selectedAccounts.length }}&nbsp;{{ $t('commons.selected') }}</span>
                            <a class="tag is-link" v-if="selectedAccounts.length > 0" @click="showGroupSelector = true">
                                {{ $t('commons.move') }}&nbsp;<font-awesome-icon :icon="['fas', 'layer-group']" />
                            </a>
                            <a class="tag is-danger" v-if="selectedAccounts.length > 0" @click="destroyAccounts">
                                {{ $t('commons.delete') }}&nbsp;<font-awesome-icon :icon="['fas', 'trash']" />
                            </a>
                        </div>
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
                    <!-- group switch toggle -->
                    <div class="is-clickable has-text-centered" v-if="!editMode">
                        <div class="columns" @click="toggleGroupSwitch">
                            <div class="column" v-if="!showGroupSwitch">
                                {{ this.activeGroupName }} ({{ this.accounts.length }})
                                <font-awesome-icon  :icon="['fas', 'caret-down']" />
                            </div>
                            <div class="column" v-else>
                                {{ $t('groups.select_accounts_to_show') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Show uploader (because no account) -->
        <quick-uploader v-if="showUploader" :directStreaming="accounts.length > 0" :showTrailer="accounts.length === 0" ref="QuickUploader"></quick-uploader>
        <!-- modal -->
        <modal v-model="showTwofaccountInModal">
            <twofaccount-show ref="TwofaccountShow" ></twofaccount-show>
        </modal>
        <!-- footer -->
        <vue-footer v-if="showFooter && !showGroupSwitch" :showButtons="accounts.length > 0">
            <!-- New item buttons -->
            <p class="control" v-if="!showUploader && !editMode">
                <a class="button is-link is-rounded is-focus" @click="showUploader = true">
                    <span>{{ $t('commons.new') }}</span>
                    <span class="icon is-small">
                        <font-awesome-icon :icon="['fas', 'qrcode']" />
                    </span>
                </a>
            </p>
            <!-- Manage button -->
            <p class="control" v-if="!showUploader && !editMode">
                <a class="button is-dark is-rounded" @click="setEditModeTo(true)">{{ $t('commons.manage') }}</a>
            </p>
            <!-- Done button -->
            <p class="control" v-if="!showUploader && editMode">
                <a class="button is-success is-rounded" @click="setEditModeTo(false)">
                    <span>{{ $t('commons.done') }}</span>
                    <span class="icon is-small">
                        <font-awesome-icon :icon="['fas', 'check']" />
                    </span>
                </a>
            </p>
            <!-- Cancel QuickFormButton -->
            <p class="control" v-if="showUploader && showFooter">
                <a class="button is-dark is-rounded" @click="showUploader = false">
                    {{ $t('commons.cancel') }}
                </a>
            </p>
        </vue-footer>
        <vue-footer v-if="showFooter && showGroupSwitch" :showButtons="true">
            <!-- Close Group switch button -->
            <p class="control">
                <a class="button is-dark is-rounded" @click="closeGroupSwitch()">{{ $t('commons.close') }}</a>
            </p>
        </vue-footer>
        <vue-footer v-if="showFooter && showGroupSelector" :showButtons="true">
            <!-- Move to selected group button -->
            <p class="control">
                <a class="button is-link is-rounded" @click="moveAccounts()">{{ $t('commons.move') }}</a>
            </p>
            <!-- Cancel button -->
            <p class="control">
                <a class="button is-dark is-rounded" @click="showGroupSelector = false">{{ $t('commons.cancel') }}</a>
            </p>
        </vue-footer>
    </div>
</template>


<script>

    import Modal from '../components/Modal'
    import TwofaccountShow from '../components/TwofaccountShow'
    import QuickUploader from './../components/QuickUploader'
    // import vuePullRefresh from 'vue-pull-refresh';
    import draggable from 'vuedraggable'
    import Form from './../components/Form'


    export default {
        data(){
            return {
                accounts : [],
                groups : [],
                selectedAccounts: [],
                form: new Form({
                    activeGroup: this.$root.appSettings.activeGroup,
                }),
                showTwofaccountInModal : false,
                search: '',
                editMode: this.InitialEditMode,
                showUploader: false,
                showFooter: true,
                showGroupSwitch: false,
                showGroupSelector: false,
                drag: false,
                moveAccountsTo: false,
            }
        },

        computed: {
            filteredAccounts: {
                get: function() {
                    return this.accounts.filter(
                        item => {
                            return item.service.toLowerCase().includes(this.search.toLowerCase()) || item.account.toLowerCase().includes(this.search.toLowerCase());
                        }
                    );
                },
                set: function(reorderedAccounts) {
                    this.accounts = reorderedAccounts
                }
            },

            showAccounts() {
                return this.accounts.length > 0 && !this.showUploader && !this.showGroupSwitch && !this.showGroupSelector ? true : false
            },

            activeGroupName() {
                let g = this.groups.find(el => el.id === parseInt(this.$root.appSettings.activeGroup))

                if(g) {
                    return g.name
                }
                else {
                    return this.$t('commons.all')
                }
            }

        },

        props: ['InitialEditMode'],

        mounted() {

            this.fetchAccounts()
            this.fetchGroups()

            // stop OTP generation on modal close
            this.$on('modalClose', function() {
                console.log('modalClose triggered')
                this.$refs.TwofaccountShow.clearOTP()
            });

            // hide Footer when stream is on
            this.$on('initStreaming', function() {
                // this.showFooter = this.accounts.length > 0 ? false : true
                this.showFooter = false
            });

            this.$on('stopStreaming', function() {

                this.showUploader = this.accounts.length > 0 ? false : true
                this.showFooter = true
            });

            this.$on('cannotStream', function() {
                
                this.showFooter = true
            });

        },

        components: {
            Modal,
            TwofaccountShow,
            // 'vue-pull-refresh': vuePullRefresh,
            QuickUploader,
            draggable,
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
                    
                    this.showUploader = response.data.length === 0 ? true : false
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

            saveOrder() {
                this.drag = false
                this.axios.patch('/api/twofaccounts/reorder', {orderedIds: this.accounts.map(a => a.id)})
            },

            deleteAccount(id) {
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

            async moveAccounts() {

                let accountsIds = []
                this.selectedAccounts.forEach(id => accountsIds.push(id))

                // Backend will associate all accounts with the selected group in the same move
                await this.axios.patch('/api/group/accounts', {accountsIds: accountsIds, groupId: this.moveAccountsTo} )

                // we fetch the accounts again to prevent the js collection being
                // desynchronize from the backend php collection
                this.fetchAccounts()
                this.fetchGroups()
                this.showGroupSelector = false

            },

            fetchGroups() {
                this.groups = []

                this.axios.get('api/groups').then(response => {
                    response.data.forEach((data) => {
                        this.groups.push({
                            id : data.id,
                            name : data.name,
                            isActive: data.isActive,
                            count: data.twofaccounts_count
                        })
                    })
                })
            },

            async setActiveGroup(id) {

                this.form.activeGroup = id

                await this.form.post('/api/settings/options', {returnError: true})
                .then(response => {

                    this.$root.appSettings.activeGroup = response.data.settings.activeGroup

                    this.closeGroupSwitch()

                })
                .catch(error => {
                    
                    this.$router.push({ name: 'genericError', params: { err: error.response } })
                });

                this.fetchAccounts()
            },

            toggleGroupSwitch: function(event) {

                if (event) {
                    this.showGroupSwitch ? this.closeGroupSwitch() : this.openGroupSwitch()
                }
            },

            openGroupSwitch: function(event) {

                this.showGroupSwitch = true
            },

            closeGroupSwitch: function(event) {

                this.showGroupSwitch = false
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
            }
        }
    };

</script>

<style>
    .flip-list-move {
      transition: transform 0.5s;
    }

    .ghost {
      opacity: 1;
      /*background: hsl(0, 0%, 21%);*/
    }
</style>