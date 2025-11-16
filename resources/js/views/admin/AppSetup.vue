<script setup>
    import tabs from './tabs'
    import systemService from '@/services/systemService'
    import { useAppSettingsUpdater } from '@/composables/appSettingsUpdater'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useNotify, TabBar } from '@2fauth/ui'
    import { useErrorHandler } from '@2fauth/stores'
    import { useUserStore } from '@/stores/user'
    import VersionChecker from '@/components/VersionChecker.vue'
    import CopyButton from '@/components/CopyButton.vue'
    import { useI18n } from 'vue-i18n'
    import { LucideSend } from 'lucide-vue-next'

    const errorHandler = useErrorHandler()
    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const notify = useNotify()
    const appSettings = useAppSettingsStore()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')

    const infos = ref()
    const listInfos = ref(null)
    const isSendingTestEmail = ref(false)
    const testEmailError = ref(null)
    const isClearingCache = ref(false)
    const healthEndPoint = $2fauth.config.subdirectory + '/up'
    const healthEndPointFullPath = location.hostname + $2fauth.config.subdirectory + '/up'

    /**
     * Sends a test email
     */
    function sendTestEmail() {
        isSendingTestEmail.value = true
        testEmailError.value = null

        systemService.sendTestEmail({returnError: true}).then(response => {
            notify.success({ text: t('notification.test_email_sent') })
        })
        .catch(error => {
            if (error.response.status === 424) {
                testEmailError.value = error.response.data.details
            }
            else  {
                errorHandler.show(error)
            }
        })
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
            notify.success({ text: t('notification.cache_cleared') })
        })
        .finally(() => {
            isClearingCache.value = false;
        })
    }

    /**
     * Saves a setting
     */
    async function saveSetting(setting, value) {
        const { error } = await useAppSettingsUpdater(setting, value)

        if (error == null) {
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
        <TabBar :tabs="tabs" :active-tab="'admin.appSetup'" @tab-selected="(to) => router.push({ name: to })" />
        <div class="options-tabs">
            <FormWrapper>
                <form>
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('heading.general') }}</h4>
                    <!-- Check for update -->
                    <FormCheckbox v-model="appSettings.checkForUpdate" @update:model-value="val => saveSetting('checkForUpdate', val)" fieldName="checkForUpdate" label="field.check_for_update" help="field.check_for_update.help" />
                    <VersionChecker />
                    <!-- email config test -->
                    <div class="field">
                        <label class="label" for="btnTestEmail">{{ $t('field.test_email') }}</label>
                        <p class="help">{{ $t('field.test_email.help') }}</p>
                        <p class="help">
                            <i18n-t keypath="message.email_will_be_send_to_x" tag="span">
                                <template v-slot:email>
                                    <span class="is-family-code has-text-info">{{ user.email }}</span>
                                </template>
                            </i18n-t>
                        </p>
                    </div>
                    <div class="columns is-mobile is-vcentered">
                        <div class="column is-narrow">
                            <button id="btnTestEmail" type="button" :class="isSendingTestEmail ? 'is-loading' : ''" class="button is-link is-rounded is-small" @click="sendTestEmail" >
                                <span class="icon is-small">
                                    <LucideSend class="icon-size-1" />
                                </span>
                                <span>{{ $t('label.send') }}</span>
                            </button>   
                        </div>
                    </div>
                    <div v-if="testEmailError" class="about-debug box is-family-monospace is-size-7">
                        <FormFieldError v-if="testEmailError" :error="$t('error.email_sending_failed')" :field="'testEmail'" />
                        {{ testEmailError }}
                    </div>
                    <!-- healthcheck -->
                    <div class="field">
                        <label class="label" for="lnkHealthCheck">{{ $t('field.health_endpoint') }}</label>
                        <p class="help">{{ $t('field.health_endpoint.help') }}</p>
                    </div>
                    <div class="field mb-5">
                        <a id="lnkHealthCheck" target="_blank" :href="healthEndPoint">{{ healthEndPointFullPath }}</a>
                    </div>
                    <h4 class="title is-4 pt-5 has-text-grey-light">{{ $t('heading.storage') }}</h4>
                    <!-- store icons in database -->
                    <FormCheckbox v-model="appSettings.storeIconsInDatabase" @update:model-value="val => saveSetting('storeIconsInDatabase', val)" fieldName="storeIconsInDatabase" label="field.store_icon_to_database" help="field.store_icon_to_database.help" />
                    <p class="help">{{ $t('field.store_icon_to_database.help_bis') }}</p>
                    <h4 class="title is-4 pt-5 has-text-grey-light">{{ $t('heading.security') }}</h4>
                    <!-- protect db -->
                    <FormCheckbox v-model="appSettings.useEncryption" @update:model-value="val => saveSetting('useEncryption', val)" fieldName="useEncryption" label="field.use_encryption" help="field.use_encryption.help" />
                </form>

                <h4 class="title is-4 pt-5 has-text-grey-light">{{ $t('heading.environment') }}</h4>
                <!-- cache management -->
                <div class="field">
                    <label for="btnClearCache" class="label">{{ $t('field.cache_management') }}</label>
                    <p class="help">{{ $t('field.cache_management.help') }}</p>
                </div>
                <div class="field mb-5 is-grouped">
                    <p class="control">
                        <button id="btnClearCache" type="button" :class="isClearingCache ? 'is-loading' : ''" class="button is-link is-rounded is-small" @click="clearCache">
                            {{ $t('label.clear') }}
                        </button>
                    </p>
                </div>
                <!-- env vars -->
                <div class="field">
                    <label for="btnCopyEnvVars" class="label">{{ $t('label.variables') }}</label>
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
                    {{ $t('error.error_during_data_fetching') }}
                </div>
            </FormWrapper>
        </div>
        <VueFooter>
            <template #default>
                <NavigationButton action="close" @closed="router.push({ name: returnTo })" :current-page-title="$t('title.admin.appSetup')" />
            </template>
        </VueFooter>
    </div>
</template>