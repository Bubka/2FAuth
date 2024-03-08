<script setup>
    const props = defineProps({
        error: {
            type: String,
            required: true
        },
        field: {
            type: String,
            required: true
        },
        alertType: {
            type: String,
            default: 'is-danger'
        }
    })
</script>

<template>
    <div role="alert">
        <p :id="'valError' + field[0].toUpperCase() + field.toLowerCase().slice(1)"
            class="help"
            :class="alertType"
            v-html="error" />
    </div>
</template>