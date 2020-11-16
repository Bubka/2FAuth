<template>
    <div class="field" :class="{ 'with-offset' : hasOffset }">
        <label class="label" v-html="label"></label>
        <div class="is-toggle buttons">
            <a class="button is-dark" :disabled="isDisabled" v-for="choice in choices" :class="{ 'is-link' : form[fieldName] === choice }" @click="setField(choice)">{{ choice }}</a>
        </div>
        <field-error :form="form" :field="fieldName" />
        <p class="help" v-html="help" v-if="help"></p>
    </div>
</template>

<script>
    export default {
        name: 'FormToggle',
        
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

            choices: {
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

            hasOffset: {
                type: Boolean,
                default: false
            },

            isDisabled: {
                type: Boolean,
                default: false
            }
        },

        methods: {

            setField(value) {
                if( !this.isDisabled ) {
                    this.form[this.fieldName] = value
                }
            }

        }
    }
</script>