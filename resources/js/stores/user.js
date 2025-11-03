import { defineStore } from 'pinia'
import authService from '@/services/authService'
import userService from '@/services/userService'
import router from '@/router'
import { useColorMode } from '@vueuse/core'
import { useTwofaccounts } from '@/stores/twofaccounts'
import { useGroups } from '@/stores/groups'
import { useNotify } from '@2fauth/ui'
import { useAppSettingsStore } from '@/stores/appSettings'
import { useErrorHandler } from '@2fauth/stores'

export const useUserStore = defineStore('user', {
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
            const notify = useNotify()
            const errorHandler = useErrorHandler()

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
                        notify.warn({ text: this.$i18n.global.t('notification.autolock_triggered_punchline'), duration:-1 })
                    }
                    this.tossOut()
                })
                .catch(error => {
                    // The logout request will receive a 401 response when the
                    // backend has already detect inactivity on its side. In this case we
                    // don't want any error to be displayed.
                    if (error.response.status !== 401) {
                        errorHandler.show(error)
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

            // We don't want sso settings to be reset in order to activate the
            // appropriate login form once pushed to login view
            const enableSso = useAppSettingsStore().enableSso
            const useSsoOnly = useAppSettingsStore().useSsoOnly

            useAppSettingsStore().$reset()

            useAppSettingsStore().enableSso = enableSso
            useAppSettingsStore().useSsoOnly = useSsoOnly

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
            let lang = this.$i18n.fallbackLocale

            if (isSupported) {
                // The language tag pushed by the browser may be composed of
                // multiple subtags (ex: fr-FR) so we keep only the
                // "language subtag" (ex: fr)
                lang = this.preferences.lang == 'browser' ? language.value.slice(0, 2)  : this.preferences.lang
            }

            this.$i18n.global.locale = lang
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
                const notify = useNotify()
                notify.alert({ text: this.$i18n.global.t('error.data_cannot_be_refreshed_from_server') })
            })
        }

    },
})
