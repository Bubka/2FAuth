<script setup>
    const props = defineProps({
        form: {
            type: Object,
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
        <p :id="'valError' + field[0].toUpperCase() + field.toLowerCase().slice(1)" class="help is-danger" v-if="form.errors.has(field)" v-html="form.errors.get(field)" />
    </div>
</template>