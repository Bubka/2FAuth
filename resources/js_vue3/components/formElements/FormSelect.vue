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
        options: {
            type: Array,
            required: true
        },
        help: {
            type: String,
            default: ''
        },
    })

    const selected = ref(props.modelValue)
</script>

<template>
    <div class="field">
        <label class="label" v-html="$t(label)"></label>
        <div class="control">
            <div class="select">
                <select v-model="selected" v-on:change="$emit('update:modelValue', $event.target.value)">
                    <option v-for="option in options" :value="option.value">{{ $t(option.text) }}</option>
                </select>
            </div>
        </div>
        <!-- <FieldError :form="form" :field="fieldName" /> -->
        <p class="help" v-html="$t(help)" v-if="help"></p>
    </div>
</template>