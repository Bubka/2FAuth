<script setup>
    import { useIdGenerator } from '@/composables/helpers'

    defineOptions({
        inheritAttrs: true
    })

    const props = defineProps({
        modelValue: [String],
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
            default: 'password'
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
        showRules: {
            type: Boolean,
            default: false
        },
    })

    const { inputId } = useIdGenerator(props.inputType, props.fieldName)
    const currentType = ref(props.inputType)
    const hasCapsLockOn = ref(false)

    const hasLowerCase = computed(() => {
        return /[a-z]/.test(props.modelValue)
    })
    const hasUpperCase = computed(() => {
        return /[A-Z]/.test(props.modelValue)
    })
    const hasNumber = computed(() => {
        return /[0-9]/.test(props.modelValue)
    })
    const hasSpecialChar = computed(() => {
        return /[^A-Za-z0-9]/.test(props.modelValue)
    })
    const IsLongEnough = computed(() => {
        return props.modelValue.length >= 8
    })

    function checkCapsLock(event) {
        if (typeof event.getModifierState === 'function') {
            hasCapsLockOn.value = event.getModifierState('CapsLock') ? true : false
        }
    }

    function setFieldType(event) {
        if (currentType.value != event) {
            currentType.value = event
        }
    }
</script>

<template>
    <div class="field" :class="{ 'pt-3' : hasOffset }">
        <label :for="inputId" class="label" v-html="$t(label)" />
        <div class="control has-icons-right">
            <input
                :disabled="isDisabled"
                :id="inputId"
                :type="currentType" 
                class="input" 
                :value="modelValue" 
                :placeholder="placeholder" 
                v-bind="$attrs" 
                v-on:input="$emit('update:modelValue', $event.target.value)"
                v-on:keyup="checkCapsLock"
            />
            <span v-if="currentType == 'password'" role="button" id="btnTogglePassword" tabindex="0" class="icon is-small is-right is-clickable" @keyup.enter="setFieldType('text')" @click="setFieldType('text')" :title="$t('auth.forms.reveal_password')">
                <font-awesome-icon :icon="['fas', 'eye-slash']" />
            </span>
            <span v-else role="button" id="btnTogglePassword" tabindex="0" class="icon is-small is-right is-clickable" @keyup.enter="setFieldType('password')" @click="setFieldType('password')" :title="$t('auth.forms.hide_password')">
                <font-awesome-icon :icon="['fas', 'eye']" />
            </span>
        </div>
        <p class="help is-warning" v-if="hasCapsLockOn" v-html="$t('auth.forms.caps_lock_is_on')" />
        <FieldError v-if="fieldError != undefined" :error="fieldError" :field="fieldName" />
        <p class="help" v-html="$t(help)" v-if="help" />
        <div v-if="showRules" class="columns is-mobile is-size-7 mt-0">
            <div class="column is-one-third">
                <span class="has-text-weight-semibold">{{ $t("auth.forms.mandatory_rules") }}</span><br />
                <span class="is-underscored" id="valPwdIsLongEnough" :class="{'is-dot' : IsLongEnough}"></span>{{ $t('auth.forms.is_long_enough') }}<br/>
            </div>
            <div class="column">
                <span class="has-text-weight-semibold">{{ $t("auth.forms.optional_rules_you_should_follow") }}</span><br />
                <span class="is-underscored" id="valPwdHasLowerCase" :class="{'is-dot' : hasLowerCase}"></span>{{ $t('auth.forms.has_lower_case') }}<br/>
                <span class="is-underscored" id="valPwdHasUpperCase" :class="{'is-dot' : hasUpperCase}"></span>{{ $t('auth.forms.has_upper_case') }}<br/>
                <span class="is-underscored" id="valPwdHasSpecialChar" :class="{'is-dot' : hasSpecialChar}"></span>{{ $t('auth.forms.has_special_char') }}<br/>
                <span class="is-underscored" id="valPwdHasNumber" :class="{'is-dot' : hasNumber}"></span>{{ $t('auth.forms.has_number') }}
            </div>
        </div>
    </div> 
</template>