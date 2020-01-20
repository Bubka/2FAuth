<template>
    <p class="help is-danger" v-if="form.errors.has(field)" v-html="form.errors.get(field)" />
</template>

<script>
export default {
    name: 'field-error',

    props: {
        form: {
            type: Object,
            required: true
        },

        field: {
            type: String,
            required: true
        }
    }
}
</script>