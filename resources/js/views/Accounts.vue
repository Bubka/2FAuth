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
            <vue-footer :showButtons="true">
                <!-- Close Group switch button -->
                <p class="control">
                    <a class="button is-dark is-rounded" @click="closeGroupSwitch()">{{ $t('commons.close') }}</a>
                </p>
            </vue-footer>
        </div>
        <!-- Group selector -->
        <div class="container group-selector" v-if="showGroupSelector">
            <div class="columns is-centered is-multiline">
                <div class="column is-full has-text-centered">
                    {{ $t('groups.move_selected_to') }}
                </div>
                <div class="column is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                    <div class="columns is-multiline">
                        <div class="column is-full" v-for="group in groups" :key="group.id">
                            <button class="button is-fullwidth is-dark has-text-light is-outlined" :class="{ 'is-link' : moveAccountsTo === group.id}" @click="moveAccountsTo = group.id">
                                <span v-if="group.id === 0" class="is-italic">
                                    {{ $t('groups.no_group') }}
                                </span>
                                <span v-else>
                                    {{ group.name }}
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="columns is-centered">
                        <div class="column has-text-centered">
                            <router-link :to="{ name: 'groups' }" >{{ $t('groups.manage_groups') }}</router-link>
                        </div>
                    </div>
                </div>
            </div>
            <vue-footer :showButtons="true">
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
        	                        <div class="tfa-cell tfa-checkbox" v-if="editMode">
        	                            <div class="field">
        	                                <input class="is-checkradio is-small is-white" :id="'ckb_' + account.id" :value="account.id" type="checkbox" :name="'ckb_' + account.id" v-model="selectedAccounts">
        	                                <label :for="'ckb_' + account.id"></label>
        	                            </div>
        	                        </div>
        	                    </transition>
                                <div class="tfa-cell tfa-content is-size-3 is-size-4-mobile" @click.stop="showAccount(account)">  
                                    <div class="tfa-text has-ellipsis">
                                        <img :src="'/storage/icons/' + account.icon" v-if="account.icon && $root.appSettings.showAccountsIcons">
                                        {{ account.service }}<font-awesome-icon class="has-text-danger is-size-5 ml-2" v-if="$root.appSettings.useEncryption && account.isConsistent === false" :icon="['fas', 'exclamation-circle']" />
                                        <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey ">{{ account.account }}</span>
                                    </div>
                                </div>
        	                    <transition name="fadeInOut">
        	                        <div class="tfa-cell tfa-edit has-text-grey" v-if="editMode">
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
                                    <div class="tfa-cell tfa-dots has-text-grey" v-if="editMode">
                                        <font-awesome-icon :icon="['fas', 'bars']" />
                                    </div>
                                </transition>
                            </div>
                        </div>
                    </transition-group>
                </draggable>
            <!-- </vue-pull-refresh> -->
            <vue-footer :showButtons="true">
                <!-- New item buttons -->
                <p class="control" v-if="!editMode">
                    <a class="button is-link is-rounded is-focus" @click="start">
                        <span>{{ $t('commons.new') }}</span>
                        <span class="icon is-small">
                            <font-awesome-icon :icon="['fas', 'qrcode']" />
                        </span>
                    </a>
                </p>
                <!-- Manage button -->
                <p class="control" v-if="!editMode">
                    <a class="button is-dark is-rounded" @click="setEditModeTo(true)">{{ $t('commons.manage') }}</a>
                </p>
                <!-- Done button -->
                <p class="control" v-if="editMode">
                    <a class="button is-success is-rounded" @click="setEditModeTo(false)">
                        <span>{{ $t('commons.done') }}</span>
                        <span class="icon is-small">
                            <font-awesome-icon :icon="['fas', 'check']" />
                        </span>
                    </a>
                </p>
            </vue-footer>
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
                                {{ activeGroupName }} ({{ filteredAccounts.length }})
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
        <!-- modal -->
        <modal v-model="showTwofaccountInModal">
            <token-displayer ref="TokenDisplayer" ></token-displayer>
        </modal>
    </div>
</template>


<script>

    /**
     *  Accounts view
     *  
     *  route: '/account' (alias: '/')
     *  
     *  The main view of 2FAuth that list all existing account recorded in DB.
     *  Available feature in this view :
     *  - Token generation
     *  - Account fetching :
     *    ~ Search
     *    ~ Filtering (by group)
     *  - Accounts management :
     *    ~ Sorting
     *    ~ QR code recovering
     *    ~ Mass association to group
     *    ~ Mass account deletion
     *    ~ Access to account editing
     *
     *  Behavior :
     *  - The view has 2 modes (toggle is done with the 'manage' button) :
     *    ~ The View mode (the default one)
     *    ~ The Edit mode
     *  - User are automatically pushed to the start view if there is no account to list.
     *  - The view is affected by :
     *    ~ 'appSettings.showAccountsIcons' toggle the icon visibility
     *    ~ 'appSettings.displayMode' change the account appearance
     *
     *  Input : 
     *  - The 'InitialEditMode' props : allows to load the view directly in Edit mode
     *  
     */

    import Modal from '../components/Modal'
    import TokenDisplayer from '../components/TokenDisplayer'
    import draggable from 'vuedraggable'
    import Form from './../components/Form'
    import objectEquals from 'object-equals'

    export default {
        data(){
            return {
                accounts : [],
                groups : [],
                selectedAccounts: [],
                search: '',
                editMode: this.InitialEditMode,
                drag: false,
                showTwofaccountInModal : false,
                showGroupSwitch: false,
                showGroupSelector: false,
                moveAccountsTo: false,
                form: new Form({
                    activeGroup: this.$root.appSettings.activeGroup,
                }),
            }
        },

        computed: {
            filteredAccounts: {
                get: function() {

                    return this.accounts.filter(
                        item => {
                            if( parseInt(this.$root.appSettings.activeGroup) > 0 ) {
                                return (item.service.toLowerCase().includes(this.search.toLowerCase()) || 
                                    item.account.toLowerCase().includes(this.search.toLowerCase())) && 
                                    (item.group_id == parseInt(this.$root.appSettings.activeGroup))
                            }
                            else {
                                return (item.service.toLowerCase().includes(this.search.toLowerCase()) || 
                                    item.account.toLowerCase().includes(this.search.toLowerCase()))
                            }
                        }
                    );
                },
                set: function(reorderedAccounts) {
                    this.accounts = reorderedAccounts
                }
            },

            showAccounts() {
                return this.accounts.length > 0 && !this.showGroupSwitch && !this.showGroupSelector ? true : false
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

        props: ['initialEditMode', 'toRefresh'],

        mounted() {

            // we don't have to fetch fresh data so we try to load them from localstorage to avoid display latency
            if( !this.toRefresh && !this.$route.params.isFirstLoad ) {
                const accounts = this.$storage.get('accounts', null) // use null as fallback if localstorage is empty
                if( accounts ) this.accounts = accounts

                const groups = this.$storage.get('groups', null) // use null as fallback if localstorage is empty
                if( groups ) this.groups = groups
            }

            // we fetch fresh data whatever. The user will be notified to reload the page if there are any data changes
            this.fetchAccounts()
            this.fetchGroups()

            // stop OTP generation on modal close
            this.$on('modalClose', function() {
                console.log('modalClose triggered')
                this.$refs.TokenDisplayer.clearOTP()
            });

        },

        components: {
            Modal,
            TokenDisplayer,
            draggable,
        },

        methods: {

            /**
             * Route user to the appropriate submitting view
             */
            start() {
                if( this.$root.appSettings.useDirectCapture && this.$root.appSettings.defaultCaptureMode === 'advancedForm' ) {
                    this.$router.push({ name: 'createAccount' })
                }
                else if( this.$root.appSettings.useDirectCapture && this.$root.appSettings.defaultCaptureMode === 'livescan' ) {
                    this.$router.push({ name: 'capture' })
                }
                else {
                    this.$router.push({ name: 'start' })
                }
            },

            /**
             * Fetch accounts from db
             */
            fetchAccounts(forceRefresh = false) {
                let accounts = []
                this.selectedAccounts = []

                this.axios.get('api/twofaccounts').then(response => {
                    response.data.forEach((data) => {
                        accounts.push({
                            id : data.id,
                            service : data.service,
                            account : data.account ? data.account : '-',
                            icon : data.icon,
                            isConsistent : data.isConsistent,
                            group_id : data.group_id,
                        })
                    })

                    if ( this.accounts.length > 0 && !objectEquals(accounts, this.accounts) && !forceRefresh ) {
                        this.$notify({ type: 'is-dark', text: '<span class="is-size-7">' + this.$t('commons.some_data_have_changed') + '</span><br /><a href="." class="button is-rounded is-warning is-small">' + this.$t('commons.reload') + '</a>', duration:-1, closeOnClick: false })
                    }
                    else if( this.accounts.length === 0 && accounts.length === 0 ) {
                        // No account yet, we force user to land on the start view.
                        this.$storage.set('accounts', this.accounts)
                        this.$router.push({ name: 'start' });
                    }
                    else {
                        this.accounts = accounts
                        this.$storage.set('accounts', this.accounts)
                    }
                })
            },

            /**
             * Show account with a generated token rotation
             */
            showAccount(account) {
                // In Edit mode clicking an account do not show the tokenDisplayer but select the account
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
                    this.$refs.TokenDisplayer.getToken(account.id)
                }
            },

            /**
             * Save the account order in db
             */
            saveOrder() {
                this.drag = false
                this.axios.patch('/api/twofaccounts/reorder', {orderedIds: this.accounts.map(a => a.id)})
            },

            /**
             * Delete accounts selected from the Edit mode
             */
            async destroyAccounts() {
                if(confirm(this.$t('twofaccounts.confirm.delete'))) {

                    let ids = []
                    this.selectedAccounts.forEach(id => ids.push(id))

                    // Backend will delete all accounts at the same time
                    await this.axios.delete('/api/twofaccounts/batch', {data: ids} )

                    // we fetch the accounts again to prevent the js collection being
                    // desynchronize from the backend php collection
                    this.fetchAccounts(true)
                }
            },

            /**
             * Move accounts selected from the Edit mode to another group
             */
            async moveAccounts() {

                let accountsIds = []
                this.selectedAccounts.forEach(id => accountsIds.push(id))

                // Backend will associate all accounts with the selected group in the same move
                await this.axios.patch('/api/group/accounts', {accountsIds: accountsIds, groupId: this.moveAccountsTo} )

                // we fetch the accounts again to prevent the js collection being
                // desynchronize from the backend php collection
                this.fetchAccounts(true)
                this.fetchGroups()
                this.showGroupSelector = false

            },

            /**
             * Get the existing group list
             */
            fetchGroups() {
                let groups = []

                this.axios.get('api/groups').then(response => {
                    response.data.forEach((data) => {
                        groups.push({
                            id : data.id,
                            name : data.name,
                            isActive: data.isActive,
                            count: data.twofaccounts_count
                        })
                    })

                    if ( !objectEquals(groups, this.groups) ) {
                        this.groups = groups
                    } 

                    this.$storage.set('groups', this.groups)
                })
            },

            /**
             * Set the provided group as the active group
             */
            setActiveGroup(id) {

                this.form.activeGroup = this.$root.appSettings.activeGroup = id

                if( this.$root.appSettings.rememberActiveGroup ) {
                    this.form.post('/api/settings/options', {returnError: true})
                    .then(response => {
                        // everything's fine
                    })
                    .catch(error => {
                        
                        this.$router.push({ name: 'genericError', params: { err: error.response } })
                    });
                }

                this.closeGroupSwitch()
            },

            /**
             * Toggle the group switch visibility
             */
            toggleGroupSwitch: function(event) {

                if (event) {
                    this.showGroupSwitch ? this.closeGroupSwitch() : this.openGroupSwitch()
                }
            },

            /**
             * show the group switch which allow to select a group to activate
             */
            openGroupSwitch: function(event) {

                this.showGroupSwitch = true
            },

            /**
             * hide the group switch
             */
            closeGroupSwitch: function(event) {

                this.showGroupSwitch = false
            },

            /**
             * Toggle the accounts list between View mode and Edit mode
             */
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