<script setup>
    defineOptions({
        inheritAttrs: false
    })

    const props = defineProps({
        modelValue: Boolean,
        fieldName: {
            type: String,
            default: '',
            required: true
        },
        label: {
            type: String,
            default: ''
        },
        labelClass: {
            type: String,
            default: ''
        },
        help: {
            type: String,
            default: ''
        },
    })

    const emit = defineEmits(['update:modelValue'])
    const attrs = useAttrs()
    const model = computed({
        get() {
            return props.modelValue;
        },
        set(value) {
            emit("update:modelValue", value);
        },
    })

    function toggleModel() {
        if (attrs['disabled'] != true) {
            model.value = !model.value
        }
    }
</script>

<template>
    <div class="field">
        <input :id="fieldName" type="checkbox" :name="fieldName" class="is-checkradio is-info" v-model="model" v-bind="$attrs"/>
        <label tabindex="0" :for="fieldName" class="label" :class="labelClass" v-html="$t(label)" v-on:keypress.space.prevent="toggleModel" />
        <p class="help" v-html="$t(help)" v-if="help" />
    </div>
</template>