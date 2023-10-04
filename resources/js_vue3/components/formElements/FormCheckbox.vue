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
    const checked = ref(props.modelValue)

    function setCheckbox() {
        if (attrs['disabled'] == undefined) {
            emit('update:modelValue', checked)
        }
    }
</script>

<template>
    <div class="field">
        <input :id="fieldName" type="checkbox" :name="fieldName" class="is-checkradio is-info" v-model="checked" v-on:change="setCheckbox" v-bind="$attrs"/>
        <label tabindex="0" :for="fieldName" class="label" :class="labelClass" v-html="$t(label)" v-on:keypress.space.prevent="setCheckbox" />
        <p class="help" v-html="$t(help)" v-if="help" />
    </div>
</template>