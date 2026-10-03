<script setup>
    import twofaccountService from '@/services/twofaccountService'
    import shareService from '@/services/shareService'
    import DestinationGroupSelector from '@/components/DestinationGroupSelector.vue'
    import Toolbar from '@/components/Toolbar.vue'
    import ActionButtons from '@/components/ActionButtons.vue'
    import ExportButtons from '@/components/ExportButtons.vue'
    import { UseColorMode } from '@vueuse/components'
    import { useUserStore } from '@/stores/user'
    import { useSettingsBreakpoints } from '@/composables/breakpoints'
    import {
        useNotify,
        SearchBox,
        GroupCallToSwitch,
        GroupChips,
        GroupSwitch,
        OtpDisplay,
        Dots,
        DotsController,
        TwoFAccountList,
        TwoFAccountListItem
    } from '@2fauth/ui'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useBusStore } from '@/stores/bus'
    import { useTwofaccounts } from '@/stores/twofaccounts'
    import { useGroups } from '@/stores/groups'
    import { useSortable, moveArrayElement } from '@vueuse/integrations/useSortable'
    import { useI18n } from 'vue-i18n'
    import { useErrorHandler } from '@2fauth/stores'
    import {
        LucideMenu,
        LucidePencil,
        LucideQrCode,
        LucideTrash2,
        LucideX
    } from '@lucide/vue'

    const errorHandler = useErrorHandler()
    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const router = useRouter()
    const notify = useNotify()
    const user = useUserStore()
    const bus = useBusStore()
    const { copy, copied } = useClipboard({ legacy: true })
    const twofaccounts = useTwofaccounts()
    const groups = useGroups()
    const appSettings = useAppSettingsStore()
    const { isDesktop } = useSettingsBreakpoints()

    const showOtpInModal = ref(false)
    const showExportFormatSelector = ref(false)
    const showGroupSwitch = ref(false)
    const showDestinationGroupSelector = ref(false)
    const isDragging = ref(false)
    const renewedPeriod = ref(null)
    const opacities = ref({})
    const showFooterMenu = ref(false)
    const visibleAccount = ref(null)
    const isFetchingShares = ref(null)
    const twofaccountSpecificShares = ref({})

    const otpDisplay = ref(null)
    const accountParams = ref({
        otp_type: '',
        account: '',
        service: '',
        icon: '',
        secret: '',
        digits: null,
        algorithm: '',
        period: null,
        counter: null,
        image: '',
        is_favorite: false
    })
    const dotsControllers = ref([])
    const dotsRefs = ref([])

    let stopSortable

    watch(showOtpInModal, (val) => {
        if (val == false) {
            otpDisplay.value?.clearOTP()
        }
    })

    watch(
        () => twofaccounts.items,
        (val) => {
            stopSortable
            if (bus.inManagementMode) {
                setSortable()
            }
        }
    )

    watch(
        () => bus.inManagementMode,
        (val) => {
            stopSortable
            if (val) {
                setSortable()
            }
        }
    )

    /**
     * Returns whether or not the accounts should be displayed
    */
    const showAccounts = computed(() => {
        return !twofaccounts.isEmpty && !showGroupSwitch.value && !showDestinationGroupSelector.value
    })

    /**
     * Returns whether or not the desktop table layout must be used
    */
    const showDesktopTable = computed(() => {
        return isDesktop.value && user.preferences.useDesktopTableLayout
    })

    onMounted(async () => {
        // This SFC is reached only if the user has some twofaccounts (see the starter middleware).
        // This allows to display accounts without latency.
        //
        // We sync the store with the backend again to
        if (! user.preferences.getOtpOnRequest) {
            updateTotps()
        }
        else {
            twofaccounts.fetch().then(() => {
                if (twofaccounts.backendWasNewer) {
                    notify.info({ text: t('notification.data_refreshed_to_reflect_server_changes'), duration: 10000 })
                }
            })
        }
        
        groups.fetch()

        if (! user.preferences.enableFavorites) {
            twofaccounts.showFavoritesOnly = false
        }
    })

    // Enables the sortable behaviour of the twofaccounts list
    function setSortable() {
        const { stop } = useSortable('#dv', twofaccounts.filtered, {
            animation: 200,
            handle: '.drag-handle',
            onUpdate: (e) => {
                const movedId = twofaccounts.filtered[e.oldIndex].id
                const inItemsIndex = twofaccounts.items.findIndex(item => item.id == movedId)
                moveArrayElement(twofaccounts.items, inItemsIndex, e.newIndex)

                nextTick(() => {
                    twofaccounts.saveOrder('free')
                })
            }
        })
        stopSortable = stop
    }

    /**
     * Runs some updates after accounts assignement/withdrawal
     */
    function postGroupAssignementUpdate() {
        // we fetch the accounts again to prevent the js collection being
        // desynchronize from the backend php collection
        twofaccounts.fetch()
        twofaccounts.selectNone()
        showDestinationGroupSelector.value = false
        notify.success({ text: t('notification.accounts_moved') })
    }

    /**
     * Shows rotating OTP for the provided account
     */
    function showOTP(account) {
        // Data that should be displayed quickly by the OtpDisplay
        // component are passed using props.
        accountParams.value.otp_type = account.otp_type
        accountParams.value.service = account.service
        accountParams.value.account = account.account
        accountParams.value.icon = account.icon
        accountParams.value.is_favorite = account.is_favorite

        visibleAccount.value = account

        nextTick().then(() => {
            showOtpInModal.value = true
            otpDisplay.value.show(account.id);
        })
    }

    /**
     * Shows an OTP in a modal or directly copies it to the clipboard
     */
    function showOrCopy(account) {
        // In Management mode, clicking an account does not show/copy, it selects the account
        if(bus.inManagementMode) {
            selectAccount(account)
        }
        else {
            if (!user.preferences.getOtpOnRequest && account.otp_type.includes('totp')) {
                copyToClipboard(account.otp.password)
            }
            else {
                showOTP(account)
            }
        }
    }

    /**
     * Copies a string to the clipboard
     */
    function copyToClipboard (password) {
        copy(password)

        if (copied) {
            if (user.preferences.kickUserAfter == -1) {
                user.logout({ kicked: true})
            }
            if (user.preferences.clearSearchOnCopy) {
                twofaccounts.filter = ''
            }
            if (user.preferences.viewDefaultGroupOnCopy) {
                user.preferences.activeGroup = user.preferences.defaultGroup == -1 ?
                    user.preferences.activeGroup
                    : user.preferences.defaultGroup
            }
            
            notify.success({ text: t('notification.copied_to_clipboard') })
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
                let hotpToIncrement = twofaccounts.items.find((acc) => acc.id == account.id)
                
                // TODO : à koi ça sert ?
                if (hotpToIncrement != undefined) {
                    hotpToIncrement.counter = otp.counter
                }
            }
        })
    }

    /**
     * Dragging start
     */
    function onStart() {
        isDragging.value = true
    }

    /**
     * Dragging end
     */
    function onEnd() {
        isDragging.value = false
    }

    /**
     * Turns dots On for all dots components that match the provided period
     */
     function turnDotsOn(period, stepIndex) {
        dotsRefs.value
            .filter((dots) => dots.props.period == period || period == undefined)
            .forEach((dot) => {
                dot.turnOn(stepIndex)
        })

        // The is-opacity-* classes are defined from 0 to 10 only.
        // TODO: Make the opacity refiner support variable number of steps (not only 10, see step_count)
        opacities.value[period] = 'is-opacity-' + stepIndex
    }

    /**
     * Turns dots Off for all dots components that match the provided period
     */
    function turnDotsOff(period) {
        dotsRefs.value
            .filter((dots) => dots.props.period == period || period == undefined)
            .forEach((dot) => {
                dot.turnOff()
        })
    }

    /**
     * Updates "Always On" OTPs for all TOTP accounts and (re)starts dots controllers
     */
    async function updateTotps(period) {
        let fetchPromise

        if (period == undefined) {
            renewedPeriod.value = -1
            fetchPromise = twofaccountService.getAll(true)
        } else {
            renewedPeriod.value = period
            fetchPromise = twofaccountService.getByIds(twofaccounts.accountIdsWithPeriod(period).join(','), true)
        }
        
        turnDotsOff(period)

        // We replace the current on screen passwords with the next_password to avoid having loaders.
        // The next_password will be confirmed with a new request to be synced with the backend no matter what.
        const totpAccountsWithNextPasswordInThePeriod = twofaccounts.items.filter((account) => account.otp_type.includes('totp') && account.period == period && account.otp.next_password)
        
        if (totpAccountsWithNextPasswordInThePeriod.length > 0) {
            totpAccountsWithNextPasswordInThePeriod.forEach((account) => {
                const index = twofaccounts.items.findIndex(acc => acc.id === account.id)
                if (twofaccounts.items[index].otp.next_password) {
                    twofaccounts.items[index].otp.password = twofaccounts.items[index].otp.next_password
                }
            })
            turnDotsOn(period, 0)
        }

        fetchPromise.then(response => {
            let generatedAt = 0

            // twofaccounts TOTP updates
            response.data.forEach((account) => {
                if (account.otp_type.includes('totp')) {
                    const index = twofaccounts.items.findIndex(acc => acc.id === account.id)
                    if (twofaccounts.items[index] == undefined) {
                        twofaccounts.items.push(account)
                    }
                    else twofaccounts.items[index].otp = account.otp
                    generatedAt = account.otp.generated_at
                }
            })

            // dots controllers restart at new timestamp
            dotsControllers.value.forEach((dotsController) => {
                if (dotsController.props.period == period || period == undefined) {
                    nextTick().then(() => {
                        dotsController.startStepping(generatedAt)
                    })
                }
            })
        })
        .finally(() => {
            renewedPeriod.value = null
        })
    }

    /**
     * Deletes given account
     */
    async function deleteAccount(accountId) {
        await twofaccounts.delete(accountId)

        if (twofaccounts.isEmpty) {
            bus.inManagementMode = false
            router.push({ name: 'start' })
        }

        nextTick().then(() => {
            showOtpInModal.value = false
            showFooterMenu.value = false
        })
    }

    /**
     * Deletes selected accounts
     */
    async function deleteAccounts() {
        await twofaccounts.deleteSelected()

        if (twofaccounts.isEmpty) {
            bus.inManagementMode = false
            router.push({ name: 'start' })
        }
    }

    /**
     * Exits from the Management mode
     */
    function exitManagementMode()
    {
        bus.inManagementMode = false
        twofaccounts.selectNone()
    }

    /**
     * Saves the active group to the backends
     */
    // TODO : Delegate this to the store or a global watcher
    function saveActiveGroup(newActiveGroupId) {
        // When invoked by GroupSwitch event,  newActiveGroupId should
        // be the same as user.preferences.activeGroup because of the v-model
        // binding.
        // When invoked by OtpDisplay we have to update the user preference too.
        if (user.preferences.activeGroup != newActiveGroupId) {
            user.preferences.activeGroup = newActiveGroupId
        }

        if( user.preferences.rememberActiveGroup) {
            userService.updatePreference('activeGroup', user.preferences.activeGroup)
        }
    }

    /**
     * Selects an account
     */
    function selectAccount(account) {
        twofaccounts.select(account.id)
    }

    /**
     * Selects an account
     */
    async function toggleOtpDisplayFavorite(accountId) {
        await twofaccounts.toggleIsFavorite(accountId)
        const account = twofaccounts.getById(accountId)

        if (account != undefined) {
            otpDisplay.value?.setFavorite(account.is_favorite)
        }
    }

    /**
     * Fetches list of specific user shares for a given account
     */
    function getTwofaccountShares(accountId) {
        isFetchingShares.value = accountId

        shareService.getShares(accountId).then(response => {
            if (response?.data?.specific_users.length > 0) {
                twofaccountSpecificShares.value[accountId] = response.data.specific_users
            }
        })
        .finally(() => {
            isFetchingShares.value = null
        })
    }

    /**
     * Unshare selected accounts
     */
    async function bulkUnshare() {
        if (appSettings.enableSharing && confirm(t('confirmation.bulk_unshare')) === true) {
            twofaccounts.selectedIds.forEach((accountId) => {
                const account = twofaccounts.getById(accountId)

                if (account != undefined && (account.is_shared_with_all || account.is_shared)) {
                    shareService.unshare(accountId).then(response => {
                        try {
                            const index = twofaccounts.items.findIndex(acc => acc.id === accountId)
                            delete twofaccounts.items[index].is_shared
                            delete twofaccounts.items[index].is_shared_with_all
                        } catch (error) {
                            console.error(error)
                        }
                    })
                }
            })
        }
    }

</script>

<template>
    <UseColorMode v-slot="{ mode }">
    <div>
        <StackLayout :shouldShrinkSubheader="user.preferences.useGroupChips">
            <template #header>
                <!-- header -->
                <div class="header" v-if="showAccounts || showGroupSwitch">
                    <div class="columns is-gapless is-mobile is-centered">
                        <div class="column is-three-quarters-mobile is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                            <!-- search -->
                            <SearchBox v-model:keyword="twofaccounts.filter"/>
                        </div>
                    </div>
                </div>
            </template>
            <template #subheader v-if="! showDestinationGroupSelector">
                <div v-if="!showGroupSwitch" class="is-flex is-flex-direction-row accounts-container"
                    :class="showDesktopTable ? 'pl-3 is-justify-content-space-between' : 'is-justify-content-space-around'">
                    <!-- toolbar -->
                    <Toolbar v-if="bus.inManagementMode || showDesktopTable"
                        v-model:sortOrder="user.preferences.sortOrder"
                        :selectedCount="twofaccounts.selectedCount"
                        @clear-selected="twofaccounts.selectNone()"
                        @select-all="twofaccounts.selectAll()"
                        @sort-asc="twofaccounts.sortAsc()"
                        @sort-desc="twofaccounts.sortDesc()"
                        @select-mine="twofaccounts.selectMine()"
                        @select-shared-by-me="twofaccounts.selectSharedByMe()"
                        @select-groupless="twofaccounts.selectGroupless()">
                    </Toolbar>
                    <!-- group chips -->
                    <GroupChips
                        v-if="showDesktopTable || (user.preferences.useGroupChips && !bus.inManagementMode && !showGroupSwitch)"
                        v-model:active-group="user.preferences.activeGroup"
                        v-model:show-group-switch="showGroupSwitch"
                        v-model:show-favorites-only="twofaccounts.showFavoritesOnly"
                        :groups="groups.items"
                        :filteredCount="twofaccounts.filteredCount"
                        :useShare="appSettings.enableSharing"
                        :useShareAllScope="appSettings.enableAllUsersSharingScope"
                        :useVirtualChips="user.preferences.showVirtualChips"
                        :useFavorites="user.preferences.enableFavorites"
                        @active-group-changed="saveActiveGroup">
                    </GroupChips>
                </div>
                <div v-if="!bus.inManagementMode" class="has-text-centered">
                    <!-- close call to switch label -->
                    <div v-if="showGroupSwitch">
                        <button type="button" id="btnHideGroupSwitch" :title="$t('tooltip.hide_group_selector')" tabindex="1" class="button is-text is-like-text has-text-grey-dark" :class="{'has-text-grey' : mode != 'dark'}" @click.stop="showGroupSwitch = !showGroupSwitch">
                            {{ $t('label.select_accounts_to_show') }}
                        </button>
                    </div>
                    <!-- call to switch label -->
                    <div v-else-if="!showDesktopTable">
                        <GroupCallToSwitch v-if="!user.preferences.useGroupChips"
                            v-model:show-group-switch="showGroupSwitch"
                            :activeGroup="user.preferences.activeGroup"
                            :currentGroup="groups.current"
                            :filteredCount="twofaccounts.filteredCount"
                            :useShare="appSettings.enableSharing" />
                    </div>
                </div>
            </template>
            <template #content>
                <GroupSwitch
                    v-if="showGroupSwitch"
                    v-model:is-visible="showGroupSwitch"
                    v-model:active-group="user.preferences.activeGroup"
                    :groups="groups.items"
                    :useShare="appSettings.enableSharing"
                    :useShareAllScope="appSettings.enableAllUsersSharingScope"
                    @active-group-changed="saveActiveGroup">
                    <template v-if="groups.items.length < 2">
                        <p class="my-5">{{ $t('message.no_group_yet') }}</p>
                        <RouterLink :to="{ name: 'createGroup' }" >{{ $t('link.create_your_first_group') }}</RouterLink>
                    </template>
                </GroupSwitch>
                <DestinationGroupSelector
                    v-if="showDestinationGroupSelector"
                    v-model:showDestinationGroupSelector="showDestinationGroupSelector"
                    v-model:selectedAccountsIds="twofaccounts.selectedIds"
                    :groups="groups.items"
                    @accounts-moved="postGroupAssignementUpdate">
                </DestinationGroupSelector>
                <!-- show accounts list -->
                <div class="accounts-container" :class="[{ 'is-edit-mode': bus.inManagementMode }]" v-if="showAccounts">
                    <!-- accounts -->
                    <div class="accounts">
                        <span id="dv" class="columns is-multiline m-0" :class="{ 'is-centered': user.preferences.displayMode === 'grid' }">
                            <TwoFAccountList :useDesktopTableLayout="showDesktopTable">
                                <TwoFAccountListItem
                                    v-for="account in twofaccounts.filtered"
                                    :key="account.id"
                                    v-model:selectedTwofaccountIds="twofaccounts.selectedIds"
                                    v-bind="twofaccountSpecificShares[account.id] !== undefined ? { specificShares: twofaccountSpecificShares[account.id] } : { specificShares: [] }"
                                    :useDesktopTableLayout="showDesktopTable"
                                    :isFetchingShares="isFetchingShares == account.id"
                                    :colorScheme="mode"
                                    :storageRootPath="$2fauth.config.subdirectory"
                                    :account="account"
                                    :inManagementMode="bus.inManagementMode"
                                    :enableSharing="appSettings.enableSharing"
                                    :enableAllUsersSharingScope="appSettings.enableAllUsersSharingScope"
                                    :preferences="user.preferences"
                                    :nextOtpOpacityClass="opacities[account.period]"
                                    @show-or-copy="(account) => showOrCopy(account)"
                                    @get-and-copy-otp="(account) => getAndCopyOTP(account)"
                                    @copy-to-clipboard="(pwd) => copyToClipboard(pwd)"
                                    @toggle-is-favorite="(accountId) => toggleOtpDisplayFavorite(accountId)"
                                    @show-otp="(account) => showOTP(account)"
                                    @get-shares="(accountId) => getTwofaccountShares(accountId)"
                                    @delete-account-clicked="(accountId) => deleteAccount(accountId)"
                                >
                                    <template v-slot:dots>
                                        <Dots
                                            ref="dotsRefs"
                                            :class="'is-inline-block'"
                                            :isCondensed="true"
                                            :period="account.period" />
                                    </template>
                                </TwoFAccountListItem>
                            </TwoFAccountList>
                        </span>
                    </div>
                </div>
            </template>
            <template #footer v-if="showGroupSwitch">
                <VueFooter :show-buttons="true">
                    <!-- Create group buttons -->
                    <p class="control">
                        <RouterLink class="button is-link is-outlined is-rounded" :to="{ name: 'createGroup' }" :title="$t('tooltip.create_new_group')">
                            {{ $t('label.new') }}
                        </RouterLink>
                    </p>
                    <!-- Manage group buttons -->
                    <p class="control">
                        <RouterLink class="button is-link is-outlined is-rounded" :to="{ name: 'groups' }"  :title="$t('tooltip.manage_your_groups')">
                            {{ $t('link.manage') }}
                        </RouterLink>
                    </p>
                    <NavigationButton action="close" :use-link-tag="false" @closed="showGroupSwitch = false" />
                </VueFooter>
            </template>
            <template #footer v-else-if="! showDestinationGroupSelector">
                <VueFooter>
                    <template #default>
                        <ActionButtons
                            :areDisabled="twofaccounts.hasNoneSelected"
                            :showNew="!bus.inManagementMode || showDesktopTable"
                            :showManage="!bus.inManagementMode && !showDesktopTable"
                            :showMove="bus.inManagementMode || showDesktopTable"
                            :showDelete="bus.inManagementMode || showDesktopTable"
                            :showUnshare="(bus.inManagementMode || showDesktopTable) && appSettings.enableSharing"
                            :showExport="bus.inManagementMode || showDesktopTable"
                            :canDelete="!twofaccounts.hasBorrowedSelected"
                            :canUnshare="twofaccounts.hasOnlySharedSelected"
                            :canExport="!twofaccounts.hasBorrowedSelected"
                            @switch-to-management-mode="bus.inManagementMode = true"
                            @move-button-clicked="showDestinationGroupSelector = true"
                            @delete-button-clicked="deleteAccounts"
                            @export-button-clicked="showExportFormatSelector = true"
                            @unshare-button-clicked="bulkUnshare">
                        </ActionButtons>
                    </template>
                    <template #subpart v-if="bus.inManagementMode && !showDestinationGroupSelector">
                        <button type="button" id="lnkExitEdit" class="button is-ghost is-like-text" @click.stop="exitManagementMode">{{ $t('label.done') }}</button>
                    </template>
                </VueFooter>
                <!-- <VueFooter v-else>
                    <template #default>
                        <ActionButtons v-model:inManagementMode="bus.inManagementMode" />
                    </template>
                </VueFooter> -->
            </template>
        </StackLayout>
        <!-- export modal -->
        <Modal v-model:is-active="showExportFormatSelector">
            <ExportButtons
                @export-twofauth-format="twofaccounts.export()"
                @export-otpauth-format="twofaccounts.export('otpauth')">
            </ExportButtons>
        </Modal>
        <!-- otp modal -->
        <Modal v-model:is-active="showOtpInModal" v-model:show-footer-menu="showFooterMenu">
            <template #default>
                <OtpDisplay
                    ref="otpDisplay"
                    :accountParams="accountParams"
                    :preferences="user.preferences"
                    :twofaccountService="twofaccountService"
                    :iconPathPrefix="$2fauth.config.subdirectory"
                    @please-close-me="showOtpInModal = false; showFooterMenu = false"
                    @please-clear-search="twofaccounts.filter = ''"
                    @kickme="user.logout({ kicked: true})"
                    @please-update-activeGroup="saveActiveGroup"
                    @please-toggle-favorite="toggleOtpDisplayFavorite"
                    @otp-copied-to-clipboard="notify.success({ text: t('notification.copied_to_clipboard') })"
                    @error="(error) => errorHandler.show(error)"
                />
            </template>
            <template v-if="showOtpInModal && ! visibleAccount.is_borrowed" #footer-submenu>
                <ul class="ml-0 mt-1">
                    <!-- manage sharing link -->
                    <li v-if="appSettings.enableSharing" class="column">
                        <router-link id="lnkManageSharing" :to="{ name: 'accountSharing', params: { twofaccountId: visibleAccount.id }}" class="is-link">
                            {{ $t('link.manage_sharing') }}
                        </router-link>
                    </li>
                    <!-- transfer ownership link -->
                    <li v-if="appSettings.enableSharing" class="column">
                        <router-link id="lnkTransferOwnership" :to="{ name: 'transferOwnership', params: { twofaccountId: visibleAccount.id }}" class="is-link">
                            {{ $t('link.transfer_ownership') }}
                        </router-link>
                    </li>
                    <!-- otp generation log link -->
                    <li class="column">
                        <router-link id="lnkRead" :to="{ name: 'otpLogs', params: { twofaccountId: visibleAccount.id }}" class="is-link">
                            {{ $t('link.otp_generation_log') }}
                        </router-link>
                    </li>
                    <!-- action buttons -->
                    <li class="column">
                        <div class="tags is-centered are-medium">
                            <router-link id="lnkQrCode" :to="{ name: 'showQRcode', params: { twofaccountId: visibleAccount.id }}" class="tag is-rounded" :class="mode == 'dark' ? 'is-dark' : 'is-white'" :title="$t('tooltip.show_qrcode')">
                                <LucideQrCode class="icon-size-1" />
                            </router-link>
                            <router-link id="lnkEdit" :to="{ name: 'editAccount', params: { twofaccountId: visibleAccount.id }}" class="tag is-rounded mx-2" :class="mode == 'dark' ? 'is-dark' : 'is-white'" :title="$t('tooltip.edit_account')">
                                <LucidePencil class="icon-size-1" />
                            </router-link>
                            <button id="btnDelete" @click="deleteAccount(visibleAccount.id)" class="tag is-rounded" :class="mode == 'dark' ? 'is-dark' : 'is-white'" :title="$t('tooltip.delete_account')">
                                <LucideTrash2 class="icon-size-1" />
                            </button>
                        </div>
                    </li>
                </ul>
            </template>
            <template #footer-subpart>
                <span v-if="showOtpInModal && appSettings.enableSharing && visibleAccount.is_borrowed" class="has-text-grey">
                    {{ $t('message.shared_by_x', { username: visibleAccount.borrowed_by }) }}
                </span>
                <button v-else type="button" id="btnActions" @click="showFooterMenu = !showFooterMenu" class="button is-text is-like-text has-text-grey" style="width: 100%;">
                    <span class="mr-2 has-ellipsis">{{ $t('label.actions') }}</span>
                    <LucideMenu v-if="!showFooterMenu" />
                    <LucideX v-else />
                </button>
            </template>
        </Modal>
        <!-- dots controllers -->
        <span v-if="!user.preferences.getOtpOnRequest">
            <DotsController
                v-for="period in twofaccounts.periods"
                ref="dotsControllers"
                :key="period.period"
                :autostart="false"
                :period="period.period"
                :generated_at="period.generated_at"
                @stepping-ended="updateTotps(period.period)"
                @stepping-started="turnDotsOn(period.period, $event)"
                @stepped-up="turnDotsOn(period.period, $event)"
            ></DotsController>
        </span>
    </div>
    </UseColorMode>
</template>
