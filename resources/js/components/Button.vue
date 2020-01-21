<template>
    <button 
        :type="nativeType"
        :disabled="isLoading"
        :class="{
            'button': true,
            [`${color}`]: true,
            'is-loading': isLoading,
          }">
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
        }
    }

</script>
