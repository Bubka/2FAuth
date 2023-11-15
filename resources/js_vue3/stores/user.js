import { defineStore } from 'pinia'
import authService from '@/services/authService'
import router from '@/router'
import { useColorMode } from '@vueuse/core'
import { useTwofaccounts } from '@/stores/twofaccounts'
import { useGroups } from '@/stores/groups'
import { useNotifyStore } from '@/stores/notify'

export const useUserStore = defineStore({
    id: 'user',

    state: () => {
        return {
            name: undefined,
            email: undefined,
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

            // async appLogout(evt) {
            if (this.$2fauth.config.proxyAuth) {
                if (this.$2fauth.config.proxyLogoutUrl) {
                    location.assign(this.$2fauth.config.proxyLogoutUrl)
                }
                else return false
            }
            else {
                return authService.logout().then(() => {
                    this.reset()
                    if (kicked) {
                        const notify = useNotifyStore()
                        notify.clear()
                        notify.warn({ text: trans('auth.autolock_triggered_punchline'), duration:-1 })
                    }
                })
            }
        },

        /**
         * Resets all user data
         */
        reset() {
            this.$reset()
            this.initDataStores()
            this.applyUserPrefs()
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
        }

    },
})
