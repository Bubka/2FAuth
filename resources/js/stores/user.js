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
                class: 'dark',
            })
            mode.value = this.preferences.theme == 'system' ? 'auto' : this.preferences.theme
        },

        /**
         * Applies user language
         */
        applyLanguage() {
            const { isSupported, language } = useNavigatorLanguage()
            let lang = 'en'

            if (isSupported) {
                if (this.preferences.lang == 'browser') {
                    if (this.$2fauth.langs.includes(language.value)) {
                        lang = language.value
                    }
                    // If the language tag pushed by the browser is composed of
                    // multiple subtags (ex: fr-FR) we need to retry but only with
                    // the "language subtag" (ex: fr)
                    else if (this.$2fauth.langs.includes(language.value.slice(0, 2))) {
                        lang = language.value.slice(0, 2)
                    }
                }
                else lang = this.preferences.lang
            }

            loadLanguageAsync(lang)
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
            const appSettings = useAppSettingsStore()

            userService.getPreferences({returnError: true})
            .then(response => {
                response.data.forEach(preference => {
                    this.preferences[preference.key] = preference.value
                    let index = appSettings.lockedPreferences.indexOf(preference.key)

                    if (preference.locked == true && index === -1) {
                        appSettings.lockedPreferences.push(preference.key)
                    }
                    else if (preference.locked == false && index > 0) {
                        appSettings.lockedPreferences.splice(index, 1)
                    }
                })
            })
            .catch(error => {
                const notify = useNotifyStore()
                notify.alert({ text: trans('errors.data_cannot_be_refreshed_from_server') })
            })
        }

    },
})
