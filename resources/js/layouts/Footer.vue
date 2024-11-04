<script setup>
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useUserStore } from '@/stores/user'
    import { UseColorMode } from '@vueuse/components'

    const appSettings = useAppSettingsStore()
    const user = useUserStore()
    const $2fauth = inject('2fauth')
    const showMenu = ref(false)

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
    <footer class="main" :class="{ 'menu' : showMenu }">
        <!-- action buttons -->
        <div class="columns is-gapless" v-if="showButtons && ! showMenu">
            <div class="column has-text-centered">
                <div class="field is-grouped">
                    <slot></slot>
                </div>
            </div>
        </div>
        <!-- sub-links -->
        <div v-if="internalFooterType == 'doneButton'" class="content has-text-centered">
            <!-- done link -->
            <button id="lnkExitEdit" class="button is-ghost is-like-text" @click.stop="$emit('doneButtonClicked', true)">{{ $t('commons.done') }}</button>
        </div>
        <div v-else-if="internalFooterType == 'modal'" class="content has-text-centered">
            <!-- back to home link -->
            <router-link v-if="$route.name != 'accounts'" id="lnkBackToHome" :to="{ name: 'accounts' }" class="has-text-grey">{{ $t('commons.back_to_home') }}</router-link>
            <span v-else>&nbsp;</span>
        </div>
        <div v-else class="content has-text-centered">
            <div v-if="$route.meta.showAbout === true">
                <!-- about link -->
                <router-link id="lnkAbout" :to="{ name: 'about' }" class="has-text-grey">
                    2FAuth – <span class="has-text-weight-bold">v{{ $2fauth.version }}</span>
                </router-link>
            </div>
            <!-- email + menu links -->
            <div v-else-if="user.preferences.showEmailInFooter">
                <ul v-if="showMenu == true" class="ml-0 mt-1">
                    <!-- settings link -->
                    <li class="column">
                        <router-link id="lnkSettings" :to="{ name: 'settings.options' }">
                            {{ $t('settings.settings') }}
                        </router-link>
                    </li>
                    <!-- admin link -->
                    <li v-if="user.isAdmin" class="column">
                        <router-link id="lnkAdmin" :to="{ name: 'admin.appSetup' }" >
                            {{ $t('admin.admin_panel') }}<span v-if="appSettings.latestRelease && appSettings.checkForUpdate" class="release-flag"></span>
                        </router-link>
                    </li>
                    <!-- sign-out button -->
                    <li v-if="!$2fauth.config.proxyAuth || ($2fauth.config.proxyAuth && $2fauth.config.proxyLogoutUrl)" class="column">
                        <UseColorMode v-slot="{ mode }">
                            <button id="lnkSignOut" class="button is-text is-like-text" :class="mode == 'dark' ? 'has-text-grey-lighter' : 'has-text-grey-darker'" @click="logout">
                                {{ $t('auth.sign_out') }}
                            </button>
                        </UseColorMode>
                    </li>
                </ul>
                <!-- email link -->
                <button id="btnEmailMenu" @click="showMenu = !showMenu" class="button is-text is-like-text has-text-grey" style="width: 100%;">
                    <span class="mx-2 has-ellipsis">{{ user.email }}</span>
                    <FontAwesomeIcon v-if="!showMenu" :icon="['fas', 'bars']" class="mr-2" />
                    <!-- <button v-else class="delete ml-3"></button> -->
                    <FontAwesomeIcon v-else :icon="['fas', 'times']" class="mr-2" />
                </button>
            </div>
            <!-- legacy links -->
            <div v-else>
                <!-- settings link -->
                <router-link id="lnkSettings" :to="{ name: 'settings.options' }" class="has-text-grey">
                    {{ $t('settings.settings') }}
                </router-link>
                <!-- admin link -->
                <span v-if="user.isAdmin"> -
                    <router-link id="lnkAdmin" :to="{ name: 'admin.appSetup' }" class="has-text-grey">
                        {{ $t('admin.admin') }}<span v-if="appSettings.latestRelease && appSettings.checkForUpdate" class="release-flag"></span>
                    </router-link>
                </span>
                <!-- sign-out button -->
                <span v-if="!$2fauth.config.proxyAuth || ($2fauth.config.proxyAuth && $2fauth.config.proxyLogoutUrl)">
                    - <button id="lnkSignOut" class="button is-text is-like-text has-text-grey" @click="logout">{{ $t('auth.sign_out') }}</button>
                </span>
            </div>
        </div>
    </footer>
</template>
