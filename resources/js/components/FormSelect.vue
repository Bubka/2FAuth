<template>
    <div class="field">
        <label class="label" v-html="label"></label>
        <div class="control">
            <div class="select">
                <select v-model="form[fieldName]">
                    <option v-for="option in options" :value="option.value">{{ option.text }}</option>
                </select>
            </div>
        </div>
        <field-error :form="form" :field="fieldName" />
        <p class="help" v-html="help" v-if="help"></p>
    </div>
</template>

<script>
    export default {
        name: 'FormSelect',
        
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

            options: {
                type: Array,
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