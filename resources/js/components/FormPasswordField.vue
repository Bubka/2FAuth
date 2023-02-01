<template>
    <div class="field" :class="{ 'pt-3' : hasOffset }">
        <label :for="this.inputId('password',fieldName)" class="label" v-html="label"></label>
        <div class="control has-icons-right">
            <input
                :disabled="isDisabled"
                :id="this.inputId('password',fieldName)"
                :type="currentType" 
                class="input" 
                v-model="form[fieldName]" 
                :placeholder="placeholder" 
                v-bind="$attrs" 
                v-on:change="$emit('field-changed', form[fieldName])"
                v-on:keyup="checkCapsLock"
            />
            <span v-if="currentType == 'password'" role="button" tabindex="0" class="icon is-small is-right is-clickable" @keyup.enter="setFieldType('text')" @click="setFieldType('text')" :title="$t('auth.forms.reveal_password')">
                <font-awesome-icon :icon="['fas', 'eye-slash']" />
            </span>
            <span v-else role="button" tabindex="0" class="icon is-small is-right is-clickable" @keyup.enter="setFieldType('password')" @click="setFieldType('password')" :title="$t('auth.forms.hide_password')">
                <font-awesome-icon :icon="['fas', 'eye']" />
            </span>
        </div>
        <p class="help is-warning" v-if="hasCapsLockOn" v-html="$t('auth.forms.caps_lock_is_on')" />
        <field-error :form="form" :field="fieldName" />
        <p class="help" v-html="help" v-if="help"></p>
        <div v-if="showRules" class="columns is-mobile is-size-7 mt-0">
            <div class="column is-one-third">
                <span class="has-text-weight-semibold">{{ $t("auth.forms.mandatory_rules") }}</span><br />
                <span class="is-underscored" :class="{'is-dot' : IsLongEnough}"></span>{{ $t('auth.forms.is_long_enough') }}<br/>
            </div>
            <div class="column">
                <span class="has-text-weight-semibold">{{ $t("auth.forms.optional_rules_you_should_follow") }}</span><br />
                <span class="is-underscored" :class="{'is-dot' : hasLowerCase}"></span>{{ $t('auth.forms.has_lower_case') }}<br/>
                <span class="is-underscored" :class="{'is-dot' : hasUpperCase}"></span>{{ $t('auth.forms.has_upper_case') }}<br/>
                <span class="is-underscored" :class="{'is-dot' : hasSpecialChar}"></span>{{ $t('auth.forms.has_special_char') }}<br/>
                <span class="is-underscored" :class="{'is-dot' : hasNumber}"></span>{{ $t('auth.forms.has_number') }}
            </div>
        </div>
    </div> 
</template>

<script>
    export default {
        name: 'FormPasswordField',
        inheritAttrs: false,
        
        data() {
            return {
                currentType: this.inputType,
                hasCapsLockOn: false,
            }
        },

        computed: {
            hasLowerCase() {
                return /[a-z]/.test(this.form[this.fieldName])
            },
            hasUpperCase() {
                return /[A-Z]/.test(this.form[this.fieldName])
            },
            hasNumber() {
                return /[0-9]/.test(this.form[this.fieldName])
            },
            hasSpecialChar() {
                return /[^A-Za-z0-9]/.test(this.form[this.fieldName])
            },
            IsLongEnough() {
                return this.form[this.fieldName].length >= 8
            },
        },

        props: {
            label: {
                type: String,
                default: ''
            },

            fieldName: {
                type: String,
                default: '',
                required: true
            },

            inputType: {
                type: String,
                default: 'password'
            },

            form: {
                type: Object,
                required: true
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
        },

        methods: {
            checkCapsLock(event) {
                this.hasCapsLockOn = event.getModifierState('CapsLock') ? true : false
            },

            setFieldType(event) {
                if (this.currentType != event) {
                    this.currentType = event
                }
            }
        },
    }
</script>