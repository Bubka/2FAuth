<script setup>
    const props = defineProps({
        isVisible: Boolean,
        type: {
            type: String,
            default: 'inline'
        },
    })
</script>

<template>
    <div v-if="isVisible">
        <div v-if="type == 'inline'" class="has-text-centered mt-6">
            <span id="icnSpinner" class="is-size-4">
                <FontAwesomeIcon :icon="['fas', 'spinner']" spin />
            </span>
        </div>
    </div>
</template>