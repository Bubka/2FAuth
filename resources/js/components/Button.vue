<template>
    <button 
        :type="nativeType"
        :disabled="isLoading || isDisabled"
        :class="{
            'button': true,
            [`${color}`]: true,
            'is-loading': isLoading,
        }"
        v-on:click="$emit('click')">
        <slot />
    </button>
</template>

<script>
    export default {
        name: 'VButton',

        props: {
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
        }
    }

</script>
