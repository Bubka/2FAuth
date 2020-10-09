<template>
    <div class="field">
        <input :id="fieldName" type="checkbox" :name="fieldName" class="is-checkradio is-info" v-model="form[fieldName]">
        <label :for="fieldName" class="label" v-html="label"></label>
        <p class="help" v-html="help" v-if="help"></p>
    </div>
</template>

<script>
    export default {
        name: 'FormCheckbox',
        
        data() {
            return {

            }
        },

        props: {
            label: {
                type: String,
                default: ''
            },

            fieldName: {
                type: String,
                default: '',
                required: true
            },

            form: {
                type: Object,
                required: true
            },

            help: {
                type: String,
                default: ''
            },
        }
    }
</script>