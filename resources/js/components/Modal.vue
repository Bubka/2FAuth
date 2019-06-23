<template>
    <div class="modal modal-otp" v-bind:class="{ 'is-active': isActive }">
      <div class="modal-background" @click.stop="isActive = false"></div>
      <div class="modal-content">
        <slot></slot>
      </div>
      <button class="modal-close is-large" aria-label="close" @click.stop="isActive = false"></button>
    </div>
</template>

<script>
export default {
    name: 'Modal',
    props: {
        value: Boolean,

    },
    computed: {
        isActive: {
            get () {
                return this.value
            },
            set (value) {
                this.$emit('input', value)
            }
        }
    }
}
</script>
