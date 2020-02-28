<template>
    <div class="field" :class="{ 'with-offset' : hasOffset }">
        <label class="label" v-html="label"></label>
        <div class="control">
            <input :id="fieldName" :type="inputType" class="input" v-model="form[fieldName]" :placeholder="placeholder" v-bind="$attrs" />
        </div>
        <field-error :form="form" :field="fieldName" />
        <p class="help" v-html="help" v-if="help"></p>
    </div> 
</template>

<script>
    export default {
        name: 'FormField',
        inheritAttrs: false,
        
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

            inputType: {
                type: String,
                default: 'text'
            },

            form: {
                type: Object,
                required: true
            },

            placeholder: {
                type: String,
                default: ''
            },

            help: {
                type: String,
                default: ''
            },

            hasOffset: {
                type: Boolean,
                default: false
            }
        }
    }
</script>