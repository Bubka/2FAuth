<template>
    <footer>
        <div class="columns is-gapless" v-if="showButtons">
            <div class="column has-text-centered">
                <div class="field is-grouped">
                    <slot></slot>
                </div>
            </div>
        </div>
        <div v-if="editMode" class="content has-text-centered">
            <button id="lnkExitEdit" class="button is-ghost is-like-text" @click="$emit('exit-edit')">{{ $t('commons.done') }}</button>
        </div>
        <div v-else class="content has-text-centered">
            <div v-if="$route.meta.showAbout === true" class="is-size-6">
                <router-link id="lnkAbout" :to="{ name: 'about' }" class="has-text-grey">
                    2FAuth – <span class="has-text-weight-bold">v{{ $2fauth.version }}</span>
                </router-link>
            </div>
            <div v-else>
                <router-link id="lnkSettings"  :to="{ name: 'settings.options' }" class="has-text-grey">
                    {{ $t('settings.settings') }}<span v-if="appSettings.latestRelease && appSettings.checkForUpdate" class="release-flag"></span>
                </router-link>
                <span v-if="!$2fauth.config.proxyAuth || ($2fauth.config.proxyAuth && $2fauth.config.proxyLogoutUrl)">
                    - <button id="lnkSignOut" class="button is-text is-like-text has-text-grey" @click="logout">{{ $t('auth.sign_out') }}</button>
                </span>
            </div>
        </div>
    </footer>
</template>


<script setup>
    import { useAppSettingsStore } from '@/stores/appSettings'
    const appSettings = useAppSettingsStore()

    const props = defineProps({
        showButtons: true,
        editMode: false,
    })

    function logout() {
        if(confirm(this.$t('auth.confirm.logout'))) {
            this.appLogout()
        }
    }
</script>
