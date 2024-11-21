<script setup>
    import { useValidationErrorIdGenerator } from '@/composables/helpers'

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

    const { valErrorId } = useValidationErrorIdGenerator(props.field)
</script>

<template>
    <div role="alert">
        <p :id="valErrorId"
            class="help"
            :class="alertType"
            v-html="error" />
    </div>
</template>