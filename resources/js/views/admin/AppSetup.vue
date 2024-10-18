<script setup>
    import AdminTabs from '@/layouts/AdminTabs.vue'
    import systemService from '@/services/systemService'
    import { useAppSettingsUpdater } from '@/composables/appSettingsUpdater'
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
    const healthEndPoint = $2fauth.config.subdirectory + '/up'
    const healthEndPointFullPath = location.hostname + $2fauth.config.subdirectory + '/up'

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
        <AdminTabs activeTab="admin.appSetup" />
        <div class="options-tabs">
            <FormWrapper>
                <form>
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.general') }}</h4>
                    <!-- Check for update -->
                    <FormCheckbox v-model="appSettings.checkForUpdate" @update:model-value="val => useAppSettingsUpdater('checkForUpdate', val)" fieldName="checkForUpdate" label="commons.check_for_update" help="commons.check_for_update_help" />
                    <VersionChecker />
                    <!-- email config test -->
                    <div class="field">
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
                    <!-- healthcheck -->
                    <div class="field">
                        <label class="label"  v-html="$t('admin.forms.health_endpoint.label')" />
                        <p class="help" v-html="$t('admin.forms.health_endpoint.help')" />
                    </div>
                    <div class="field mb-5">
                        <a target="_blank" :href="healthEndPoint">{{ healthEndPointFullPath }}</a>
                    </div>
                    <h4 class="title is-4 pt-5 has-text-grey-light">{{ $t('admin.storage') }}</h4>
                    <!-- store icons in database -->
                    <FormCheckbox v-model="appSettings.storeIconsInDatabase" @update:model-value="val => useAppSettingsUpdater('storeIconsInDatabase', val)" fieldName="storeIconsInDatabase" label="admin.forms.store_icon_to_database.label" help="admin.forms.store_icon_to_database.help" />
                    <h4 class="title is-4 pt-5 has-text-grey-light">{{ $t('settings.security') }}</h4>
                    <!-- protect db -->
                    <FormCheckbox v-model="appSettings.useEncryption" @update:model-value="val => useAppSettingsUpdater('useEncryption', val)" fieldName="useEncryption" label="admin.forms.use_encryption.label" help="admin.forms.use_encryption.help" />
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