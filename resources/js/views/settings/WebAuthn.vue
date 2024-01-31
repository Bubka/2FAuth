<script setup>
    import SettingTabs from '@/layouts/SettingTabs.vue'
    import userService from '@/services/userService'
    import { webauthnService } from '@/services/webauthn/webauthnService'
    import { useUserStore } from '@/stores/user'
    import { useNotifyStore } from '@/stores/notify'
    import { UseColorMode } from '@vueuse/components'
    import Spinner from '@/components/Spinner.vue'

    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const notify = useNotifyStore()
    const router = useRouter()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')

    const credentials = ref([])
    const isFetching = ref(false)
    const isRemoteUser = ref(false)
    
    onMounted(() => {
        fetchCredentials()
    })

    watch(() => user.preferences.useWebauthnOnly, () => {
        userService.updatePreference('useWebauthnOnly', user.preferences.useWebauthnOnly).then(response => {
            notify.success({ text: trans('settings.forms.setting_saved') })
        })
    })

    /**
     * Register a new security device
     */
    function register() {

        if (isRemoteUser == true) {
            notify.warn({text: trans('errors.unsupported_with_reverseproxy') })
            return false
        }

        webauthnService.register().then((response) => {
            router.push({ name: 'settings.webauthn.editCredential', params: { credentialId: JSON.parse(response.config.data).id } })
        })
        .catch(error => {
            if ('webauthn' in error) {
                if (error.name == 'is-warning') {
                    notify.warn({ text: trans(error.message) })
                }
                else notify.alert({ text: trans(error.message) })
            }
            else if( error.response?.status === 422 ) {
                notify.alert({ text: error.response.data.message })
            }
            else {
                notify.error(error);
            }
        })
    }

    /**
     * revoke a credential
     */
    function revokeCredential(credentialId) {
        if(confirm(trans('auth.confirm.revoke_device'))) {
            userService.revokeWebauthnDevice(credentialId).then(response => {
                // Remove the revoked credential from the collection
                credentials.value = credentials.value.filter(a => a.id !== credentialId)

                // Then we disable the useWebauthnOnly preference which is relevant
                // only when at least one device is registered
                if (credentials.value.length == 0) {
                    user.preferences.useWebauthnOnly = false
                }

                notify.success({ text: trans('auth.webauthn.device_revoked') })
            });
        }
    }

    /**
     * Always display a printable name
     */
    function displayName(credential) {
        return credential.alias ? credential.alias : trans('auth.webauthn.my_device') + ' (#' + credential.id.substring(0, 10) + ')'
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
            if( error.response.status === 405 ) {
                // The backend returns a 405 response for routes with the
                // rejectIfReverseProxy middleware
                isRemoteUser.value = true
            }
            else {
                notify.error(error)
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
        <SettingTabs :activeTab="'settings.webauthn.devices'" />
        <div class="options-tabs">
            <FormWrapper>
                <div v-if="isRemoteUser" class="notification is-warning has-text-centered" v-html="$t('auth.auth_handled_by_proxy')" />
                <h4 class="title is-4 has-text-grey-light">{{ $t('auth.webauthn.security_devices') }}</h4>
                <div class="is-size-7-mobile">
                    {{ $t('auth.webauthn.security_devices_legend')}}
                </div>
                <div class="mt-3">
                    <a tabindex="0" @click="register" @keyup.enter="register">
                        <FontAwesomeIcon :icon="['fas', 'plus-circle']" />&nbsp;{{ $t('auth.webauthn.register_a_new_device')}}
                    </a>
                </div>
                <!-- credentials list -->
                <div v-if="credentials.length > 0" class="field">
                    <div v-for="credential in credentials" :key="credential.id" class="group-item is-size-5 is-size-6-mobile">
                        {{ displayName(credential) }}
                        <!-- revoke link -->
                        <UseColorMode v-slot="{ mode }">
                            <button class="button tag is-pulled-right" :class="mode === 'dark' ? 'is-dark':'is-white'" @click="revokeCredential(credential.id)" :title="$t('settings.revoke')">
                                {{ $t('settings.revoke') }}
                            </button>
                        </UseColorMode>
                        <!-- edit link -->
                        <!-- <RouterLink :to="{ name: '' }" class="has-text-grey pl-1" :title="$t('commons.rename')">
                            <FontAwesomeIcon :icon="['fas', 'pen-square']" />
                        </RouterLink> -->
                    </div>
                    <div class="mt-2 is-size-7 is-pulled-right">
                        {{ $t('auth.webauthn.revoking_a_device_is_permanent')}}
                    </div>
                </div>
                <Spinner :isVisible="isFetching && credentials.length === 0" />
                <h4 class="title is-4 pt-6 has-text-grey-light">{{ $t('auth.webauthn.options') }}</h4>
                <div class="field">
                    {{ $t('auth.webauthn.need_a_security_device_to_enable_options')}}
                </div>
                <form>
                    <!-- use webauthn only -->
                    <FormCheckbox
                        v-model="user.preferences.useWebauthnOnly"
                        fieldName="useWebauthnOnly"
                        label="auth.webauthn.use_webauthn_only.label"
                        help="auth.webauthn.use_webauthn_only.help"
                        :disabled="isRemoteUser || credentials.length === 0"
                    />
                </form>
                <!-- footer -->
                <VueFooter :showButtons="true">
                    <ButtonBackCloseCancel :returnTo="{ name: returnTo }" action="close" />
                </VueFooter>
            </FormWrapper>
        </div>
    </div>
</template>