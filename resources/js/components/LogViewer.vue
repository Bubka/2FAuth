<script setup>
    import LogItem from '@/components/LogItem.vue'
    import userService from '@/services/userService'
    import twofaccountService from '@/services/twofaccountService'
    import { useErrorHandler } from '@2fauth/stores'
    import { SearchBox } from '@2fauth/ui'
    import {
        LucideCalendarArrowDown,
        LucideCalendarArrowUp,
    } from '@lucide/vue'

    const errorHandler = useErrorHandler()

    const props = defineProps({
        twofaccountId: [Number, String],
        userId: [Number, String],
        lastOnly: Boolean,
        showSearch: Boolean,
        period: {
            type: [Number, String],
            default: 12
        },
        logType: {
            type: String,
            required: true,
            validator(value, props) {
                return ['auth', 'otp'].includes(value)
            }
        },
        sortingKey: {
            type: String,
            required: true
        }
    })

    const periods = {
        aMonth: 1,
        threeMonths: 3,
        halfYear: 6,
        aYear: 12
    }

    const logs = ref([])
    const isFetching = ref(false)
    const searched = ref('')
    const period = ref(props.period)
    const orderIsDesc = ref(true)

    const emit = defineEmits(['has-more-entries'])

    const visibleLogs = computed(() => {
        return logs.value.filter(log => {
            return JSON.stringify(log)
            .toString()
            .toLowerCase()
            .includes(searched.value);
        })
    })

    onMounted(() => {
        getLogs()
    })

    /**
     * Sets the visible time span
     * 
     * @param {int} duration 
     */
    function setPeriod(duration) {
        period.value = duration
        getLogs()
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
        logs.value.sort((a, b) => a[props.sortingKey] > b[props.sortingKey] ? 1 : -1)
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
        logs.value.sort((a, b) => a[props.sortingKey] < b[props.sortingKey] ? 1 : -1)
    }

    /**
     * Gets logs
     */
    function getLogs() {
        isFetching.value = true
        let limit = props.lastOnly ? 4 : false
        let fetchPromise

        if (props.logType === 'auth') {
            fetchPromise = userService.getauthentications(props.userId, period.value, limit, {returnError: true})
        } else if (props.logType === 'otp') {
            fetchPromise = twofaccountService.getOtpLogs(props.twofaccountId, period.value, limit, {returnError: true})
        }

        fetchPromise.then(response => {
            logs.value = response.data

            if (logs.value.length > 3 && props.lastOnly) {
                emit('has-more-entries')
                logs.value.pop()
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
                <button id="btnSortLogDesc" v-on:click="setDesc()" :title="$t('tooltip.sort_by_date_desc')" :class="{ 'has-text-grey' : !orderIsDesc }" type="button" class="button p-1 is-ghost">
                    <LucideCalendarArrowDown />
                </button>
                <button id="btnSortLogAsc" v-on:click="setAsc()" :title="$t('tooltip.sort_by_date_asc')" :class="{ 'has-text-grey' : orderIsDesc }" type="button" class="button p-1 is-ghost">
                    <LucideCalendarArrowUp />
                </button>
            </div>
        </div>
    </nav>
    <Spinner v-if="isFetching" :isVisible="true" :type="'list-loading'" />
    <div v-else-if="logs.length == 0" class="mt-4">
        {{ $t('message.no_entry_yet') }}
    </div>
    <div v-else-if="visibleLogs.length > 0">
        <LogItem v-for="log in visibleLogs" :key="log.id" :log="log" :log-type="props.logType" />
    </div>
    <div v-else class="mt-5 pl-3">
        {{ $t('message.no_result') }}
    </div>
</template>