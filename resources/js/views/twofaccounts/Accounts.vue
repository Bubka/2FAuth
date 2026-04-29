<script setup>
    import twofaccountService from '@/services/twofaccountService'
    import shareService from '@/services/shareService'
    import DestinationGroupSelector from '@/components/DestinationGroupSelector.vue'
    import Toolbar from '@/components/Toolbar.vue'
    import ActionButtons from '@/components/ActionButtons.vue'
    import ExportButtons from '@/components/ExportButtons.vue'
    import { UseColorMode } from '@vueuse/components'
    import { useUserStore } from '@/stores/user'
    import {
        useNotify,
        SearchBox,
        GroupSwitch,
        OtpDisplay,
        Dots,
        DotsController,
        useVisiblePassword
    } from '@2fauth/ui'
    import { useBusStore } from '@/stores/bus'
    import { useTwofaccounts } from '@/stores/twofaccounts'
    import { useGroups } from '@/stores/groups'
    import { useSortable, moveArrayElement } from '@vueuse/integrations/useSortable'
    import { useI18n } from 'vue-i18n'
    import { useErrorHandler } from '@2fauth/stores'
    import { LucideChevronDown, LucideCircleAlert, LucideEye, LucideEyeOff, LucideMenu, LucideQrCode, LucideUserPen, LucideUserPlus, LucideUsers, LucideX } from '@lucide/vue'

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

    const showOtpInModal = ref(false)
    const showExportFormatSelector = ref(false)
    const showGroupSwitch = ref(false)
    const showDestinationGroupSelector = ref(false)
    const isDragging = ref(false)
    const renewedPeriod = ref(null)
    const revealPassword = ref(null)
    const opacities = ref({})
    const showFooterMenu = ref(false)
    const visibleAccount = ref(null)

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
        image: ''
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
     * 
     * @param userId
     */
    function unshareUser(account, userId) {
        if (confirm(t('confirmation.unshare')) === true) {
            shareService.unshareWithUser(account.id, userId).then(response => {
                usershares.value = usershares.value.filter(user => user.id != userId)
            })
        }
    }

</script>

<template>
    <UseColorMode v-slot="{ mode }">
    <div>
        <StackLayout>
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
            <template #subheader>
                <!-- toolbar -->
                <Toolbar v-if="bus.inManagementMode"
                    v-model:sortOrder="user.preferences.sortOrder"
                    :selectedCount="twofaccounts.selectedCount"
                    @clear-selected="twofaccounts.selectNone()"
                    @select-all="twofaccounts.selectAll()"
                    @sort-asc="twofaccounts.sortAsc()"
                    @sort-desc="twofaccounts.sortDesc()">
                </Toolbar>
                <!-- group switch toggle -->
                <div v-else class="has-text-centered">
                    <div v-if="showGroupSwitch">
                        <button type="button" id="btnHideGroupSwitch" :title="$t('tooltip.hide_group_selector')" tabindex="1" class="button is-text is-like-text has-text-grey-dark" :class="{'has-text-grey' : mode != 'dark'}" @click.stop="showGroupSwitch = !showGroupSwitch">
                            {{ $t('label.select_accounts_to_show') }}
                        </button>
                    </div>
                    <div v-else>
                        <button type="button" id="btnShowGroupSwitch" :title="$t('tooltip.show_group_selector')" tabindex="1" class="button is-text is-like-text has-text-grey-dark" :class="{'has-text-grey' : mode != 'dark'}" @click.stop="showGroupSwitch = !showGroupSwitch">
                            <template v-if="parseInt(user.preferences.activeGroup) == -1">
                                {{ $t('label.group_less') }} ({{ twofaccounts.filteredCount }})&nbsp;
                            </template>
                            <template v-else-if="parseInt(user.preferences.activeGroup) == -2">
                                {{ $t('label.shared_by_me') }} ({{ twofaccounts.filteredCount }})&nbsp;
                            </template>
                            <template v-else-if="parseInt(user.preferences.activeGroup) == -3">
                                 {{ $t('label.shared_with_me') }} ({{ twofaccounts.filteredCount }})&nbsp;
                            </template>
                            <template v-else-if="groups.current">
                                {{ groups.current }} ({{ twofaccounts.filteredCount }})&nbsp;
                            </template>
                            <template v-else>
                                {{ $t('label.all') }} ({{ twofaccounts.filteredCount }})&nbsp;
                            </template>
                            <LucideChevronDown class="mt-1" />
                        </button>
                    </div>
                </div>
            </template>
            <template #content>
                <GroupSwitch
                    v-if="showGroupSwitch"
                    v-model:is-visible="showGroupSwitch"
                    v-model:active-group="user.preferences.activeGroup"
                    :groups="groups.items"
                    @active-group-changed="saveActiveGroup">
                        <RouterLink :to="{ name: 'groups' }" >{{ $t('link.manage_groups') }}</RouterLink>
                </GroupSwitch>
                <DestinationGroupSelector
                    v-if="showDestinationGroupSelector"
                    v-model:showDestinationGroupSelector="showDestinationGroupSelector"
                    v-model:selectedAccountsIds="twofaccounts.selectedIds"
                    :groups="groups.items"
                    @accounts-moved="postGroupAssignementUpdate">
                </DestinationGroupSelector>
                <!-- show accounts list -->
                <div class="accounts-container" v-if="showAccounts" :class="bus.inManagementMode ? 'is-edit-mode' : ''">
                    <!-- accounts -->
                    <div class="accounts">
                        <span id="dv" class="columns is-multiline m-0" :class="{ 'is-centered': user.preferences.displayMode === 'grid' }">
                            <div :class="[user.preferences.displayMode === 'grid' ? 'tfa-grid' : 'tfa-list']" class="column is-narrow" v-for="account in twofaccounts.filtered" :key="account.id">
                                <div class="tfa-container">
                                    <transition name="slideCheckbox">
                                        <div class="tfa-cell tfa-checkbox" v-if="bus.inManagementMode">
                                            <div class="field">
                                                <input class="is-checkradio is-small" :class="mode == 'dark' ? 'is-white':'is-info'" :id="'ckb_' + account.id" :value="account.id" type="checkbox" :name="'ckb_' + account.id" v-model="twofaccounts.selectedIds"  />
                                                <label tabindex="0" :for="'ckb_' + account.id" v-on:keypress.space.prevent="selectAccount(account)"></label>
                                            </div>
                                        </div>
                                    </transition>
                                    <div tabindex="0" class="tfa-cell tfa-content is-size-3 is-size-4-mobile" @click.exact="showOrCopy(account)" @keyup.enter="showOrCopy(account)" @click.ctrl="getAndCopyOTP(account)" role="button">  
                                        <div class="tfa-text has-ellipsis is-clickable">
                                            <img v-if="account.icon && user.preferences.showAccountsIcons" role="presentation" class="tfa-icon" :src="$2fauth.config.subdirectory + '/storage/icons/' + account.icon" alt="">
                                            <img v-else-if="account.icon == null && user.preferences.showAccountsIcons" role="presentation" class="tfa-icon" :src="$2fauth.config.subdirectory + '/storage/noicon.svg'" alt="">
                                            {{ account.service ? account.service : $t('message.no_service') }}<LucideCircleAlert class="has-text-danger ml-2" v-if="account.account === $t('error.indecipherable')" />
                                            <span class="is-block has-ellipsis is-family-primary is-size-6 is-size-7-mobile has-text-grey ">
                                                <template v-if="account.is_borrowed">
                                                    <span :title="$t('tooltip.this_account_is_shared_by_x_with_you', { username: account.borrowed_by })" class="tag p-1 mr-1" :class="mode == 'dark' ? 'is-dark':'is-white'" >@{{ bus.inManagementMode ? account.borrowed_by : '' }}</span>
                                                </template>
                                                {{ account.account }}
                                            </span>
                                        </div>
                                    </div>
                                    <transition name="popLater">
                                        <div v-show="user.preferences.getOtpOnRequest == false && !bus.inManagementMode" class="has-text-right">
                                            <div v-if="account.otp != undefined">
                                                <div class="always-on-otp is-clickable has-nowrap has-text-grey is-size-5 ml-4" @click="copyToClipboard(account.otp.password)" @keyup.enter="copyToClipboard(account.otp.password)" :title="$t('tooltip.copy_to_clipboard')">
                                                    {{ useVisiblePassword(
                                                            account.otp.password,
                                                            user.preferences.formatPassword,
                                                            user.preferences.formatPasswordBy,
                                                            user.preferences.showOtpAsDot,
                                                            user.preferences.revealDottedOTP && revealPassword == account.id
                                                        )
                                                    }}
                                                </div>
                                                <div class="has-nowrap" style="line-height: 0.9;">
                                                    <span v-if="user.preferences.showNextOtp" class="always-on-otp is-clickable has-nowrap has-text-grey is-size-7 mr-2" :class="opacities[account.period]" @click="copyToClipboard(account.otp.next_password)" @keyup.enter="copyToClipboard(account.otp.next_password)" :title="$t('tooltip.copy_next_password')">
                                                        {{ useVisiblePassword(
                                                                account.otp.next_password,
                                                                user.preferences.formatPassword,
                                                                user.preferences.formatPasswordBy,
                                                                user.preferences.showOtpAsDot,
                                                                user.preferences.revealDottedOTP && revealPassword == account.id
                                                            )
                                                        }}
                                                    </span>
                                                    <Dots
                                                        v-if="account.otp_type.includes('totp')"
                                                        ref="dotsRefs"
                                                        :class="'is-inline-block'"
                                                        :isCondensed="true"
                                                        :period="account.period" />
                                                </div>
                                            </div>
                                            <div v-else>
                                                <!-- get hotp button -->
                                                <button type="button" class="button tag" :class="mode == 'dark' ? 'is-dark' : 'is-white'" @click="showOTP(account)" :title="$t('tooltip.import_this_account')">
                                                    {{ $t('label.generate') }}
                                                </button>
                                            </div>
                                        </div>
                                    </transition>
                                    <transition name="popLater" v-if="user.preferences.showOtpAsDot && user.preferences.revealDottedOTP">
                                        <div v-show="user.preferences.getOtpOnRequest == false && !bus.inManagementMode" class="has-text-right">
                                            <button v-if="revealPassword == account.id" type="button" class="pr-0 button is-ghost has-text-grey-dark" @click.stop="revealPassword = null">
                                                <LucideEye />
                                            </button>
                                            <button v-else type="button" class="pr-0 button is-ghost has-text-grey-dark" @click.stop="revealPassword = account.id">
                                                <LucideEyeOff />
                                            </button>
                                        </div>
                                    </transition>
                                    <transition name="fadeInOut">
                                        <div class="tfa-cell tfa-edit has-text-grey" v-if="bus.inManagementMode && user.preferences.activeGroup == -2">
                                            <!-- new user share button -->
                                            <RouterLink v-if="account.is_shared" :to="{ name: 'shareAccount', params: { twofaccountId: account.id } }" class="tag is-rounded mr-1" :class="mode == 'dark' ? 'is-dark' : 'is-white'" :title="$t('tooltip.share_with_new_users')">
                                                <LucideUserPlus class="icon-size-1" />
                                            </RouterLink>
                                            <!-- shared with all flag -->
                                            <span v-else-if="account.is_shared_with_all" class="tag is-rounded is-light has-no-background mr-1" :title="$t('tooltip.shared_with_all')">
                                                <LucideUsers class="icon-size-1" />
                                            </span>
                                            <!-- manage sharing button -->
                                            <RouterLink :to="{ name: 'accountSharing', params: { twofaccountId: account.id }}" class="tag is-rounded" :class="mode == 'dark' ? 'is-dark' : 'is-white'" :title="$t('tooltip.edit_sharing')">
                                                <LucideUserPen class="icon-size-1" />
                                            </RouterLink>
                                        </div>
                                        <div class="tfa-cell tfa-edit has-text-grey" v-else-if="bus.inManagementMode && ! account.is_borrowed">
                                            <!-- edit button -->
                                            <RouterLink :to="{ name: 'editAccount', params: { twofaccountId: account.id }}" class="tag is-rounded mr-1" :class="mode == 'dark' ? 'is-dark' : 'is-white'">
                                                {{ $t('link.edit') }}
                                            </RouterLink>
                                            <!-- show qrcode button -->
                                            <RouterLink :to="{ name: 'showQRcode', params: { twofaccountId: account.id }}" class="tag is-rounded" :class="mode == 'dark' ? 'is-dark' : 'is-white'" :title="$t('tooltip.show_qrcode')">
                                                <LucideQrCode class="icon-size-1" />
                                            </RouterLink>
                                        </div>
                                    </transition>
                                    <transition name="fadeInOut">
                                        <div class="drag-handle tfa-cell tfa-dots has-text-grey" v-if="bus.inManagementMode">
                                            <LucideMenu />
                                        </div>
                                    </transition>
                                </div>
                            </div>
                        </span>
                    </div>
                </div>
            </template>
            <template #footer v-if="showGroupSwitch">
                <VueFooter :show-buttons="true">
                    <NavigationButton action="close" :use-link-tag="false" @closed="showGroupSwitch = false" />
                </VueFooter>
            </template>
            <template #footer v-else-if="! showDestinationGroupSelector">
                <VueFooter v-if="bus.inManagementMode && !showDestinationGroupSelector">
                    <template #default>
                        <ActionButtons
                            v-model:inManagementMode="bus.inManagementMode"
                            :areDisabled="twofaccounts.hasNoneSelected"
                            :canShare="!twofaccounts.hasBorrowedSelected"
                            :canDelete="!twofaccounts.hasBorrowedSelected"
                            :canExport="!twofaccounts.hasBorrowedSelected"
                            @move-button-clicked="showDestinationGroupSelector = true"
                            @delete-button-clicked="deleteAccounts"
                            @export-button-clicked="showExportFormatSelector = true">
                        </ActionButtons>
                    </template>
                    <template #subpart>
                        <button type="button" id="lnkExitEdit" class="button is-ghost is-like-text" @click.stop="exitManagementMode">{{ $t('label.done') }}</button>
                    </template>
                </VueFooter>
                <VueFooter v-else>
                    <template #default>
                        <ActionButtons v-model:inManagementMode="bus.inManagementMode" />
                    </template>
                </VueFooter>
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
                    @otp-copied-to-clipboard="notify.success({ text: t('notification.copied_to_clipboard') })"
                    @error="(error) => errorHandler.show(error)"
                />
            </template>
            <template v-if="showOtpInModal && ! visibleAccount.is_borrowed" #footer-submenu>
                <ul class="ml-0 mt-1">
                    <!-- edit link -->
                    <li class="column">
                        <router-link id="lnkEdit" :to="{ name: 'editAccount', params: { twofaccountId: visibleAccount.id }}" class="is-link">
                            {{ $t('link.edit') }}
                        </router-link>
                    </li>
                    <!-- manage sharing link -->
                    <li class="column">
                        <router-link id="lnkManageSharing" :to="{ name: 'accountSharing', params: { twofaccountId: visibleAccount.id }}" class="is-link">
                            {{ $t('link.manage_sharing') }}
                        </router-link>
                    </li>
                    <!-- qrcode link -->
                    <li class="column">
                        <router-link id="lnkQrCode" :to="{ name: 'showQRcode', params: { twofaccountId: visibleAccount.id }}" class="is-link" :title="$t('tooltip.show_qrcode')">
                            {{ $t('link.view_as_qrcode') }}
                        </router-link>
                    </li>
                </ul>
            </template>
            <template #footer-subpart>
                <span v-if="showOtpInModal && visibleAccount.is_borrowed" class="has-text-grey">
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
