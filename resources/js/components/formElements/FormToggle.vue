<script setup>
    import { useIdGenerator, useValidationErrorIdGenerator } from '@/composables/helpers'
    import { UseColorMode } from '@vueuse/components'

    const props = defineProps({
        modelValue: [String, Number, Boolean],
        choices: {
            type: Array,
            required: true
        },
        fieldName: {
            type: String,
            required: true
        },
        fieldError: [String],
        hasOffset: Boolean,
        isDisabled: Boolean,
        label: {
            type: String,
            default: ''
        },
        help: {
            type: String,
            default: ''
        },
    })

    // defines what events our component emits
    const emit = defineEmits(['update:modelValue'])
    const { valErrorId } = useValidationErrorIdGenerator(props.fieldName)
    const legendId = useIdGenerator('legend', props.fieldName).inputId

    function setRadio(event) {
        emit('update:modelValue', event)
    }
    
</script>

<template>
    <div class="field" :class="{ 'pt-3': hasOffset }">
        <span v-if="label" class="label" v-html="$t(label)" />
        <div
            id="rdoGroup"
            role="radiogroup"
            :aria-describedby="help ? legendId : undefined"
            :aria-invalid="fieldError != undefined"
            :aria-errormessage="fieldError != undefined ? valErrorId : undefined" 
            class="is-toggle buttons"
        >
            <UseColorMode v-slot="{ mode }">
                <button
                    v-for="choice in choices"
                    :key="choice.value"
                    :id="useIdGenerator('button',fieldName+choice.value).inputId"
                    role="radio"
                    type="button"
                    class="button"
                    :aria-checked="modelValue===choice.value"
                    :disabled="isDisabled"
                    :class="{
                        'is-link': modelValue===choice.value,
                        'is-dark': mode==='dark',
                        'is-multiline': choice.legend,
                    }"
                    v-on:click.stop="setRadio(choice.value)">
                    <input
                        :id="useIdGenerator('radio',choice.value).inputId"
                        type="radio"
                        class="is-hidden"
                        :checked="modelValue===choice.value"
                        :value="choice.value"
                        :disabled="isDisabled"
                    />
                    <span v-if="choice.legend" v-html="$t(choice.legend)" class="is-block is-size-7" />
                    <FontAwesomeIcon :icon="['fas',choice.icon]" v-if="choice.icon" class="mr-2" />
                    <label :for="useIdGenerator('button',fieldName+choice.value).inputId" class="is-clickable">
                        {{ $t(choice.text) }}
                    </label>
                </button>
            </UseColorMode>
        </div>
        <FieldError v-if="fieldError != undefined" :error="fieldError" :field="fieldName" />
        <p :id="legendId" class="help" v-html="$t(help)" v-if="help" />
    </div>
</template>