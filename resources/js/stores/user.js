import { defineStore } from 'pinia'
import authService from '@/services/authService'
import userService from '@/services/userService'
import router from '@/router'
import { useColorMode } from '@vueuse/core'
import { useTwofaccounts } from '@/stores/twofaccounts'
import { useGroups } from '@/stores/groups'
import { useNotifyStore } from '@/stores/notify'
import { useAppSettingsStore } from '@/stores/appSettings'

export const useUserStore = defineStore({
    id: 'user',

    state: () => {
        return {
            id: undefined,
            name: undefined,
            email: undefined,
            oauth_provider: undefined,
            authenticated_by_proxy: undefined,
            preferences: window.defaultPreferences,
            isAdmin: false,
        }
    },

    getters: {
        isAuthenticated() {
            return this.name != undefined
        }
    },

    actions: {
        /**
         * Initializes the store for the given user
         * 
         * @param {object} user 
         */
        async loginAs(user) {
            this.$patch(user)
            await this.initDataStores()
            this.applyUserPrefs()
        },

        /**
         * Initializes the user's data stores
         */
        async initDataStores() {
            const accounts = useTwofaccounts()
            const groups = useGroups()

            if (this.isAuthenticated) {
                await accounts.fetch()
                groups.fetch()
            }
            else {
                accounts.$reset()
                groups.$reset()
            }
        },

        /**
         * Logs the user out or moves to proxy logout url
         */
        logout(options = {}) {
            const { kicked } = options
            const notify = useNotifyStore()

            // async appLogout(evt) {
            if (this.$2fauth.config.proxyAuth) {
                if (this.$2fauth.config.proxyLogoutUrl) {
                    location.assign(this.$2fauth.config.proxyLogoutUrl)
                }
                else return false
            }
            else {
                authService.logout({ returnError: true }).then(() => {
                    if (kicked) {
                        notify.clear()
                        notify.warn({ text: trans('auth.autolock_triggered_punchline'), duration:-1 })
                    }
                    this.tossOut()
                })
                .catch(error => {
                    // The logout request will receive a 401 response when the
                    // backend has already detect inactivity on its side. In this case we
                    // don't want any error to be displayed.
                    if (error.response.status !== 401) {
                        notify.error(error)
                    }
                    else this.tossOut()
                })
            }
        },

        /**
         * Resets all user data and push out
         */
        tossOut() {
            this.$reset()
            this.initDataStores()
            this.applyUserPrefs()
            useAppSettingsStore().$reset()
            router.push({ name: 'login' })
        },

        /**
         * Applies user theme
         */
        applyTheme() {
            const mode = useColorMode({
                attribute: 'data-theme',
            })
            mode.value = this.preferences.theme == 'system' ? 'auto' : this.preferences.theme
        },

        /**
         * Applies user language
         */
        applyLanguage() {
            const { isSupported, language } = useNavigatorLanguage()

            if (isSupported) {
                loadLanguageAsync(this.preferences.lang == 'browser' ? language.value.slice(0, 2)  : this.preferences.lang)
            }
            else loadLanguageAsync('en')
        },

        /**
         * Applies both user theme & language
         */
        applyUserPrefs() {
            this.applyTheme()
            this.applyLanguage()
        },

        /**
         * Refresh user preferences with backend state
         */
        refreshPreferences() {
            userService.getPreferences({returnError: true})
            .then(response => {
                response.data.forEach(preference => {
                    this.preferences[preference.key] = preference.value
                })
            })
            .catch(error => {
                const notify = useNotifyStore()
                notify.alert({ text: trans('errors.data_cannot_be_refreshed_from_server') })
            })
        }

    },
})
