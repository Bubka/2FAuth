<script setup>
    import { useIdGenerator } from '@/composables/helpers'
    import { UseColorMode } from '@vueuse/components'

    defineOptions({
        inheritAttrs: false
    })

    const { inputId } = useIdGenerator(props.inputType, props.fieldName)

    const props = defineProps({
        modelValue: [String, Number, Boolean],
        isEditMode: {
            type: Boolean,
            default: false
        },
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
        isExpanded: {
            type: Boolean,
            default: true
        },
        maxLength: {
            type: Number,
            default: null
        }
    })

    const fieldIsLocked = ref(props.isDisabled || props.isEditMode)
</script>

<template>
    <div class="field" style="margin-bottom: 0.5rem;">
        <label :for="inputId" class="label" v-html="$t(label)" />
    </div>
    <div class="field has-addons" :class="{ 'pt-3' : hasOffset }">
        <div class="control" :class="{ 'is-expanded': isExpanded }">
            <input 
                :disabled="fieldIsLocked" 
                :id="inputId" 
                :type="inputType" 
                class="input" 
                :value="modelValue" 
                :placeholder="placeholder" 
                v-bind="$attrs"
                v-on:input="$emit('update:modelValue', $event.target.value)"
                :maxlength="maxLength" 
            />
        </div>
        <UseColorMode v-slot="{ mode }" v-if="isEditMode">
            <div class="control" v-if="fieldIsLocked">
                <button type="button" class="button field-lock" :class="{'is-dark' : mode == 'dark'}" @click.stop="fieldIsLocked = false" :title="$t('twofaccounts.forms.unlock.title')">
                    <span class="icon">
                        <FontAwesomeIcon :icon="['fas', 'lock']" />
                    </span>
                </button>
            </div>
            <div class="control" v-else>
                <button type="button" class="button field-unlock" :class="{'is-dark' : mode == 'dark'}" @click.stop="fieldIsLocked = true" :title="$t('twofaccounts.forms.lock.title')">
                    <span class="icon has-text-danger">
                        <FontAwesomeIcon :icon="['fas', 'lock-open']" />
                    </span>
                </button>
            </div>
        </UseColorMode>
    </div>
    <FieldError v-if="fieldError != undefined" :error="fieldError" :field="fieldName" />
    <p class="help" v-html="$t(help)" v-if="help"></p>
</template>
