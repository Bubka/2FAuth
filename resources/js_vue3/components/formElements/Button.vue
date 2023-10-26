<script setup>
    const props = defineProps({
        color: {
            type: String,
            default: 'is-link'
        },
        nativeType: {
            type: String,
            default: 'submit'
        },
        isLoading: {
            type: Boolean,
            default: false
        },
        isDisabled: {
            type: Boolean,
            default: false
        }
    })
</script>

<template>
    <button 
        :type="nativeType"
        :disabled="isLoading || isDisabled"
        :class="{
            'button': true,
            [`${color}`]: true,
            'is-loading': isLoading,
        }">
        <slot />
    </button>
</template>