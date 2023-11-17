<script setup>
    import twofaccountService from '@/services/twofaccountService'
    import TotpLooper from '@/components/TotpLooper.vue'
    import GroupSwitch from '@/components/GroupSwitch.vue'
    import DestinationGroupSelector from '@/components/DestinationGroupSelector.vue'
    import SearchBox from '@/components/SearchBox.vue'
    import Toolbar from '@/components/Toolbar.vue'
    import OtpDisplay from '@/components/OtpDisplay.vue'
    import ActionButtons from '@/components/ActionButtons.vue'
    import Dots from '@/components/Dots.vue'
    import { UseColorMode } from '@vueuse/components'
    import { useUserStore } from '@/stores/user'
    import { useNotifyStore } from '@/stores/notify'
    import { useBusStore } from '@/stores/bus'
    import { useTwofaccounts } from '@/stores/twofaccounts'
    import { useGroups } from '@/stores/groups'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useDisplayablePassword } from '@/composables/helpers'
    import { useSortable, moveArrayElement } from '@vueuse/integrations/useSortable'

    const $2fauth = inject('2fauth')
    const router = useRouter()
    const notify = useNotifyStore()
    const user = useUserStore()
    const bus = useBusStore()
    const appSettings = useAppSettingsStore()
    const { copy, copied } = useClipboard({ legacy: true })
    const twofaccounts = useTwofaccounts()
    const groups = useGroups()

    const showOtpInModal = ref(false)
    const showGroupSwitch = ref(false)
    const showDestinationGroupSelector = ref(false)
    const isDragging = ref(false)
    const isRenewingOTPs = ref(false)
    const renewedPeriod = ref(null)

    const otpDisplay = ref(null)
    const otpDisplayProps = ref({
        otp_type: '',
        account : '',
        service : '',
        icon : '',
    })
    const looperRefs = ref([])
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
                    notify.info({ text: trans('commons.data_refreshed_to_reflect_server_changes'), duration: 10000 })
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
                    twofaccounts.saveOrder()
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
        notify.success({ text: trans('twofaccounts.accounts_moved') })
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
            // Data that should be displayed quickly by the OtpDisplay
            // component are passed using props.
            otpDisplayProps.value.otp_type = account.otp_type
            otpDisplayProps.value.service = account.service
            otpDisplayProps.value.account = account.account
            otpDisplayProps.value.icon = account.icon

            nextTick().then(() => {
                showOtpInModal.value = true
                otpDisplay.value.show(account.id);
            })
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
                user.logout({ kicked: true})
            }
            notify.success({ text: trans('commons.copied_to_clipboard') })
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
        twofaccounts.saveOrder()
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
     * Updates "Always On" OTPs for all TOTP accounts and (re)starts loopers
     */
    async function updateTotps(period) {
        isRenewingOTPs.value = true
        turnDotsOff(period)
        let fetchPromise

        if (period == undefined) {
            renewedPeriod.value = -1
            fetchPromise = twofaccountService.getAll(true)
        } else {
            renewedPeriod.value = period
            fetchPromise = twofaccountService.getByIds(twofaccounts.accountIdsWithPeriod(period).join(','), true)
        }

        fetchPromise.then(response => {
            let generatedAt = 0

            // twofaccounts OTP updates
            response.data.forEach((account) => {
                const index = twofaccounts.items.findIndex(acc => acc.id === account.id)
                if (twofaccounts.items[index] == undefined) {
                    twofaccounts.items.push(account)
                }
                else twofaccounts.items[index].otp = account.otp
                generatedAt = account.otp.generated_at
            })

            // Loopers restart at new timestamp
            looperRefs.value.forEach((looper) => {
                if (looper.props.period == period || period == undefined) {
                    nextTick().then(() => {
                        looper.startLoop(generatedAt)
                    })
                }
            })
        })
        .finally(() => {
            isRenewingOTPs.value = false
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

</script>

<template>
    <div>
        <GroupSwitch v-if="showGroupSwitch" v-model:showGroupSwitch="showGroupSwitch" v-model:groups="groups.items" />
        <DestinationGroupSelector
            v-if="showDestinationGroupSelector"
            v-model:showDestinationGroupSelector="showDestinationGroupSelector"
            v-model:selectedAccountsIds="twofaccounts.selectedIds"
            :groups="groups.items"
            @accounts-moved="postGroupAssignementUpdate">
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
                v-bind="otpDisplayProps"
                @please-close-me="showOtpInModal = false">
            </OtpDisplay>
        </Modal>
        <!-- totp loopers -->
        <span v-if="!user.preferences.getOtpOnRequest">
            <TotpLooper
                v-for="period in twofaccounts.periods"
                :key="period.period"
                :autostart="false"
                :period="period.period"
                :generated_at="period.generated_at"
                v-on:loop-ended="updateTotps(period.period)"
                v-on:loop-started="turnDotsOn(period.period, $event)"
                v-on:stepped-up="turnDotsOn(period.period, $event)"
                ref="looperRefs"
            ></TotpLooper>
        </span>
        <!-- show accounts list -->
        <div class="container" v-if="showAccounts" :class="bus.inManagementMode ? 'is-edit-mode' : ''">
            <!-- accounts -->
            <div class="accounts">
                <span id="dv" class="columns is-multiline" :class="{ 'is-centered': user.preferences.displayMode === 'grid' }">
                    <div :class="[user.preferences.displayMode === 'grid' ? 'tfa-grid' : 'tfa-list']" class="column is-narrow" v-for="account in twofaccounts.filtered" :key="account.id">
                        <div class="tfa-container">
                            <transition name="slideCheckbox">
                                <div class="tfa-cell tfa-checkbox" v-if="bus.inManagementMode">
                                    <div class="field">
                                        <UseColorMode v-slot="{ mode }">
                                            <input class="is-checkradio is-small" :class="mode == 'dark' ? 'is-white':'is-info'" :id="'ckb_' + account.id" :value="account.id" type="checkbox" :name="'ckb_' + account.id" v-model="twofaccounts.selectedIds">
                                        </UseColorMode>
                                        <label tabindex="0" :for="'ckb_' + account.id" v-on:keypress.space.prevent="twofaccounts.select(account.id)"></label>
                                    </div>
                                </div>
                            </transition>
                            <div tabindex="0" class="tfa-cell tfa-content is-size-3 is-size-4-mobile" @click.exact="showOrCopy(account)" @keyup.enter="showOrCopy(account)" @click.ctrl="getAndCopyOTP(account)" role="button">  
                                <div class="tfa-text has-ellipsis">
                                    <img class="tfa-icon" :src="$2fauth.config.subdirectory + '/storage/icons/' + account.icon" v-if="account.icon && user.preferences.showAccountsIcons" :alt="$t('twofaccounts.icon_for_account_x_at_service_y', {account: account.account, service: account.service})">
                                    {{ account.service ? account.service : $t('twofaccounts.no_service') }}<FontAwesomeIcon class="has-text-danger is-size-5 ml-2" v-if="appSettings.useEncryption && account.account === $t('errors.indecipherable')" :icon="['fas', 'exclamation-circle']" />
                                    <span class="has-ellipsis is-family-primary is-size-6 is-size-7-mobile has-text-grey ">{{ account.account }}</span>
                                </div>
                            </div>
                            <transition name="popLater">
                                <div v-show="user.preferences.getOtpOnRequest == false && !bus.inManagementMode" class="has-text-right">
                                    <span v-if="account.otp != undefined">
                                        <span v-if="isRenewingOTPs == true && (renewedPeriod == -1 || renewedPeriod == account.period)" class="has-nowrap has-text-grey has-text-centered is-size-5">
                                            <FontAwesomeIcon :icon="['fas', 'circle-notch']" spin />
                                        </span>
                                        <span v-else class="always-on-otp is-clickable has-nowrap has-text-grey is-size-5 ml-4" @click="copyToClipboard(account.otp.password)" @keyup.enter="copyToClipboard(account.otp.password)" :title="$t('commons.copy_to_clipboard')">
                                            {{ useDisplayablePassword(account.otp.password) }}
                                        </span>
                                        <Dots
                                            v-if="account.otp_type.includes('totp')"
                                            :class="'condensed'"
                                            ref="dotsRefs"
                                            :period="account.period" />
                                    </span>
                                    <span v-else>
                                        <!-- get hotp button -->
                                        <UseColorMode v-slot="{ mode }">
                                            <button class="button tag" :class="mode == 'dark' ? 'is-dark' : 'is-white'" @click="showOTP(account)" :title="$t('twofaccounts.import.import_this_account')">
                                                {{ $t('commons.generate') }}
                                            </button>
                                        </UseColorMode>
                                    </span>
                                </div>
                            </transition>
                            <transition name="fadeInOut">
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
                            </transition>
                            <transition name="fadeInOut">
                                <div class="drag-handle tfa-cell tfa-dots has-text-grey" v-if="bus.inManagementMode">
                                    <FontAwesomeIcon :icon="['fas', 'bars']" />
                                </div>
                            </transition>
                        </div>
                    </div>
                </span>
            </div>
            <VueFooter :showButtons="true" :internalFooterType="bus.inManagementMode && !showDestinationGroupSelector ? 'doneButton' : 'navLinks'" @done-button-clicked="exitManagementMode">
                <ActionButtons
                    v-model:inManagementMode="bus.inManagementMode"
                    :areDisabled="twofaccounts.hasNoneSelected"
                    @move-button-clicked="showDestinationGroupSelector = true"
                    @delete-button-clicked="deleteAccounts"
                    @export-button-clicked="twofaccounts.export()">
                </ActionButtons>
            </VueFooter>
        </div>
    </div>
</template>
