<template>
    <div class="field" :class="{ 'with-offset' : hasOffset }">
        <label class="label" v-html="label"></label>
        <div class="is-toggle buttons">
            <label class="button is-dark" :disabled="isDisabled" v-for="choice in choices" :class="{ 'is-link' : form[fieldName] === choice.value }">
                <input type="radio" class="is-hidden" :checked="form[fieldName] === choice.value" :value="choice.value" v-model="form[fieldName]" :disabled="isDisabled" />
                <font-awesome-icon :icon="['fas', choice.icon]" v-if="choice.icon" class="mr-3" /> {{ choice.text }}
            </label>
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
        }
    }
</script>