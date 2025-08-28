<script setup>
    import userService from '@/services/userService'
    import { UseColorMode } from '@vueuse/components'
    import { useErrorHandler } from '@2fauth/stores'
    import { SearchBox } from '@2fauth/ui'
    import {
        LucideCalendarArrowDown,
        LucideCalendarArrowUp,
        LucideSmartphone,
        LucideMonitor,
        LucideTablet,
        LucideCheck,
        LucideX
    } from 'lucide-vue-next'

    const errorHandler = useErrorHandler()
    const $2fauth = inject('2fauth')

    const props = defineProps({
        userId: [Number, String],
        lastOnly: Boolean,
        showSearch: Boolean,
        period: {
            type: [Number, String],
            default: 12
        },
    })

    const periods = {
        aMonth: 1,
        threeMonths: 3,
        halfYear: 6,
        aYear: 12
    }

    const authentications = ref([])
    const isFetching = ref(false)
    const searched = ref('')
    const period = ref(props.period)
    const orderIsDesc = ref(true)

    const emit = defineEmits(['has-more-entries'])

    const visibleAuthentications = computed(() => {
        return authentications.value.filter(authentication => {
            return JSON.stringify(authentication)
            .toString()
            .toLowerCase()
            .includes(searched.value);
        })
    })

    onMounted(() => {
        getAuthentications()
    })

    /**
     * Sets the visible time span
     * 
     * @param {int} duration 
     */
    function setPeriod(duration) {
        period.value = duration
        getAuthentications()
    }

    /**
     * Set sort order to ASC
     */
    function setAsc() {
        orderIsDesc.value = false
        sortAsc()
    }
        
    /**
     * Sorts entries ascending
     */
    function sortAsc() {
        authentications.value.sort((a, b) => a.id > b.id ? 1 : -1)
    }

    /**
     * Set sort order to DESC
     */
    function setDesc() {
        orderIsDesc.value = true
        sortDesc()
    }

    /**
     * Sorts entries descending
    */
    function sortDesc() {
        authentications.value.sort((a, b) => a.id < b.id ? 1 : -1)
    }

    /**
     * Gets user authentication logs
     */
    function getAuthentications() {
        isFetching.value = true
        let limit = props.lastOnly ? 4 : false

        userService.getauthentications(props.userId, period.value, limit, {returnError: true})
        .then(response => {
            authentications.value = response.data

            if (authentications.value.length > 3 && props.lastOnly) {
                emit('has-more-entries')
                authentications.value.pop()
            }
            
            orderIsDesc.value == true ? sortDesc() : sortAsc()
        })
        .catch(error => {
            errorHandler.show(error)
        })
        .finally(() => {
            isFetching.value = false
        })
    }

    const deviceIcon = (device) => {
        switch (device) {
            case "phone":
                return 'mobile-screen'
            case "tablet":
                return 'tablet-screen-button'
            default:
                return 'display'
        }
    }

    const isSuccessfulLogin = (authentication) => {
        return authentication.login_successful && authentication.login_at
    }

    const isSuccessfulLogout = (authentication) => {
        return !authentication.login_at && authentication.logout_at
    }

    const isFailedEntry = (authentication) => {
        return !authentication.login_successful && !authentication.logout_at
    }
</script>

<template>
    <SearchBox v-if="props.showSearch" v-model:keyword="searched" :hasNoBackground="true" />
    <nav v-if="props.showSearch" class="level is-mobile mb-2">
        <div class="level-item has-text-centered">
            <div class="buttons"> 
                <button id="btnShowOneMonth" :title="$t('tooltip.show_last_month_log')" v-on:click="setPeriod(periods.aMonth)" :class="{ 'has-text-grey' : period !== periods.aMonth }" type="button" class="button is-ghost p-1">
                    {{ $t('label.one_month') }}
                </button>
                <button id="btnShowThreeMonths" :title="$t('tooltip.show_three_months_log')" v-on:click="setPeriod(periods.threeMonths)" :class="{ 'has-text-grey' : period !== periods.threeMonths }" type="button" class="button is-ghost p-1">
                    {{ $t('label.x_month', { 'x' : '3' }) }}
                </button>
                <button id="btnShowSixMonths" :title="$t('tooltip.show_six_months_log')" v-on:click="setPeriod(periods.halfYear)" :class="{ 'has-text-grey' : period !== periods.halfYear }" type="button" class="button is-ghost p-1">
                    {{ $t('label.x_month', { 'x' : '6' }) }}
                </button>
                <button id="btnShowOneYear" :title="$t('tooltip.show_one_year_log')" v-on:click="setPeriod(periods.aYear)" :class="{ 'has-text-grey' : period !== periods.aYear }" type="button" class="button is-ghost p-1 mr-5">
                    {{ $t('label.one_year') }}
                </button>
                <button id="btnSortLogDesc" v-on:click="setDesc" :title="$t('tooltip.sort_by_date_desc')" :class="{ 'has-text-grey' : !orderIsDesc }" type="button" class="button p-1 is-ghost">
                    <LucideCalendarArrowDown />
                </button>
                <button id="btnSortLogAsc" v-on:click="setAsc" :title="$t('tooltip.sort_by_date_asc')" :class="{ 'has-text-grey' : orderIsDesc }" type="button" class="button p-1 is-ghost">
                    <LucideCalendarArrowUp />
                </button>
            </div>
        </div>
    </nav>
    <Spinner v-if="isFetching" :isVisible="true" :type="'list-loading'" />
    <div v-else-if="authentications.length == 0" class="mt-4">
        {{ $t('message.no_entry_yet') }}
    </div>
    <div v-else-if="visibleAuthentications.length > 0">
        <div v-for="authentication in visibleAuthentications" :key="authentication.id" class="list-item is-size-6 is-size-7-mobile has-text-grey is-flex is-justify-content-space-between">
            <UseColorMode v-slot="{ mode }">
                <div>
                    <div>
                        <i18n-t v-if="isFailedEntry(authentication)" keypath="message.failed_login_on" tag="span">
                            <template v-slot:login_at>
                                <span class="light-or-darker">{{ authentication.login_at }}</span>
                            </template>
                        </i18n-t>
                        <i18n-t v-else-if="isSuccessfulLogout(authentication)" keypath="message.successful_logout_on" tag="span">
                            <template v-slot:logout_at>
                                <span class="light-or-darker">{{ authentication.logout_at }}</span>
                            </template>
                        </i18n-t>
                        <i18n-t v-else-if="$2fauth.config.proxyAuth" keypath="message.viewed_on" tag="span">
                            <template v-slot:login_at>
                                <span class="light-or-darker">{{ authentication.login_at }}</span>
                            </template>
                        </i18n-t>
                        <i18n-t v-else keypath="message.successful_login_on" tag="span">
                            <template v-slot:login_at>
                                <span class="light-or-darker">{{ authentication.login_at }}</span>
                            </template>
                        </i18n-t>
                    </div>
                    <div>
                        {{ $t('label.IP') }}: <span class="light-or-darker">{{ authentication.ip_address }}</span> -
                        {{ $t('label.browser') }}: <span class="light-or-darker">{{ authentication.browser }}</span> -
                        {{ $t('label.operating_system_short') }}: <span class="light-or-darker">{{ authentication.platform }}</span>
                    </div>
                </div>
                <div :class="mode == 'dark' ? 'has-text-grey-darker' : 'has-text-grey-lighter'"
                    class="is-align-self-center ">
                    <div class="log-icon-wrapper">
                        <LucideSmartphone v-if="authentication.device=='phone'" class="log-icon-bg" />
                        <LucideTablet v-else-if="authentication.device=='tablet'" class="log-icon-bg" />
                        <LucideMonitor v-else class="log-icon-bg" />
                        <LucideX v-if="isFailedEntry(authentication)" stroke-width=3 class="log-icon-mark" :class="'has-text-danger' + (mode == 'dark' ? '-dark' : '')" />
                        <LucideCheck v-else stroke-width=3 class="log-icon-mark" :class="'has-text-success' + (mode == 'dark' ? '-dark' : '')" />
                    </div>
                </div>
            </UseColorMode>
        </div>
    </div>
    <div v-else class="mt-5 pl-3">
        {{ $t('message.no_result') }}
    </div>
</template>