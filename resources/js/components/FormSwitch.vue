<template>
    <div class="field">
        <label :for="fieldName" class="label" v-html="label"></label>
        <input :id="fieldName" type="checkbox" :name="fieldName" class="switch is-thin is-info" v-model="form[fieldName]">
        <label :for="fieldName" class="label"></label>
        <p class="help" v-html="help" v-if="help"></p>
    </div>
</template>

<script>
    export default {
        name: 'FormSwitch',
        
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