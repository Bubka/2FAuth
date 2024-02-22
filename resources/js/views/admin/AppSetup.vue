<script setup>
    import AdminTabs from '@/layouts/AdminTabs.vue'
    import appSettingService from '@/services/appSettingService'
    import systemService from '@/services/systemService'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useNotifyStore } from '@/stores/notify'
    import VersionChecker from '@/components/VersionChecker.vue'
    import CopyButton from '@/components/CopyButton.vue'

    const $2fauth = inject('2fauth')
    const notify = useNotifyStore()
    const appSettings = useAppSettingsStore()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')

    const infos = ref()
    const listInfos = ref(null)

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

    onMounted(() => {
        systemService.getSystemInfos({returnError: true}).then(response => {
            infos.value = response.data.common
        })
        .catch(() => {
            infos.value = null
        })
    })

</script>

<template>
    <div>
        <AdminTabs activeTab="admin.appSetup" />
        <div class="options-tabs">
            <FormWrapper>
                <form>
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.general') }}</h4>
                    <!-- Check for update -->
                    <FormCheckbox v-model="appSettings.checkForUpdate" @update:model-value="val => saveSetting('checkForUpdate', val)" fieldName="checkForUpdate" label="commons.check_for_update" help="commons.check_for_update_help" />
                    <VersionChecker />
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.security') }}</h4>
                    <!-- protect db -->
                    <FormCheckbox v-model="appSettings.useEncryption" @update:model-value="val => saveSetting('useEncryption', val)" fieldName="useEncryption" label="admin.forms.use_encryption.label" help="admin.forms.use_encryption.help" />
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('admin.registrations') }}</h4>
                    <!-- disable registration -->
                    <FormCheckbox v-model="appSettings.disableRegistration" @update:model-value="val => saveSetting('disableRegistration', val)" fieldName="disableRegistration" label="admin.forms.disable_registration.label" help="admin.forms.disable_registration.help" />
                    <!-- disable SSO registration -->
                    <FormCheckbox v-model="appSettings.enableSso" @update:model-value="val => saveSetting('enableSso', val)" fieldName="enableSso" label="admin.forms.enable_sso.label" help="admin.forms.enable_sso.help" />
                </form>
                <h4 class="title is-4 pt-5 has-text-grey-light">{{ $t('commons.environment') }}</h4>
                <div v-if="infos" class="about-debug box is-family-monospace is-size-7">
                    <CopyButton id="btnCopyEnvVars" :token="listInfos?.innerText" />
                    <ul ref="listInfos" id="listInfos">
                        <li v-for="(value, preference) in infos" :value="value" :key="preference">
                            <b>{{ preference }}</b>: <span class="has-text-grey">{{ value }}</span>
                        </li>
                    </ul>
                </div>
                <div v-else-if="infos === null" class="about-debug box is-family-monospace is-size-7 has-text-warning-dark">
                    {{ $t('errors.error_during_data_fetching') }}
                </div>
            </FormWrapper>
        </div>
        <VueFooter :showButtons="true">
            <ButtonBackCloseCancel :returnTo="{ name: returnTo }" action="close" />
        </VueFooter>
    </div>
</template>