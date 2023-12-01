<script setup>
    import { useIdGenerator } from '@/composables/helpers'

    defineOptions({
        inheritAttrs: false
    })

    const { inputId } = useIdGenerator(props.inputType, props.fieldName)

    const props = defineProps({
        modelValue: [String, Number, Boolean],
        label: {
            type: String,
            default: ''
        },
        fieldName: {
            type: String,
            default: '',
            required: true
        },
        fieldError: [String],
        inputType: {
            type: String,
            default: 'text'
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
        },
        isDisabled: {
            type: Boolean,
            default: false
        },
        maxLength: {
            type: Number,
            default: null
        }
    })
</script>

<template>
    <div class="field" :class="{ 'pt-3' : hasOffset }">
        <label :for="inputId" class="label" v-html="$t(label)"></label>
        <div class="control">
            <input 
                :disabled="isDisabled" 
                :id="inputId" 
                :type="inputType" 
                class="input" 
                :value="modelValue" 
                :placeholder="placeholder" 
                v-bind="$attrs"
                v-on:change="$emit('update:modelValue', $event.target.value)"
                :maxlength="maxLength" 
            />
        </div>
        <FieldError v-if="fieldError != undefined" :error="fieldError" :field="fieldName" />
        <p class="help" v-html="$t(help)" v-if="help"></p>
    </div> 
</template>
