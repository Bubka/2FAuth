<script setup>
    import { useIdGenerator, useValidationErrorIdGenerator } from '@/composables/helpers'

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
        options: {
            type: Array,
            required: true
        },
        help: {
            type: String,
            default: ''
        },
        isIndented: Boolean,
        isDisabled: Boolean,
        isLocked: Boolean,
        idSuffix: {
            type: String,
            default: ''
        },
    })

    const selected = ref(props.modelValue)
    const { inputId } = useIdGenerator('select', props.fieldName + props.idSuffix)
    const { valErrorId } = useValidationErrorIdGenerator(props.fieldName)
    const legendId = useIdGenerator('legend', props.fieldName + props.idSuffix).inputId
</script>

<template>
    <div class="field is-flex">
        <div v-if="isIndented" class="mx-2 pr-1" :class="{ 'is-opacity-5' : isDisabled || isLocked }">
            <FontAwesomeIcon class="has-text-grey" :icon="['fas', 'chevron-right']" transform="rotate-135"/>
        </div>
        <div>
            <label :for="inputId" class="label" :class="{ 'is-opacity-5' : isDisabled || isLocked }">
                {{ $t(label) }}<FontAwesomeIcon v-if="isLocked" :icon="['fas', 'lock']" class="ml-2" size="xs" />
            </label>
            <div class="control">
                <div class="select">
                    <select
                        :id="inputId"
                        v-model="selected"
                        v-on:change="$emit('update:modelValue', $event.target.value)"
                        :disabled="isDisabled || isLocked"
                        :aria-describedby="help ? legendId : undefined"
                        :aria-invalid="fieldError != undefined"
                        :aria-errormessage="fieldError != undefined ? valErrorId : undefined" 
                    >
                        <option v-for="option in options" :value="option.value">{{ $t(option.text) }}</option>
                    </select>
                </div>
            </div>
            <FieldError v-if="fieldError != undefined" :error="fieldError" :field="fieldName" />
            <p :id="legendId" class="help" v-html="$t(help)" v-if="help"></p>
        </div>
    </div>
</template>