<script setup>
    import tabs from './tabs'
    import appSettingService from '@/services/appSettingService'
    import { useAppSettingsUpdater } from '@/composables/appSettingsUpdater'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useNotify, TabBar } from '@2fauth/ui'
    import { useI18n } from 'vue-i18n'
    import { useErrorHandler } from '@2fauth/stores'

    const errorHandler = useErrorHandler()
    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const notify = useNotify()
    const appSettings = useAppSettingsStore()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')

    const fieldErrors = ref({
        restrictList: null,
        restrictRule: null,
    })

    /**
     * Saves a setting on the backend
     * 
     * @param {string} preference 
     * @param {any} value 
     */
    // async function saveSetting(setting, value) {

    // }

    /**
     * Saves or deletes a setting on the backend
     * 
     * @param {string} setting 
     * @param {any} value 
     */
    async function saveOrDeleteSetting(setting, value) {
        fieldErrors.value[setting] = null

        // restrictRule and RestrictList may be empty if the admin decides to not use them.
        // As an app setting cannot be set with an empty string (the 'value' field in the 'Options'
        // table is not NULLABLE), we 'delete' the appSetting instead of updating it.
        if (value == '') {
            appSettingService.delete(setting, { returnError: true }).then(response => {
                appSettings[setting] = ''
                notify.success({ type: 'is-success', text: t('notification.setting_saved') })
            })
            .catch(error => {
                if( error.response.status !== 404 ) {
                    errorHandler.show(error)
                }
            })
        }
        else {
            saveSetting(setting, value)
        }
    }

    /**
     * Saves a setting on the backend
     */
    async function saveSetting(setting, value) {
        const { error } = await useAppSettingsUpdater(setting, value, true)

        if( error != null && Object.prototype.hasOwnProperty.call(fieldErrors.value, setting) ) {
            fieldErrors.value[setting] = error.response.data.message
        }
        else {
            notify.success({ text: t('notification.setting_saved') })
        }
    }

    onBeforeRouteLeave((to) => {
        if (! to.name.startsWith('admin.')) {
            notify.clear()
        }
    })

    onMounted(async () => {
        await appSettings.fetch()
    })

</script>

<template>
    <div>
        <TabBar :tabs="tabs" :active-tab="'admin.auth'" @tab-selected="(to) => router.push({ name: to })" />
        <div class="options-tabs">
            <FormWrapper>
                <form>
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('heading.single_sign_on') }}</h4>
                    <!-- enable SSO -->
                    <FormCheckbox v-model="appSettings.enableSso" @update:model-value="val => saveSetting('enableSso', val)" fieldName="enableSso" label="field.enable_sso" help="field.enable_sso.help" />
                        <!-- use SSO only -->
                        <FormCheckbox v-model="appSettings.useSsoOnly" @update:model-value="val => saveSetting('useSsoOnly', val)" fieldName="useSsoOnly" label="field.use_sso_only" help="field.use_sso_only.help" :isDisabled="!appSettings.enableSso" :isIndented="true" />
                        <!-- Allow Pat In SSO Only -->
                        <FormCheckbox v-model="appSettings.allowPatWhileSsoOnly" @update:model-value="val => saveSetting('allowPatWhileSsoOnly', val)" fieldName="allowPatWhileSsoOnly" label="field.allow_pat_in_sso_only" help="field.allow_pat_in_sso_only.help" :isDisabled="!appSettings.useSsoOnly" :isIndented="true" />
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('heading.registrations') }}</h4>
                    <!-- restrict registration -->
                    <FormCheckbox v-model="appSettings.restrictRegistration" @update:model-value="val => saveSetting('restrictRegistration', val)" fieldName="restrictRegistration" :isDisabled="appSettings.disableRegistration" label="field.restrict_registration" help="field.restrict_registration.help" />
                        <!-- restrict list -->
                        <FormField v-model="appSettings.restrictList" @change:model-value="val => saveOrDeleteSetting('restrictList', val)" :errorMessage="fieldErrors.restrictList" fieldName="restrictList" :isDisabled="!appSettings.restrictRegistration || appSettings.disableRegistration" label="field.restrict_list" help="field.restrict_list.help" :isIndented="true" />
                        <!-- restrict rule -->
                        <FormField v-model="appSettings.restrictRule" @change:model-value="val => saveOrDeleteSetting('restrictRule', val)" :errorMessage="fieldErrors.restrictRule" fieldName="restrictRule" :isDisabled="!appSettings.restrictRegistration || appSettings.disableRegistration" label="field.restrict_rule" help="field.restrict_rule.help" :isIndented="true" leftIcon="Slash" rightIcon="Slash" />
                    <!-- disable registration -->
                    <FormCheckbox v-model="appSettings.disableRegistration" @update:model-value="val => saveSetting('disableRegistration', val)" fieldName="disableRegistration" label="field.disable_registration" help="field.disable_registration.help" />
                        <!-- keep sso registration -->
                        <FormCheckbox v-model="appSettings.keepSsoRegistrationEnabled" @update:model-value="val => saveSetting('keepSsoRegistrationEnabled', val)" fieldName="keepSsoRegistrationEnabled" :isDisabled="!appSettings.enableSso || !appSettings.disableRegistration" label="field.keep_sso_registration_enabled" help="field.keep_sso_registration_enabled.help" :isIndented="true" />
                </form>
            </FormWrapper>
        </div>
        <VueFooter>
            <template #default>
                <NavigationButton action="close" @closed="router.push({ name: returnTo })" :current-page-title="$t('title.admin.auth')" />
            </template>
        </VueFooter>
    </div>
</template>
