<script setup>
    const props = defineProps({
        error: {
            type: String,
            required: true
        },
        field: {
            type: String,
            required: true
        }
    })
</script>

<template>
    <div role="alert">
        <p :id="'valError' + field[0].toUpperCase() + field.toLowerCase().slice(1)"
            class="help is-danger"
            v-html="error" />
    </div>
</template>