<script setup>
    import {
        LucideArrowDownAZ,
        LucideArrowUpAZ,
        LucideCopyCheck,
        LucideSquareAsterisk,
        LucideSquareDashed,
        LucideSquareDashedX,
        LucideSquareSlash,
        LucideSquareUserRound
    } from '@lucide/vue';

    const sortOrder = defineModel('sortOrder')

    const props = defineProps({
        selectedCount: Number
    })

    const emit = defineEmits([
        'sort-asc',
        'sort-desc',
        'clear-selected',
        'select-all',
        'select-mine',
        'select-shared-by-me',
        'select-groupless'
    ])

    /**
     * 
     * @param sortOrder string
     */
    function setSortOrder(newOrder) {
        sortOrder.value = newOrder
        emit('sort-' + newOrder)
    }

</script>

<template>
    <div class="toolbar has-text-centered has-nowrap">
        <!-- selected label -->
        <span class="has-text-grey p-0 mr-3">({{ selectedCount }})</span>
        <!-- deselect all -->
        <button type="button" id="btnUnselectAll" @click="$emit('clear-selected')" class="button py-0 pl-0 pr-1 pt-1 has-line-height is-ghost has-text-grey " :title="$t('tooltip.clear_selection')" :disabled="selectedCount == 0">
            <!-- <span>{{ $t('label.check_all') }}</span> -->
            <LucideSquareDashed v-if="selectedCount == 0" stroke-width=1.5 />
            <LucideSquareDashedX v-else stroke-width=1.5 />
        </button>|
        <!-- select all button -->
        <button type="button" id="btnSelectAll" @click="$emit('select-all')" class="button py-0 px-0 pt-1 mr-5 has-line-height is-ghost has-text-grey" :title="$t('tooltip.select_all')">
            <LucideCopyCheck stroke-width=1.5 />
        </button>
        <!-- select my account button -->
        <button type="button" id="btnSelectMine" @click="$emit('select-mine')" class="button py-0 px-1 pt-1 has-line-height is-ghost has-text-grey" :title="$t('tooltip.select_mine')">
            <LucideSquareAsterisk stroke-width=1.5 />
        </button>|
        <!-- select shared button -->
        <button type="button" id="btnSelectSharedByMe" @click="$emit('select-shared-by-me')" class="button py-0 pl-0 pr-1 pt-1 has-line-height is-ghost has-text-grey" :title="$t('tooltip.select_shared_by_me')">
            <LucideSquareUserRound stroke-width=1.5 />
        </button>|
        <!-- select groupless button -->
        <button type="button" id="btnSelectGroupless" @click="$emit('select-groupless')" class="button py-0 px-0 pt-1 mr-5 has-line-height is-ghost has-text-grey" :title="$t('tooltip.select_group_less')">
            <LucideSquareSlash stroke-width=1.5 />
        </button>
        <!-- sort asc/desc buttons -->
        <button type="button" id="btnSortAscending" @click="setSortOrder('asc')" :class="{'has-text-grey' : sortOrder != 'asc'}" class="button has-line-height p-0 pr-1 is-ghost pt-1" :title="$t('tooltip.sort_ascending')">
            <LucideArrowDownAZ stroke-width=1.5 />
        </button>|
        <button type="button" id="btnSortDescending" @click="setSortOrder('desc')" :class="{'has-text-grey' : sortOrder != 'desc'}" class="button has-line-height p-0 is-ghost pt-1" :title="$t('tooltip.sort_descending')">
            <LucideArrowUpAZ stroke-width=1.5 />
        </button>
    </div>
</template>