<template>
    <div v-if="form.errors.any()" role="alert" class="field">
    	<span v-if="message" v-html="message" class="tag is-danger" />
        <ul>
            <li v-for="error in form.errors.flatten()" v-html="error" class="tag is-danger" />
        </ul>
    </div>
</template>

<script>
export default {
    name: 'form-errors',

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
