<script setup>
    import twofaccountService from '@/services/twofaccountService'
    import groupService from '@/services/groupService'
    import Spinner from '@/components/Spinner.vue'
    import TotpLooper from '@/components/TotpLooper.vue'
    import GroupSwitch from '@/components/GroupSwitch.vue'
    import DestinationGroupSelector from '@/components/DestinationGroupSelector.vue'
    import SearchBox from '@/components/SearchBox.vue'
    import Toolbar from '@/components/Toolbar.vue'
    import OtpDisplay from '@/components/OtpDisplay.vue'
    import ActionButtons from '@/components/ActionButtons.vue'
    import { UseColorMode } from '@vueuse/components'
    import { useUserStore } from '@/stores/user'
    import { useNotifyStore } from '@/stores/notify'
    import { useBusStore } from '@/stores/bus'
    import { useTwofaccounts } from '@/stores/twofaccounts'
    import { useGroups } from '@/stores/groups'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useDisplayablePassword } from '@/composables/helpers'

    const $2fauth = inject('2fauth')
    const notify = useNotifyStore()
    const user = useUserStore()
    const bus = useBusStore()
    const router = useRouter()
    const appSettings = useAppSettingsStore()
    const { copy, copied } = useClipboard({ legacy: true })
    const twofaccounts = useTwofaccounts()
    const groups = useGroups()

    const showOtpInModal = ref(false)
    const showGroupSwitch = ref(false)
    const showDestinationGroupSelector = ref(false)
    const isDragging = ref(false)

    const otpDisplay = ref(null)

    watch(showOtpInModal, (val) => {
        if (val == false) {
            otpDisplay.value?.clearOTP()
        }
    })

    /**
     * Returns whether or not the accounts should be displayed
    */
    const showAccounts = computed(() => {
        return twofaccounts.isNotEmpty && !showGroupSwitch.value && !showDestinationGroupSelector.value
    })

    onMounted(() => {
        // we don't have to fetch fresh data so we try to load them from localstorage to avoid display latency
        // if( user.preferences.getOtpOnRequest && !this.toRefresh && !this.$route.params.isFirstLoad ) {
        //     const accounts = this.$storage.get('accounts', null) // use null as fallback if localstorage is empty
        //     if( accounts ) this.accounts = accounts

        //     const groups = this.$storage.get('groups', null) // use null as fallback if localstorage is empty
        //     if( groups ) this.groups = groups
        // }

        // We fetch fresh data whatever. The user will be notified to reload the page if there are any data changes
        twofaccounts.refresh()
        groups.refresh()

        // if (twofaccounts.count === 0) {
        //     // No account yet, we force user to land on the start view.
        //     router.push({ name: 'start' });
        // }

        // stop OTP generation on modal close
        // this.$on('modalClose', function() {
        //     this.$refs.OtpDisplayer.clearOTP()
        // })
    })

    /**
     * Runs some updates after accounts assignement/withdrawal
     */
    function postGroupAssignementUpdate() {
        // we fetch the accounts again to prevent the js collection being
        // desynchronize from the backend php collection
        fetchAccounts(true)
        notify.info({ text: trans('twofaccounts.accounts_moved') })
    }

    /**
     * Shows rotating OTP for the provided account
     */
    function showOTP(account) {
        // In Management mode, clicking an account does not show the otpDisplay, it selects the account
        if(bus.inManagementMode) {
            twofaccounts.select(account.id)
        }
        else {
            showOtpInModal.value = true
            otpDisplay.value.show(account.id);
        }
    }

    /**
     * Shows an OTP in a modal or directly copies it to the clipboard
     */
    function showOrCopy(account) {
        if (!user.preferences.getOtpOnRequest && account.otp_type.includes('totp')) {
            copyToClipboard(account.otp.password)
        }
        else {
            showOTP(account)
        }
    }

    /**
     * Copies a string to the clipboard
     */
    function copyToClipboard (password) {
        copy(password)

        if (copied) {
            if(user.preferences.kickUserAfter == -1) {
                user.logout()
            }
            notify.info({ text: trans('commons.copied_to_clipboard') })
        }
    }

    /**
     * Gets a fresh OTP from backend and copies it
     */
    async function getAndCopyOTP(account) {
        twofaccountService.getOtpById(account.id).then(response => {
            let otp = response.data
            copyToClipboard(otp.password)

            if (otp.otp_type == 'hotp') {
                let hotpToIncrement = accounts.value.find((acc) => acc.id == account.id)
                
                // TODO : à koi ça sert ?
                if (hotpToIncrement != undefined) {
                    hotpToIncrement.counter = otp.counter
                }
            }
        })
    }
</script>

<template>
    <div>
        <GroupSwitch v-if="showGroupSwitch" v-model:showGroupSwitch="showGroupSwitch" />
        <DestinationGroupSelector
            v-if="showDestinationGroupSelector"
            v-model:showDestinationGroupSelector="showDestinationGroupSelector"
            v-model:selectedAccountsIds="twofaccounts.selectedIds"
            @account-moved="postGroupAssignementUpdate">
        </DestinationGroupSelector>
        <!-- header -->
        <div class="header" v-if="showAccounts || showGroupSwitch">
            <div class="columns is-gapless is-mobile is-centered">
                <div class="column is-three-quarters-mobile is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                    <!-- search -->
                    <SearchBox v-model:keyword="twofaccounts.filter"/>
                    <!-- toolbar -->
                    <Toolbar v-if="bus.inManagementMode"
                        :selectedCount="twofaccounts.selectedCount"
                        @clear-selected="twofaccounts.selectNone()"
                        @select-all="twofaccounts.selectAll()"
                        @sort-asc="twofaccounts.sortAsc()"
                        @sort-desc="twofaccounts.sortDesc()">
                    </Toolbar>
                    <!-- group switch toggle -->
                    <div v-else class="has-text-centered">
                        <div class="columns">
                            <UseColorMode v-slot="{ mode }">
                                <div class="column" v-if="showGroupSwitch">
                                    <button id="btnHideGroupSwitch" :title="$t('groups.hide_group_selector')" tabindex="1" class="button is-text is-like-text" :class="{'has-text-grey' : mode != 'dark'}" @click.stop="showGroupSwitch = !showGroupSwitch">
                                        {{ $t('groups.select_accounts_to_show') }}
                                    </button>
                                </div>
                                <div class="column" v-else>
                                    <button id="btnShowGroupSwitch" :title="$t('groups.show_group_selector')" tabindex="1" class="button is-text is-like-text" :class="{'has-text-grey' : mode != 'dark'}" @click.stop="showGroupSwitch = !showGroupSwitch">
                                        {{ groups.current }} ({{ twofaccounts.filteredCount }})&nbsp;
                                        <FontAwesomeIcon  :icon="['fas', 'caret-down']" />
                                    </button>
                                </div>
                            </UseColorMode>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal -->
        <Modal v-model="showOtpInModal">
            <OtpDisplay
                ref="otpDisplay"
                @please-close-me="showOtpInModal = false">
            </OtpDisplay>
        </Modal>
        <!-- show accounts list -->
        <div class="container" v-if="showAccounts" :class="bus.inManagementMode ? 'is-edit-mode' : ''">
            <!-- accounts -->
            <!-- <vue-pull-refresh :on-refresh="onRefresh" :config="{
                errorLabel: 'error',
                startLabel: '',
                readyLabel: '',
                loadingLabel: 'refreshing'
                }" > -->
                <!-- <draggable v-model="twofaccounts.filtered" @start="isDragging = true" @end="() => {isDragging = false; twofaccounts.saveOrder()}" ghost-class="ghost" handle=".tfa-dots" animation="200" class="accounts"> -->
                <div class="accounts">
                    <!-- <transition-group class="columns is-multiline" :class="{ 'is-centered': user.preferences.displayMode === 'grid' }" type="transition" :name="!isDragging ? 'flip-list' : null"> -->
                    <span class="columns is-multiline">
                        <div :class="[user.preferences.displayMode === 'grid' ? 'tfa-grid' : 'tfa-list']" class="column is-narrow" v-for="account in twofaccounts.filtered" :key="account.id">
                            <div class="tfa-container">
        	                    <!-- <transition name="slideCheckbox"> -->
        	                        <div class="tfa-cell tfa-checkbox" v-if="bus.inManagementMode">
        	                            <div class="field">
                                            <UseColorMode v-slot="{ mode }">
                                                <input class="is-checkradio is-small" :class="mode == 'dark' ? 'is-white':'is-info'" :id="'ckb_' + account.id" :value="account.id" type="checkbox" :name="'ckb_' + account.id" v-model="twofaccounts.selectedIds">
                                            </UseColorMode>
        	                                <label tabindex="0" :for="'ckb_' + account.id" v-on:keypress.space.prevent="twofaccounts.select(account.id)"></label>
        	                            </div>
        	                        </div>
        	                    <!-- </transition> -->
                                <div tabindex="0" class="tfa-cell tfa-content is-size-3 is-size-4-mobile" @click.exact="showOrCopy(account)" @keyup.enter="showOrCopy(account)" @click.ctrl="getAndCopyOTP(account)" role="button">  
                                    <div class="tfa-text has-ellipsis">
                                        <img class="tfa-icon" :src="$2fauth.config.subdirectory + '/storage/icons/' + account.icon" v-if="account.icon && user.preferences.showAccountsIcons" :alt="$t('twofaccounts.icon_for_account_x_at_service_y', {account: account.account, service: account.service})">
                                        {{ account.service ? account.service : $t('twofaccounts.no_service') }}<FontAwesomeIcon class="has-text-danger is-size-5 ml-2" v-if="appSettings.useEncryption && account.account === $t('errors.indecipherable')" :icon="['fas', 'exclamation-circle']" />
                                        <span class="has-ellipsis is-family-primary is-size-6 is-size-7-mobile has-text-grey ">{{ account.account }}</span>
                                    </div>
                                </div>
        	                    <!-- <transition name="popLater"> -->
                                    <div v-show="user.preferences.getOtpOnRequest == false && !bus.inManagementMode" class="has-text-right">
                                        <span v-if="account.otp != undefined && isRenewingOTPs" class="has-nowrap has-text-grey has-text-centered is-size-5">
                                            <FontAwesomeIcon :icon="['fas', 'circle-notch']" spin />
                                        </span>
                                        <span v-else-if="account.otp != undefined && isRenewingOTPs == false" class="always-on-otp is-clickable has-nowrap has-text-grey is-size-5 ml-4" @click="copyToClipboard(account.otp.password)" @keyup.enter="copyToClipboard(account.otp.password)" :title="$t('commons.copy_to_clipboard')">
                                            {{ useDisplayablePassword(account.otp.password) }}
                                        </span>
                                        <span v-else>
                                            <!-- get hotp button -->
                                            <UseColorMode v-slot="{ mode }">
                                                <button class="button tag" :class="mode == 'dark' ? 'is-dark' : 'is-white'" @click="showOTP(account)" :title="$t('twofaccounts.import.import_this_account')">
                                                    {{ $t('commons.generate') }}
                                                </button>
                                            </UseColorMode>
                                        </span>
                                        <!-- <dots v-if="account.otp_type.includes('totp')" @hook:mounted="turnDotsOnFromCache(account.period)" :class="'condensed'" :ref="'dots_' + account.period"></dots> -->
                                    </div>
        	                    <!-- </transition> -->
        	                    <!-- <transition name="fadeInOut"> -->
        	                        <div class="tfa-cell tfa-edit has-text-grey" v-if="bus.inManagementMode">
                                        <UseColorMode v-slot="{ mode }">
                                            <RouterLink :to="{ name: 'editAccount', params: { twofaccountId: account.id }}" class="tag is-rounded mr-1" :class="mode == 'dark' ? 'is-dark' : 'is-white'">
                                                {{ $t('commons.edit') }}
                                            </RouterLink>
                                            <RouterLink :to="{ name: 'showQRcode', params: { twofaccountId: account.id }}" class="tag is-rounded" :class="mode == 'dark' ? 'is-dark' : 'is-white'" :title="$t('twofaccounts.show_qrcode')">
                                                <FontAwesomeIcon :icon="['fas', 'qrcode']" />
                                            </RouterLink>
                                        </UseColorMode>
        	                        </div>
        	                    <!-- </transition> -->
                                <!-- <transition name="fadeInOut"> -->
                                    <div class="tfa-cell tfa-dots has-text-grey" v-if="bus.inManagementMode">
                                        <FontAwesomeIcon :icon="['fas', 'bars']" />
                                    </div>
                                <!-- </transition> -->
                            </div>
                        </div>
                    </span>
                    <!-- </transition-group> -->
                </div>
                <!-- </draggable> -->
            <!-- </vue-pull-refresh> -->
            <VueFooter :showButtons="true" v-on:management-mode-exited="twofaccounts.selectNone()">
                <ActionButtons
                    v-model:inManagementMode="bus.inManagementMode"
                    :areDisabled="twofaccounts.hasNoneSelected"
                    @move-button-clicked="showDestinationGroupSelector = true"
                    @delete-button-clicked="twofaccounts.deleteSelected()"
                    @export-button-clicked="twofaccounts.export()">
                </ActionButtons>
            </VueFooter>
        </div>
        <!-- totp loopers -->
        <!-- <span v-if="!user.preferences.getOtpOnRequest">
            <TotpLooper
                v-for="period in periods"
                :key="period.period"
                :period="period.period"
                :generated_at="period.generated_at"
                v-on:loop-ended="updateTotps(period.period)"
                v-on:loop-started="setCurrentStep(period.period, $event)"
                v-on:stepped-up="setCurrentStep(period.period, $event)"
                ref="loopers"
            ></TotpLooper>
        </span> -->
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
     *
     */


    // export default {
    //     data(){
    //         return {
    //             stepIndexes: {},
    //             isRenewingOTPs: false
    //         }
    //     },

        // computed: {
            /**
             * Returns an array of all totp periods present in the twofaccounts list
             */
            // periods() {
            //     return !user.preferences.getOtpOnRequest ?
            //         this.accounts.filter(acc => acc.otp_type == 'totp').map(function(item) {
            //             return {period: item.period, generated_at: item.otp.generated_at}
            //             // return item.period
            //         }).filter((value, index, self) => index === self.findIndex((t) => (
            //             t.period === value.period
            //         ))).sort()
            //         : null
            // },
        // },

        // props: ['toRefresh'],
     
        // methods: {
            /**
             * 
             */
            // setCurrentStep(period, stepIndex) {
            //     this.stepIndexes[period] = stepIndex
            //     this.turnDotsOn(period, stepIndex)
            // },

            /**
             * 
             */
            // turnDotsOnFromCache(period, stepIndex) {
            //     if (this.stepIndexes[period] != undefined) {
            //         this.turnDotsOn(period, this.stepIndexes[period])
            //     }
            // },

            /**
             * 
             */
            // turnDotsOn(period, stepIndex) {
            //     this.$refs['dots_' + period].forEach((dots) => {
            //         dots.turnOn(stepIndex)
            //     })
            // },

            /**
             * Fetch all accounts set with the given period to get fresh OTPs
             */
            // async updateTotps(period) {
            //     this.isRenewingOTPs = true
            //     this.axios.get('api/v1/twofaccounts?withOtp=1&ids=' + this.accountIdsWithPeriod(period).join(',')).then(response => {
            //         response.data.forEach((account) => {
            //             const index = this.accounts.findIndex(acc => acc.id === account.id)
            //             this.accounts[index].otp = account.otp
                        
            //             this.$refs.loopers.forEach((looper) => {
            //                 if (looper.period == period) {
            //                     looper.generatedAt = account.otp.generated_at
            //                     this.$nextTick(() => {
            //                         looper.startLoop()
            //                     })
            //                 }
            //             })
            //         })
            //     })
            //     .finally(() => {
            //         this.isRenewingOTPs = false
            //     })
            // },

            /**
             * Return an array of all accounts (ids) set with the given period
             */
            // accountIdsWithPeriod(period) {
            //     return this.accounts.filter(a => a.period == period).map(item => item.id)
            // },

            /**
             * Get a fresh OTP for the provided account
             */
            // getOTP(accountId) {
            //     this.axios.get('api/v1/twofaccounts/' + accountId + '/otp').then(response => {
            //         this.$notify({ type: 'is-success', text: this.$t('commons.copied_to_clipboard')+ ' '+response.data })
            //     })
            // },

    //     }
    // };

</script>

<style scoped>
    .flip-list-move {
      transition: transform 0.5s;
    }

    .ghost {
      opacity: 1;
      /*background: hsl(0, 0%, 21%);*/
    }
</style>
