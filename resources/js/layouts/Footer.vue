<script setup>
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useUserStore } from '@/stores/user'

    const appSettings = useAppSettingsStore()
    const user = useUserStore()
    const $2fauth = inject('2fauth')

    const props = defineProps({
        showButtons: true,
        internalFooterType: {
            type: String,
            default: 'navLinks'
        }
    })

    function logout() {
        if(confirm(trans('auth.confirm.logout'))) {
            user.logout()
        }
    }
</script>

<template>
    <footer class="main">
        <!-- action buttons -->
        <div class="columns is-gapless" v-if="showButtons">
            <div class="column has-text-centered">
                <div class="field is-grouped">
                    <slot></slot>
                </div>
            </div>
        </div>
        <!-- sub-links -->
        <div v-if="internalFooterType == 'doneButton'" class="content has-text-centered">
            <button id="lnkExitEdit" class="button is-ghost is-like-text" @click.stop="$emit('doneButtonClicked', true)">{{ $t('commons.done') }}</button>
        </div>
        <div v-else-if="internalFooterType == 'modal'" class="content has-text-centered">
            <router-link v-if="$route.name != 'accounts'" id="lnkBackToHome" :to="{ name: 'accounts' }" class="has-text-grey">{{ $t('commons.back_to_home') }}</router-link>
            <span v-else>&nbsp;</span>
        </div>
        <div v-else class="content has-text-centered">
            <div v-if="$route.meta.showAbout === true" class="is-size-6">
                <router-link id="lnkAbout" :to="{ name: 'about' }" class="has-text-grey">
                    2FAuth – <span class="has-text-weight-bold">v{{ $2fauth.version }}</span>
                </router-link>
            </div>
            <div v-else>
                <router-link id="lnkSettings" :to="{ name: 'settings.options' }" class="has-text-grey">
                    {{ $t('settings.settings') }}
                </router-link>
                <span v-if="user.isAdmin"> -
                    <router-link id="lnkAdmin" :to="{ name: 'admin.appSetup' }" class="has-text-grey">
                        {{ $t('admin.admin') }}<span v-if="appSettings.latestRelease && appSettings.checkForUpdate" class="release-flag"></span>
                    </router-link>
                </span>
                <span v-if="!$2fauth.config.proxyAuth || ($2fauth.config.proxyAuth && $2fauth.config.proxyLogoutUrl)">
                    - <button id="lnkSignOut" class="button is-text is-like-text has-text-grey" @click="logout">{{ $t('auth.sign_out') }}</button>
                </span>
            </div>
        </div>
    </footer>
</template>
