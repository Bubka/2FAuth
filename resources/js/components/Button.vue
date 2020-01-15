<template>
    <button 
        :type="nativeType" 
        :class="{
            [`${color}`]: true,
            'is-large': isLarge,
            'is-focused': isFocused,
          }" 
        class="button">
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

            isFocused: {
                type: Boolean,
                default: false
            },

            isLarge: {
                type: Boolean,
                default: false
            }
        }
    }

</script>
