<script setup>
    import { LucideArrowDownAZ, LucideArrowUpAZ, LucideSquareCheck } from 'lucide-vue-next';

    const props = defineProps({
        selectedCount: Number
    })
</script>

<template>
    <div class="toolbar has-text-centered">
        <div class="columns">
            <div class="column has-nowrap px-0" style="line-height: initial" >
                <!-- selected label -->
                <span class="has-text-grey">{{ $t('message.x_selected', { count: selectedCount }) }}</span>
                <!-- deselect all -->
                <button type="button" id="btnUnselectAll" @click="$emit('clear-selected')" class="clear-selection delete mr-4 ml-1" :style="{visibility: selectedCount > 0 ? 'visible' : 'hidden'}" :title="$t('tooltip.clear_selection')"></button>
                <!-- select all button -->
                <button type="button" id="btnSelectAll" @click="$emit('select-all')" class="button py-0 px-1 mr-5 has-line-height is-ghost has-text-grey" :title="$t('tooltip.select_all')">
                    <span>{{ $t('label.check_all') }}</span>
                    <LucideSquareCheck class="ml-1" />
                </button>
                <!-- sort asc/desc buttons -->
                <button type="button" id="btnSortAscending" @click="$emit('sort-asc')" class="button has-line-height p-0 is-ghost has-text-grey" :title="$t('tooltip.sort_ascending')">
                    <LucideArrowDownAZ />
                </button>
                <button type="button" id="btnSortDescending" @click="$emit('sort-desc')" class="button has-line-height p-0 pl-2 is-ghost has-text-grey" :title="$t('tooltip.sort_descending')">
                    <LucideArrowUpAZ />
                </button>
            </div>
        </div>
    </div>
</template>