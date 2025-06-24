<script setup>
    import tabs from './tabs'
    import userService from '@/services/userService'
    import { webauthnService } from '@/services/webauthn/webauthnService'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useUserStore } from '@/stores/user'
    import { useNotify, TabBar } from '@2fauth/ui'
    import { UseColorMode } from '@vueuse/components'
    import Spinner from '@/components/Spinner.vue'
    import { useI18n } from 'vue-i18n'
    import { useErrorHandler } from '@2fauth/stores'

    const errorHandler = useErrorHandler()
    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const appSettings = useAppSettingsStore()
    const notify = useNotify()
    const router = useRouter()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')

    const credentials = ref([])
    const isFetching = ref(false)

    const isDisabled = computed(() => {
        return (appSettings.enableSso && appSettings.useSsoOnly) || user.authenticated_by_proxy
    })
    
    onMounted(() => {
        fetchCredentials()
    })

    watch(() => user.preferences.useWebauthnOnly, () => {
        userService.updatePreference('useWebauthnOnly', user.preferences.useWebauthnOnly).then(response => {
            notify.success({ text: t('message.settings.forms.setting_saved') })
        })
    })

    /**
     * Register a new security device
     */
    function register() {
        if (isDisabled.value == true) {
            notify.warn({text: t('error.unsupported_with_reverseproxy') })
            return false
        }

        webauthnService.register().then((response) => {
            router.push({ name: 'settings.webauthn.editCredential', params: { credentialId: JSON.parse(response.config.data).id } })
        })
        .catch(error => {
            if ('webauthn' in error) {
                if (error.name == 'is-warning') {
                    notify.warn({ text: t(error.message) })
                }
                else notify.alert({ text: t(error.message) })
            }
            else if( error.response?.status === 422 ) {
                notify.alert({ text: error.response.data.message })
            }
            else {
                errorHandler.parse(error)
                router.push({ name: 'genericError' })
            }
        })
    }

    /**
     * revoke a credential
     */
    function revokeCredential(credentialId) {
        if(confirm(t('message.auth.confirm.revoke_device'))) {
            userService.revokeWebauthnDevice(credentialId).then(response => {
                // Remove the revoked credential from the collection
                credentials.value = credentials.value.filter(a => a.id !== credentialId)

                // Then we disable the useWebauthnOnly preference which is relevant
                // only when at least one device is registered
                if (credentials.value.length == 0) {
                    user.preferences.useWebauthnOnly = false
                }

                notify.success({ text: t('message.auth.webauthn.device_revoked') })
            });
        }
    }

    /**
     * Always display a printable name
     */
    function displayName(credential) {
        return credential.alias ? credential.alias : t('message.auth.webauthn.my_device') + ' (#' + credential.id.substring(0, 10) + ')'
    }

    /**
     * Get all credentials from backend
     */
    function fetchCredentials() {
        isFetching.value = true

        userService.getWebauthnDevices({returnError: true})
        .then(response => {
            credentials.value = response.data
        })
        .catch(error => {
            if( error.response.status === 403 ) {
                // The backend returns a 403 response if the user is authenticated by a reverse proxy
                // or if SSO only is enabled.
                // The form is already disabled (see isDisabled) so we do nothing more here
            }
            else {
                errorHandler.parse(error)
                router.push({ name: 'genericError' })
            }
        })
        .finally(() => {
            isFetching.value = false
        })
    }

    onBeforeRouteLeave((to) => {
        if (! to.name.startsWith('settings.')) {
            notify.clear()
        }
    })
</script>

<template>
    <div>
        <TabBar :tabs="tabs" :active-tab="'settings.webauthn.devices'" @tab-selected="(to) => router.push({ name: to })" />
        <div class="options-tabs">
            <FormWrapper>
                <div v-if="isDisabled && user.oauth_provider" class="notification is-warning has-text-centered">
                    {{ $t('message.auth.sso_only_x_settings_are_disabled', { auth_method: 'WebAuthn' }) }}
                </div>
                <div v-if="isDisabled && user.authenticated_by_proxy" class="notification is-warning has-text-centered">
                    {{ $t('message.auth.auth_handled_by_proxy') + '<br />' + $t('message.auth.manage_auth_at_proxy_level') }}
                </div>
                <h4 class="title is-4 has-text-grey-light">{{ $t('message.auth.webauthn.security_devices') }}</h4>
                <div class="is-size-7-mobile">
                    {{ $t('message.auth.webauthn.security_devices_legend')}}
                </div>
                <div class="mt-3">
                    <a tabindex="0" @click="register" @keyup.enter="register">
                        <FontAwesomeIcon :icon="['fas', 'plus-circle']" />&nbsp;{{ $t('message.auth.webauthn.register_a_new_device')}}
                    </a>
                </div>
                <!-- credentials list -->
                <div v-if="credentials.length > 0" class="field">
                    <div v-for="credential in credentials" :key="credential.id" class="group-item is-size-5 is-size-6-mobile">
                        {{ displayName(credential) }}
                        <!-- revoke link -->
                        <UseColorMode v-slot="{ mode }">
                            <button type="button" class="button tag is-pulled-right" :class="mode === 'dark' ? 'is-dark':'is-white'" @click="revokeCredential(credential.id)" :title="$t('message.settings.revoke')">
                                {{ $t('message.settings.revoke') }}
                            </button>
                        </UseColorMode>
                        <!-- edit link -->
                        <!-- <RouterLink :to="{ name: '' }" class="has-text-grey pl-1" :title="$t('message.rename')">
                            <FontAwesomeIcon :icon="['fas', 'pen-square']" />
                        </RouterLink> -->
                    </div>
                    <div class="mt-2 is-size-7 is-pulled-right">
                        {{ $t('message.auth.webauthn.revoking_a_device_is_permanent')}}
                    </div>
                </div>
                <Spinner :isVisible="isFetching && credentials.length === 0" />
                <h4 class="title is-4 pt-6 has-text-grey-light">{{ $t('message.auth.webauthn.options') }}</h4>
                <div class="field">
                    {{ $t('message.auth.webauthn.need_a_security_device_to_enable_options')}}
                </div>
                <form>
                    <!-- use webauthn only -->
                    <FormCheckbox
                        v-model="user.preferences.useWebauthnOnly"
                        fieldName="useWebauthnOnly"
                        label="message.auth.webauthn.use_webauthn_only.label"
                        help="message.auth.webauthn.use_webauthn_only.help"
                        :isDisabled="isDisabled || credentials.length === 0"
                    />
                </form>
                <!-- footer -->
                <VueFooter>
                    <template #default>
                        <NavigationButton action="close" @closed="router.push({ name: returnTo })" :current-page-title="$t('title.settings.webauthn.devices')" />
                    </template>
                </VueFooter>
            </FormWrapper>
        </div>
    </div>
</template>