<script setup>
    import { useIdGenerator, useValidationErrorIdGenerator } from '@/composables/helpers'

    defineOptions({
        inheritAttrs: false
    })

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
        },
        isIndented: Boolean,
        isLocked: Boolean,
        leftIcon: '',
        rightIcon: '',
        idSuffix: {
            type: String,
            default: ''
        },
    })

    const { inputId } = useIdGenerator(props.inputType, props.fieldName + props.idSuffix)
    const { valErrorId } = useValidationErrorIdGenerator(props.fieldName)
    const legendId = useIdGenerator('legend', props.fieldName).inputId
</script>

<template>
    <div class="mb-3" :class="{ 'pt-3' : hasOffset, 'is-flex' : isIndented }">
        <div v-if="isIndented" class="mx-2 pr-1" :class="{ 'is-opacity-5' : isDisabled || isLocked }">
            <FontAwesomeIcon class="has-text-grey" :icon="['fas', 'chevron-right']" transform="rotate-135"/>
        </div>
        <div class="field" :class="{ 'is-flex-grow-5' : isIndented }">
            <label :for="inputId" class="label" :class="{ 'is-opacity-5' : isDisabled || isLocked }">
                {{ $t(label) }}<FontAwesomeIcon v-if="isLocked" :icon="['fas', 'lock']" class="ml-2" size="xs" />
            </label>
            <div class="control" :class="{ 'has-icons-left' : leftIcon, 'has-icons-right': rightIcon }">
                <input 
                    :disabled="isDisabled || isLocked" 
                    :id="inputId" 
                    :type="inputType" 
                    class="input" 
                    :value="modelValue" 
                    :placeholder="placeholder" 
                    v-bind="$attrs"
                    v-on:input="$emit('update:modelValue', $event.target.value)"
                    v-on:change="$emit('change:modelValue', $event.target.value)"
                    :maxlength="maxLength"
                    :aria-describedby="help ? legendId : undefined"
                    :aria-invalid="fieldError != undefined"
                    :aria-errormessage="fieldError != undefined ? valErrorId : undefined" 
                />
                <span v-if="leftIcon" class="icon is-small is-left">
                    <FontAwesomeIcon :icon="['fas', leftIcon]" transform="rotate-75" size="xs" />
                </span>
                <span v-if="rightIcon" class="icon is-small is-right">
                    <FontAwesomeIcon :icon="['fas', rightIcon]" transform="rotate-75" size="xs" />
                </span>
            </div>
            <FieldError v-if="fieldError != undefined" :error="fieldError" :field="fieldName" />
            <p :id="legendId" class="help" v-html="$t(help)" v-if="help"></p>
        </div>
    </div> 
</template>
