<template>
    <div>
        <!-- Group switch -->
        <div class="container groups" v-if="showGroupSwitch">
            <div class="columns is-centered">
                <div class="column is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                    <div class="columns is-multiline">
                        <div class="column is-full" v-for="group in groups" v-if="group.twofaccounts_count > 0" :key="group.id">
                            <button class="button is-fullwidth" :class="{'is-dark has-text-light is-outlined':$root.showDarkMode}" @click="setActiveGroup(group.id)">{{ group.name }}</button>
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
                    <button class="button is-rounded" :class="{'is-dark' : $root.showDarkMode}" @click="closeGroupSwitch()">{{ $t('commons.close') }}</button>
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
                            <button class="button is-fullwidth" :class="{'is-link' : moveAccountsTo === group.id, 'is-dark has-text-light is-outlined':$root.showDarkMode}" @click="moveAccountsTo = group.id">
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
                    <button class="button is-link is-rounded" @click="moveAccounts()">{{ $t('commons.move') }}</button>
                </p>
                <!-- Cancel button -->
                <p class="control">
                    <button class="button is-rounded" :class="{'is-dark' : $root.showDarkMode}" @click="showGroupSelector = false">{{ $t('commons.cancel') }}</button>
                </p>
            </vue-footer>
        </div>
        <!-- header -->
        <div class="header" v-if="this.showAccounts || this.showGroupSwitch">
            <div class="columns is-gapless is-mobile is-centered">
                <div class="column is-three-quarters-mobile is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                    <!-- search -->
                    <div role="search" class="field">
                        <div class="control has-icons-right">
                            <input ref="searchBox" id="txtSearch" type="search" tabindex="1" :aria-label="$t('commons.search')" :title="$t('commons.search')" class="input is-rounded is-search" v-model="search">
                            <span class="icon is-small is-right">
                                <font-awesome-icon :icon="['fas', 'search']"  v-if="!search" />
                                <button tabindex="1" :title="$t('commons.clear_search')" class="clear-selection delete" v-if="search" @click="search = '' "></button>
                            </span>
                        </div>
                    </div>
                    <!-- toolbar -->
                    <div v-if="editMode" class="toolbar has-text-centered">
                        <div class="columns">
                            <div class="column">
                                <!-- selected label -->
                                <span class="has-text-grey mr-1">{{ selectedAccounts.length }}&nbsp;{{ $t('commons.selected') }}</span>
                                <!-- deselect all -->
                                <button @click="clearSelected" class="clear-selection delete mr-4" :style="{visibility: selectedAccounts.length > 0 ? 'visible' : 'hidden'}" :title="$t('commons.clear_selection')"></button>
                                <!-- select all button -->
                                <button @click="selectAll" class="button mr-5 has-line-height p-1 is-ghost has-text-grey" :title="$t('commons.select_all')">
                                    <span>{{ $t('commons.all') }}</span>
                                    <font-awesome-icon class="ml-1" :icon="['fas', 'check-square']" />
                                </button>
                                <!-- sort asc/desc buttons -->
                                <button @click="sortAsc" class="button has-line-height p-1 is-ghost has-text-grey" :title="$t('commons.sort_ascending')">
                                    <font-awesome-icon :icon="['fas', 'sort-alpha-down']" />
                                </button>
                                <button @click="sortDesc" class="button has-line-height p-1 is-ghost has-text-grey" :title="$t('commons.sort_descending')">
                                    <font-awesome-icon :icon="['fas', 'sort-alpha-up']" />
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- group switch toggle -->
                    <div v-else class="has-text-centered">
                        <div class="columns">
                            <div class="column" v-if="!showGroupSwitch">
                                <button :title="$t('groups.show_group_selector')" tabindex="1" class="button is-text is-like-text" :class="{'has-text-grey' : !$root.showDarkMode}" @click.stop="toggleGroupSwitch">
                                    {{ activeGroupName }} ({{ filteredAccounts.length }})&nbsp;
                                    <font-awesome-icon  :icon="['fas', 'caret-down']" />
                                </button>
                            </div>
                            <div class="column" v-else>
                                <button :title="$t('groups.hide_group_selector')" tabindex="1" class="button is-text is-like-text" :class="{'has-text-grey' : !$root.showDarkMode}" @click.stop="toggleGroupSwitch">
                                    {{ $t('groups.select_accounts_to_show') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal -->
        <modal v-model="showTwofaccountInModal">
            <otp-displayer ref="OtpDisplayer"></otp-displayer>
        </modal>
        <!-- show accounts list -->
        <div class="container" v-if="this.showAccounts" :class="editMode ? 'is-edit-mode' : ''">
            <!-- accounts -->
            <!-- <vue-pull-refresh :on-refresh="onRefresh" :config="{
                errorLabel: 'error',
                startLabel: '',
                readyLabel: '',
                loadingLabel: 'refreshing'
                }" > -->
                <draggable v-model="filteredAccounts" @start="drag = true" @end="saveOrder" ghost-class="ghost" handle=".tfa-dots" animation="200" class="accounts">
                    <transition-group class="columns is-multiline" :class="{ 'is-centered': $root.userPreferences.displayMode === 'grid' }" type="transition" :name="!drag ? 'flip-list' : null">
                        <div :class="[$root.userPreferences.displayMode === 'grid' ? 'tfa-grid' : 'tfa-list']" class="column is-narrow" v-for="account in filteredAccounts" :key="account.id">
                            <div class="tfa-container">
        	                    <transition name="slideCheckbox">
        	                        <div class="tfa-cell tfa-checkbox" v-if="editMode">
        	                            <div class="field">
        	                                <input class="is-checkradio is-small" :class="$root.showDarkMode ? 'is-white':'is-info'" :id="'ckb_' + account.id" :value="account.id" type="checkbox" :name="'ckb_' + account.id" v-model="selectedAccounts">
        	                                <label tabindex="0" :for="'ckb_' + account.id" v-on:keypress.space.prevent="selectAccount(account.id)"></label>
        	                            </div>
        	                        </div>
        	                    </transition>
                                <div tabindex="0" class="tfa-cell tfa-content is-size-3 is-size-4-mobile" @click="showAccount(account)" @keyup.enter="showAccount(account)" role="button">  
                                    <div class="tfa-text has-ellipsis">
                                        <img :src="$root.appConfig.subdirectory + '/storage/icons/' + account.icon" v-if="account.icon && $root.userPreferences.showAccountsIcons" :alt="$t('twofaccounts.icon_for_account_x_at_service_y', {account: account.account, service: account.service})">
                                        {{ displayService(account.service) }}<font-awesome-icon class="has-text-danger is-size-5 ml-2" v-if="$root.appSettings.useEncryption && account.account === $t('errors.indecipherable')" :icon="['fas', 'exclamation-circle']" />
                                        <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey ">{{ account.account }}</span>
                                    </div>
                                </div>
        	                    <transition name="fadeInOut">
        	                        <div class="tfa-cell tfa-edit has-text-grey" v-if="editMode">
                                        <!-- <div class="tags has-addons"> -->
                                            <router-link :to="{ name: 'editAccount', params: { twofaccountId: account.id }}" class="tag is-rounded mr-1" :class="$root.showDarkMode ? 'is-dark' : 'is-white'">
                                            {{ $t('commons.edit') }}
                                            </router-link>
                                            <router-link :to="{ name: 'showQRcode', params: { twofaccountId: account.id }}" class="tag is-rounded" :class="$root.showDarkMode ? 'is-dark' : 'is-white'" :title="$t('twofaccounts.show_qrcode')">
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
            <vue-footer :showButtons="true" :editMode="editMode" v-on:exit-edit="setEditModeTo(false)">
                <!-- New item buttons -->
                <p class="control" v-if="!editMode">
                    <button class="button is-link is-rounded is-focus" @click="start">
                        <span>{{ $t('commons.new') }}</span>
                        <span class="icon is-small">
                            <font-awesome-icon :icon="['fas', 'qrcode']" />
                        </span>
                    </button>
                </p>
                <!-- Manage button -->
                <p class="control" v-if="!editMode">
                    <button class="button is-rounded" :class="{'is-dark' : $root.showDarkMode}" @click="setEditModeTo(true)">{{ $t('commons.manage') }}</button>
                </p>
                <!-- move button -->
                <p class="control" v-if="editMode">
                    <button 
                        :disabled='selectedAccounts.length == 0' class="button is-rounded" 
                        :class="[{'is-outlined': $root.showDarkMode||selectedAccounts.length == 0}, selectedAccounts.length == 0 ? 'is-dark': 'is-link']" 
                        @click="showGroupSelector = true"
                        :title="$t('groups.move_selected_to_group')" >
                            {{ $t('commons.move') }}
                    </button>
                </p>
                <!-- delete button -->
                <p class="control" v-if="editMode">
                    <button 
                        :disabled='selectedAccounts.length == 0' class="button is-rounded" 
                        :class="[{'is-outlined': $root.showDarkMode||selectedAccounts.length == 0}, selectedAccounts.length == 0 ? 'is-dark': 'is-link']" 
                        @click="destroyAccounts" >
                            {{ $t('commons.delete') }}
                    </button>
                </p>
                <!-- export button -->
                <p class="control" v-if="editMode">
                    <button 
                        :disabled='selectedAccounts.length == 0' class="button is-rounded" 
                        :class="[{'is-outlined': $root.showDarkMode||selectedAccounts.length == 0}, selectedAccounts.length == 0 ? 'is-dark': 'is-link']" 
                        @click="exportAccounts" 
                        :title="$t('twofaccounts.export_selected_to_json')" >
                            {{ $t('commons.export') }}
                    </button>
                </p>
            </vue-footer>
        </div>
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
     *  - {{OTP}} generation
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
     *    ~ 'userPreferences.showAccountsIcons' toggle the icon visibility
     *    ~ 'userPreferences.displayMode' change the account appearance
     *
     *  Input : 
     *  - The 'initialEditMode' props : allows to load the view directly in Edit mode
     *  
     */

    import Modal from '../components/Modal'
    import OtpDisplayer from '../components/OtpDisplayer'
    import draggable from 'vuedraggable'
    import Form from './../components/Form'
    import objectEquals from 'object-equals'
    import { saveAs } from 'file-saver';

    export default {
        data(){
            return {
                accounts : [],
                groups : [],
                selectedAccounts: [],
                search: '',
                editMode: this.initialEditMode,
                drag: false,
                showTwofaccountInModal : false,
                showGroupSwitch: false,
                showGroupSelector: false,
                moveAccountsTo: false,
                form: new Form({
                    value: this.$root.userPreferences.activeGroup,
                }),
            }
        },

        computed: {
            /**
             * The actual list of displayed accounts
             */
            filteredAccounts: {
                get: function() {

                    return this.accounts.filter(
                        item => {
                            if( parseInt(this.$root.userPreferences.activeGroup) > 0 ) {
                                return ((item.service ? item.service.toLowerCase().includes(this.search.toLowerCase()) : false) || 
                                    item.account.toLowerCase().includes(this.search.toLowerCase())) && 
                                    (item.group_id == parseInt(this.$root.userPreferences.activeGroup))
                            }
                            else {
                                return ((item.service ? item.service.toLowerCase().includes(this.search.toLowerCase()) : false) || 
                                    item.account.toLowerCase().includes(this.search.toLowerCase()))
                            }
                        }
                    );
                },
                set: function(reorderedAccounts) {
                    this.accounts = reorderedAccounts
                }
            },

            /**
             * Returns whether or not the accounts should be displayed
            */
            showAccounts() {
                return this.accounts.length > 0 && !this.showGroupSwitch && !this.showGroupSelector ? true : false
            },

            /**
             * Returns the name of a group
             */
            activeGroupName() {
                let g = this.groups.find(el => el.id === parseInt(this.$root.userPreferences.activeGroup))

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

            document.addEventListener('keydown', this.keyListener)

            // we don't have to fetch fresh data so we try to load them from localstorage to avoid display latency
            if( !this.toRefresh && !this.$route.params.isFirstLoad ) {
                const accounts = this.$storage.get('accounts', null) // use null as fallback if localstorage is empty
                if( accounts ) this.accounts = accounts

                const groups = this.$storage.get('groups', null) // use null as fallback if localstorage is empty
                if( groups ) this.groups = groups
            }

            // we fetch fresh data whatever. The user will be notified to reload the page if there are any data changes
            this.fetchAccounts()

            // stop OTP generation on modal close
            this.$on('modalClose', function() {
                this.$refs.OtpDisplayer.clearOTP()
            });

        },

        destroyed () {
            document.removeEventListener('keydown', this.keyListener)
        },

        components: {
            Modal,
            OtpDisplayer,
            draggable,
        },

        methods: {

            /**
             * Route user to the appropriate submitting view
             */
            start() {
                if( this.$root.userPreferences.useDirectCapture && this.$root.userPreferences.defaultCaptureMode === 'advancedForm' ) {
                    this.$router.push({ name: 'createAccount' })
                }
                else if( this.$root.userPreferences.useDirectCapture && this.$root.userPreferences.defaultCaptureMode === 'livescan' ) {
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

                this.axios.get('api/v1/twofaccounts').then(response => {
                    response.data.forEach((data) => {
                        accounts.push(data)
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
                        this.fetchGroups()
                    }
                })
            },

            /**
             * Show account with a generated {{OTP}} rotation
             */
            showAccount(account) {
                // In Edit mode clicking an account do not show the otpDisplayer but select the account
                if(this.editMode) {
                    this.selectAccount(account.id)
                }
                else {
                    this.$refs.OtpDisplayer.show(account.id)
                }
            },


            /**
             * Select an account while in edit mode
             */
            selectAccount(accountId) {
                for (var i=0 ; i<this.selectedAccounts.length ; i++) {
                    if ( this.selectedAccounts[i] === accountId ) {
                        this.selectedAccounts.splice(i,1);
                        return
                    }
                }

                this.selectedAccounts.push(accountId)
            },

            /**
             * Get a fresh OTP for the provided account
             */
            getOTP(accountId) {
                this.axios.get('api/v1/twofaccounts/' + accountId + '/otp').then(response => {
                    this.$notify({ type: 'is-success', text: this.$t('commons.copied_to_clipboard')+ ' '+response.data })
                })
            },


            /**
             * Save the account order in db
             */
            saveOrder() {
                this.drag = false
                this.axios.post('/api/v1/twofaccounts/reorder', {orderedIds: this.accounts.map(a => a.id)})
            },

            /**
             * Delete accounts selected from the Edit mode
             */
            async destroyAccounts() {
                if(confirm(this.$t('twofaccounts.confirm.delete'))) {

                    let ids = []
                    this.selectedAccounts.forEach(id => ids.push(id))

                    let that = this
                    await this.axios.delete('/api/v1/twofaccounts?ids=' + ids.join())
                        .then(response => {
                            ids.forEach(function(id) {
                                that.accounts = that.accounts.filter(a => a.id !== id)
                            })
                            this.$notify({ type: 'is-success', text: this.$t('twofaccounts.accounts_deleted') })
                        })
                        
                    // we fetch the accounts again to prevent the js collection being
                    // desynchronize from the backend php collection
                    this.fetchAccounts(true)
                }
            },

            /**
             * Export selected accounts
             */
            exportAccounts() {
                let ids = []
                this.selectedAccounts.forEach(id => ids.push(id))

                this.axios.get('/api/v1/twofaccounts/export?ids=' + ids.join(), {responseType: 'blob'})
                    .then((response) => {
                        var blob = new Blob([response.data], {type: "application/json;charset=utf-8"});
                        saveAs.saveAs(blob, "2fauth_export.json");
                    })
            },

            /**
             * Move accounts selected from the Edit mode to another group or withdraw them
             */
            async moveAccounts() {

                let accountsIds = []
                this.selectedAccounts.forEach(id => accountsIds.push(id))

                // Backend will associate all accounts with the selected group in the same move
                // or withdraw the accounts if destination is 'no group' (id = 0)
                if(this.moveAccountsTo === 0) {
                    await this.axios.patch('/api/v1/twofaccounts/withdraw?ids=' + accountsIds.join() )
                }
                else await this.axios.post('/api/v1/groups/' + this.moveAccountsTo + '/assign', {ids: accountsIds} )

                // we fetch the accounts again to prevent the js collection being
                // desynchronize from the backend php collection
                this.fetchAccounts(true)
                this.showGroupSelector = false

                this.$notify({ type: 'is-success', text: this.$t('twofaccounts.accounts_moved') })

            },

            /**
             * Get the existing group list
             */
            fetchGroups() {
                let groups = []

                this.axios.get('api/v1/groups').then(response => {
                    response.data.forEach((data) => {
                        groups.push(data)
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

                // In memomry saving
                this.form.value = this.$root.userPreferences.activeGroup = id

                // In db saving if the user set 2FAuth to memorize the active group
                if( this.$root.userPreferences.rememberActiveGroup ) {
                    this.form.put('/api/v1/user/preferences/activeGroup', {returnError: true})
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
                this.selectedAccounts = []
                this.editMode = state
            },

            /**
             * 
             */
            displayService(service) {
                return service ? service : this.$t('twofaccounts.no_service')
            },

            /**
             * 
             */
            clearSelected() {
                this.selectedAccounts = []
            },

            /**
             * 
             */
            selectAll() {
                if(this.editMode) {
                    let that = this
                    this.accounts.forEach(function(account) {
                        if ( !that.selectedAccounts.includes(account.id) ) {
                            that.selectedAccounts.push(account.id)
                        }
                    })
                }
            },

            /**
             * 
             */
            sortAsc() {
                this.accounts.sort((a, b) => a.service > b.service ? 1 : -1)
                this.saveOrder()
            },

            /**
             * 
             */
            sortDesc() {
                this.accounts.sort((a, b) => a.service < b.service ? 1 : -1)
                this.saveOrder()
            },

            /**
             * 
             */
            keyListener : function(e) {
                if (e.key === "f" && (e.ctrlKey || e.metaKey)) {
                    e.preventDefault();
                    const searchBox = document.getElementById('txtSearch');
                    if (searchBox != undefined) {
                        searchBox.focus()
                    }
                }
            },
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