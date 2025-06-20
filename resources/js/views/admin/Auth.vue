<script setup>
    import AdminTabs from '@/layouts/AdminTabs.vue'
    import appSettingService from '@/services/appSettingService'
    import { useAppSettingsUpdater } from '@/composables/appSettingsUpdater'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useNotifyStore } from '@/stores/notify'
    import { useI18n } from 'vue-i18n'

    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const notify = useNotifyStore()
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
     * @param {string} preference 
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
                notify.success({ type: 'is-success', text: t('message.settings.forms.setting_saved') })
            })
            .catch(error => {
                if( error.response.status !== 404 ) {
                    notify.error(error);
                }
            })
        }
        else {
            const { error } = await useAppSettingsUpdater(setting, value, true)

            if( error ) {
                fieldErrors.value[setting] = error.response.data.message
            }
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
        <AdminTabs activeTab="admin.auth" />
        <div class="options-tabs">
            <FormWrapper>
                <form>
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('message.admin.single_sign_on') }}</h4>
                    <!-- enable SSO -->
                    <FormCheckbox v-model="appSettings.enableSso" @update:model-value="val => useAppSettingsUpdater('enableSso', val)" fieldName="enableSso" label="message.admin.forms.enable_sso.label" help="message.admin.forms.enable_sso.help" />
                        <!-- use SSO only -->
                        <FormCheckbox v-model="appSettings.useSsoOnly" @update:model-value="val => useAppSettingsUpdater('useSsoOnly', val)" fieldName="useSsoOnly" label="message.admin.forms.use_sso_only.label" help="message.admin.forms.use_sso_only.help" :isDisabled="!appSettings.enableSso" :isIndented="true" />
                        <!-- Allow Pat In SSO Only -->
                        <FormCheckbox v-model="appSettings.allowPatWhileSsoOnly" @update:model-value="val => useAppSettingsUpdater('allowPatWhileSsoOnly', val)" fieldName="allowPatWhileSsoOnly" label="message.admin.forms.allow_pat_in_sso_only.label" help="message.admin.forms.allow_pat_in_sso_only.help" :isDisabled="!appSettings.useSsoOnly" :isIndented="true" />
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('message.admin.registrations') }}</h4>
                    <!-- restrict registration -->
                    <FormCheckbox v-model="appSettings.restrictRegistration" @update:model-value="val => useAppSettingsUpdater('restrictRegistration', val)" fieldName="restrictRegistration" :isDisabled="appSettings.disableRegistration" label="message.admin.forms.restrict_registration.label" help="message.admin.forms.restrict_registration.help" />
                        <!-- restrict list -->
                        <FormField v-model="appSettings.restrictList" @change:model-value="val => saveOrDeleteSetting('restrictList', val)" :errorMessage="fieldErrors.restrictList" fieldName="restrictList" :isDisabled="!appSettings.restrictRegistration || appSettings.disableRegistration" label="message.admin.forms.restrict_list.label" help="message.admin.forms.restrict_list.help" :isIndented="true" />
                        <!-- restrict rule -->
                        <FormField v-model="appSettings.restrictRule" @change:model-value="val => saveOrDeleteSetting('restrictRule', val)" :errorMessage="fieldErrors.restrictRule" fieldName="restrictRule" :isDisabled="!appSettings.restrictRegistration || appSettings.disableRegistration" label="message.admin.forms.restrict_rule.label" help="message.admin.forms.restrict_rule.help" :isIndented="true" leftIcon="Slash" rightIcon="Slash" />
                    <!-- disable registration -->
                    <FormCheckbox v-model="appSettings.disableRegistration" @update:model-value="val => useAppSettingsUpdater('disableRegistration', val)" fieldName="disableRegistration" label="message.admin.forms.disable_registration.label" help="message.admin.forms.disable_registration.help" />
                        <!-- keep sso registration -->
                        <FormCheckbox v-model="appSettings.keepSsoRegistrationEnabled" @update:model-value="val => useAppSettingsUpdater('keepSsoRegistrationEnabled', val)" fieldName="keepSsoRegistrationEnabled" :isDisabled="!appSettings.enableSso || !appSettings.disableRegistration" label="message.admin.forms.keep_sso_registration_enabled.label" help="message.admin.forms.keep_sso_registration_enabled.help" :isIndented="true" />
                </form>
            </FormWrapper>
        </div>
        <VueFooter :showButtons="true">
            <NavigationButton action="close" @closed="router.push({ name: returnTo })" :current-page-title="$t('title.admin.auth')" />
        </VueFooter>
    </div>
</template>
