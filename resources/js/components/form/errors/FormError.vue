<template>
    <div v-if="form.errors.any()" role="alert" class="field">
        <slot>
            <span v-if="form.errors.has('error')" v-html="form.errors.get('error')" class="tag is-danger" />
            <span v-else v-html="message" class="tag is-danger" />
	    </slot>
    </div>
</template>

<script>
export default {
    name: 'form-error',

    props: {
        form: {
            type: Object,
            required: true
        },

        message: {
            type: String,
            default: 'There were some problems with your input.'
        }
    }
}
</script>
