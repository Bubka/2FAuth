<script setup>
    import { useIdGenerator } from '@/composables/helpers'
    import { UseColorMode } from '@vueuse/components'

    defineOptions({
        inheritAttrs: false
    })
    
    const props = defineProps({
        modelValue: String,
        modelModifiers: { default: () => ({}) },
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

    const { inputId } = useIdGenerator(props.inputType, props.fieldName)

    const fieldIsLocked = ref(props.isDisabled || props.isEditMode)
    const hasBeenTrimmed = ref(false)
    const componentKey = ref(0);

    const emit = defineEmits(['input:modelValue'])

    /**
     * Removes spaces from the input string
     */
    function emitValue(e) {
        let value = e.target.value
        

        if (props.modelModifiers.trimAll) {
            value = value.replace(/\s+/g, '')
        }

        emit('update:modelValue', value)
    }

    function alertOnSpace(e) {
        let value = e.target.value
        hasBeenTrimmed.value = value.includes(' ')

        emit('update:modelValue', value)
    }

    function forceRefresh(e) {
        hasBeenTrimmed.value = e.target.value.includes(' ')
        componentKey.value += 1
    }

</script>

<template>
    <label :for="inputId" class="label" v-html="$t(label)" />
    <div class="field has-addons mb-0" :class="{ 'pt-3' : hasOffset }">
        <div class="control" :class="{ 'is-expanded': isExpanded }">
            <input 
                :key="componentKey"
                :disabled="fieldIsLocked" 
                :id="inputId" 
                :type="inputType" 
                class="input" 
                :value="modelValue" 
                :placeholder="placeholder" 
                v-bind="$attrs"
                v-on:input="alertOnSpace"
                v-on:change="emitValue"
                v-on:blur="forceRefresh"
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
    <FieldError v-if="hasBeenTrimmed" :error="$t('twofaccounts.forms.spaces_are_ignored')" :field="'spaces'" :alertType="'is-warning'" />
    <FieldError v-if="fieldError != undefined" :error="fieldError" :field="fieldName" />
    <p class="help" v-html="$t(help)" v-if="help"></p>
</template>
