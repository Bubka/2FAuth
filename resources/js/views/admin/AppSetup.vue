<script setup>
    import tabs from './tabs'
    import systemService from '@/services/systemService'
    import { useAppSettingsUpdater } from '@/composables/appSettingsUpdater'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useNotify, TabBar } from '@2fauth/ui'
    import { useUserStore } from '@/stores/user'
    import VersionChecker from '@/components/VersionChecker.vue'
    import CopyButton from '@/components/CopyButton.vue'
    import { useI18n } from 'vue-i18n'

    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const notify = useNotify()
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
            useNotify().success({ text: t('message.admin.cache_cleared') })
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
        <TabBar :tabs="tabs" :active-tab="'admin.appSetup'" @tab-selected="(to) => router.push({ name: to })" />
        <div class="options-tabs">
            <FormWrapper>
                <form>
                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('message.settings.general') }}</h4>
                    <!-- Check for update -->
                    <FormCheckbox v-model="appSettings.checkForUpdate" @update:model-value="val => useAppSettingsUpdater('checkForUpdate', val)" fieldName="checkForUpdate" label="message.check_for_update" help="message.check_for_update_help" />
                    <VersionChecker />
                    <!-- email config test -->
                    <div class="field">
                        <label class="label" for="btnTestEmail" v-html="$t('message.admin.forms.test_email.label')" />
                        <p class="help" v-html="$t('message.admin.forms.test_email.help')" />
                        <p class="help">
                            <i18n-t keypath="message.admin.forms.test_email.email_will_be_send_to_x" tag="span">
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
                                    <FontAwesomeIcon :icon="['far', 'paper-plane']" />
                                </span>
                                <span>{{ $t('message.send') }}</span>
                            </button>   
                        </div>
                    </div>
                    <!-- healthcheck -->
                    <div class="field">
                        <label class="label" for="lnkHealthCheck" v-html="$t('message.admin.forms.health_endpoint.label')" />
                        <p class="help" v-html="$t('message.admin.forms.health_endpoint.help')" />
                    </div>
                    <div class="field mb-5">
                        <a id="lnkHealthCheck" target="_blank" :href="healthEndPoint">{{ healthEndPointFullPath }}</a>
                    </div>
                    <h4 class="title is-4 pt-5 has-text-grey-light">{{ $t('message.admin.storage') }}</h4>
                    <!-- store icons in database -->
                    <FormCheckbox v-model="appSettings.storeIconsInDatabase" @update:model-value="val => useAppSettingsUpdater('storeIconsInDatabase', val)" fieldName="storeIconsInDatabase" label="message.admin.forms.store_icon_to_database.label" help="message.admin.forms.store_icon_to_database.help" />
                    <h4 class="title is-4 pt-5 has-text-grey-light">{{ $t('message.settings.security') }}</h4>
                    <!-- protect db -->
                    <FormCheckbox v-model="appSettings.useEncryption" @update:model-value="val => useAppSettingsUpdater('useEncryption', val)" fieldName="useEncryption" label="message.admin.forms.use_encryption.label" help="message.admin.forms.use_encryption.help" />
                </form>

                <h4 class="title is-4 pt-5 has-text-grey-light">{{ $t('message.environment') }}</h4>
                <!-- cache management -->
                <div class="field">
                    <!-- <h5 class="title is-5">{{ $t('message.settings.security') }}</h5> -->
                    <label for="btnClearCache" class="label" v-html="$t('message.admin.forms.cache_management.label')" />
                    <p class="help" v-html="$t('message.admin.forms.cache_management.help')" />
                </div>
                <div class="field mb-5 is-grouped">
                    <p class="control">
                        <button id="btnClearCache" type="button" :class="isClearingCache ? 'is-loading' : ''" class="button is-link is-rounded is-small" @click="clearCache">
                            {{ $t('message.clear') }}
                        </button>
                    </p>
                </div>
                <!-- env vars -->
                <div class="field">
                    <label for="btnCopyEnvVars" class="label"  v-html="$t('message.admin.variables')" />
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