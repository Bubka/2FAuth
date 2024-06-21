<script setup>
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
    })

    const selected = ref(props.modelValue)
</script>

<template>
    <div class="field is-flex">
        <div v-if="isIndented" class="mx-2 pr-1" :style="{ 'opacity': isDisabled ? '0.5' : '1' }">
            <FontAwesomeIcon class="has-text-grey" :icon="['fas', 'chevron-right']" transform="rotate-135"/>
        </div>
        <div>
            <label class="label" v-html="$t(label)" :style="{ 'opacity': isDisabled ? '0.5' : '1' }"></label>
            <div class="control">
                <div class="select">
                    <select v-model="selected" v-on:change="$emit('update:modelValue', $event.target.value)" :disabled="isDisabled">
                        <option v-for="option in options" :value="option.value">{{ $t(option.text) }}</option>
                    </select>
                </div>
            </div>
            <FieldError v-if="fieldError != undefined" :error="fieldError" :field="fieldName" />
            <p class="help" v-html="$t(help)" v-if="help"></p>
        </div>
    </div>
</template>