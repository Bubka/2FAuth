<script setup>
    import AdminTabs from '@/layouts/AdminTabs.vue'
    import appSettingService from '@/services/appSettingService'
    import systemService from '@/services/systemService'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useNotifyStore } from '@/stores/notify'
    import { useUserStore } from '@/stores/user'
    import VersionChecker from '@/components/VersionChecker.vue'
    import CopyButton from '@/components/CopyButton.vue'

    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const notify = useNotifyStore()
    const appSettings = useAppSettingsStore()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')

    const infos = ref()
    const listInfos = ref(null)
    const isSendingTestEmail = ref(false)
    const isClearingCache = ref(false)
    const fieldErrors = ref({
        restrictList: null,
        restrictRule: null,
    })
    const _settings = ref({
        checkForUpdate: appSettings.checkForUpdate,
        useEncryption: appSettings.useEncryption,
        restrictRegistration: appSettings.restrictRegistration,
        restrictList: appSettings.restrictList,
        restrictRule: appSettings.restrictRule,
        disableRegistration: appSettings.disableRegistration,
        enableSso: appSettings.enableSso,
    })

    /**
     * Saves a setting on the backend
     * @param {string} preference 
     * @param {any} value 
     */
    function saveSetting(setting, value) {
        fieldErrors.value[setting] = null

        appSettingService.update(setting, value).then(response => {
            appSettings[setting] = value
            useNotifyStore().success({ type: 'is-success', text: trans('settings.forms.setting_saved') })
        })
        .catch(error => {
            if( error.response.status === 422 ) {
                fieldErrors.value[setting] = error.response.data.message
            }
            else {
                notify.error(error);
            }
        })
    }

    /**
     * Saves a setting on the backend
     * @param {string} preference 
     * @param {any} value 
     */
    function saveOrDeleteSetting(setting, value) {
        if (value == '') {
            fieldErrors.value[setting] = null

            appSettingService.delete(setting, { returnError: true }).then(response => {
                appSettings[setting] = ''
                useNotifyStore().success({ type: 'is-success', text: trans('settings.forms.setting_saved') })
            })
            .catch(error => {
                // appSettings[setting] = oldValue

                if( error.response.status !== 404 ) {
                    notify.error(error);
                }
            })
        }
        else {
            saveSetting(setting, value)
        }
    }

    /**
     * Sends a test email
     */
    function sendTestEmail() {
        isSendingTestEmail.value = true;

        systemService.sendTestEmail()
        .finally(() => {
            isSendingTestEmail.value = false;
        })
    }

    /**
     * clears app cache
     */
    function clearCache() {
        isClearingCache.value = true;

        systemService.clearCache().then(response => {
            useNotifyStore().success({ type: 'is-success', text: trans('admin.cache_cleared') })
        })
        .finally(() => {
            isClearingCache.value = false;
        })
    }

    onBeforeRouteLeave((to) => {
        if (! to.name.startsWith('admin.')) {
            notify.clear()
        }
    })

    onMounted(async () => {
        appSettingService.get({ returnError: true })
        .then(response => {
            // we reset those two because they are not registered on server side
            // in order to be able to set them to blank
            _settings.value.restrictList = ''
            _settings.value.restrictRule = ''

            response.data.forEach(setting => {
                appSettings[setting.key] = setting.value
                _settings.value[setting.key] = setting.value
            })
        })
        .catch(error => {
            notify.alert({ text: trans('errors.data_cannot_be_refreshed_from_server') })
        })

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
                    <FormCheckbox v-model="_settings.checkForUpdate" @update:model-value="val => saveSetting('checkForUpdate', val)" fieldName="checkForUpdate" label="commons.check_for_update" help="commons.check_for_update_help" />
                    <VersionChecker />
                    <!-- email config test -->
                    <div class="field">
                        <!-- <h5 class="title is-5">{{ $t('settings.security') }}</h5> -->
                        <label class="label"  v-html="$t('admin.forms.test_email.label')" />
                        <p class="help" v-html="$t('admin.forms.test_email.help')" />
                        <p class="help" v-html="$t('admin.forms.test_email.email_will_be_send_to_x', { email: user.email })" />
                    </div>
                    <div class="columns is-mobile is-vcentered">
                        <div class="column is-narrow">
                            <button type="button" :class="isSendingTestEmail ? 'is-loading' : ''" class="button is-link is-rounded is-small" @click="sendTestEmail">
                                <span class="icon is-small">
                                    <FontAwesomeIcon :icon="['far', 'paper-plane']" />
                                </span>
                                <span>{{ $t('commons.send') }}</span>
                            </button>   
                        </div>
                    </div>    

                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.security') }}</h4>
                    <!-- protect db -->
                    <FormCheckbox v-model="_settings.useEncryption" @update:model-value="val => saveSetting('useEncryption', val)" fieldName="useEncryption" label="admin.forms.use_encryption.label" help="admin.forms.use_encryption.help" />
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('admin.registrations') }}</h4>
                    <!-- restrict registration -->
                    <FormCheckbox v-model="_settings.restrictRegistration" @update:model-value="val => saveSetting('restrictRegistration', val)" fieldName="restrictRegistration" :isDisabled="appSettings.disableRegistration" label="admin.forms.restrict_registration.label" help="admin.forms.restrict_registration.help" />
                        <!-- restrict list -->
                        <FormField v-model="_settings.restrictList" @change:model-value="val => saveOrDeleteSetting('restrictList', val)" :fieldError="fieldErrors.restrictList" fieldName="restrictList" :isDisabled="!appSettings.restrictRegistration || appSettings.disableRegistration" label="admin.forms.restrict_list.label" help="admin.forms.restrict_list.help" :isIndented="true" />
                        <!-- restrict rule -->
                        <FormField v-model="_settings.restrictRule" @change:model-value="val => saveOrDeleteSetting('restrictRule', val)" :fieldError="fieldErrors.restrictRule" fieldName="restrictRule" :isDisabled="!appSettings.restrictRegistration || appSettings.disableRegistration" label="admin.forms.restrict_rule.label" help="admin.forms.restrict_rule.help" :isIndented="true" leftIcon="slash" rightIcon="slash" />
                    <!-- disable registration -->
                    <FormCheckbox v-model="_settings.disableRegistration" @update:model-value="val => saveSetting('disableRegistration', val)" fieldName="disableRegistration" label="admin.forms.disable_registration.label" help="admin.forms.disable_registration.help" />
                    <!-- disable SSO registration -->
                    <FormCheckbox v-model="_settings.enableSso" @update:model-value="val => saveSetting('enableSso', val)" fieldName="enableSso" label="admin.forms.enable_sso.label" help="admin.forms.enable_sso.help" />
                </form>

                <h4 class="title is-4 pt-5 has-text-grey-light">{{ $t('commons.environment') }}</h4>
                <!-- cache management -->
                <div class="field">
                    <!-- <h5 class="title is-5">{{ $t('settings.security') }}</h5> -->
                    <label class="label"  v-html="$t('admin.forms.cache_management.label')" />
                    <p class="help" v-html="$t('admin.forms.cache_management.help')" />
                </div>
                <div class="field mb-5 is-grouped">
                    <p class="control">
                        <button type="button" :class="isClearingCache ? 'is-loading' : ''" class="button is-link is-rounded is-small" @click="clearCache">
                            {{ $t('commons.clear') }}
                        </button>
                    </p>
                </div>
                <!-- env vars -->
                <div class="field">
                    <label class="label"  v-html="$t('admin.variables')" />
                </div>
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