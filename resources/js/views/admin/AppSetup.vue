<script setup>
    import AdminTabs from '@/layouts/AdminTabs.vue'
    import appSettingService from '@/services/appSettingService'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useNotifyStore } from '@/stores/notify'
    import VersionChecker from '@/components/VersionChecker.vue'

    const $2fauth = inject('2fauth')
    const notify = useNotifyStore()
    const appSettings = useAppSettingsStore()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')

    /**
     * Saves a setting on the backend
     * @param {string} preference 
     * @param {any} value 
     */
    function saveSetting(setting, value) {
        appSettingService.update(setting, value).then(response => {
            useNotifyStore().success({ type: 'is-success', text: trans('settings.forms.setting_saved') })
        })
    }

    onBeforeRouteLeave((to) => {
        if (! to.name.startsWith('admin.')) {
            notify.clear()
        }
    })

</script>

<template>
    <div>
        <AdminTabs activeTab="admin.appSetup" />
        <div class="options-tabs">
            <FormWrapper>
                <form>
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.general') }}</h4>
                    <div class="block has-text-grey">
                        <p class="mb-2">{{ $t('admin.administration_legend') }}</p>
                    </div>
                    <!-- Check for update -->
                    <FormCheckbox v-model="appSettings.checkForUpdate" @update:model-value="val => saveSetting('checkForUpdate', val)" fieldName="checkForUpdate" label="commons.check_for_update" help="commons.check_for_update_help" />
                    <VersionChecker />
                    <!-- protect db -->
                    <FormCheckbox v-model="appSettings.useEncryption" @update:model-value="val => saveSetting('useEncryption', val)" fieldName="useEncryption" label="admin.forms.use_encryption.label" help="admin.forms.use_encryption.help" />
                    <!-- disable registration -->
                    <FormCheckbox v-model="appSettings.disableRegistration" @update:model-value="val => saveSetting('disableRegistration', val)" fieldName="disableRegistration" label="admin.forms.disable_registration.label" help="admin.forms.disable_registration.help" />
                    <!-- disable SSO registration -->
                    <FormCheckbox v-model="appSettings.enableSso" @update:model-value="val => saveSetting('enableSso', val)" fieldName="enableSso" label="admin.forms.enable_sso.label" help="admin.forms.enable_sso.help" />
                </form>
            </FormWrapper>
        </div>
        <VueFooter :showButtons="true">
            <ButtonBackCloseCancel :returnTo="{ name: returnTo }" action="close" />
        </VueFooter>
    </div>
</template>