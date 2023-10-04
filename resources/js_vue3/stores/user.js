import { defineStore } from 'pinia'
import authService from '@/services/authService'
import router from '@/router'
import { useColorMode } from '@vueuse/core'

export const useUserStore = defineStore({
    id: 'user',

    state: () => {
        return {
            name: undefined,
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

        logout() {
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
                })

                // this.$router.push({ name: 'login', params: { forceRefresh: true } })
            }
            // },
        },

        reset() {
            localStorage.clear()
            this.$reset()
            this.applyUserPrefs()
            router.push({ name: 'login' })
        },

        applyTheme() {
            const mode = useColorMode({
                attribute: 'data-theme',
            })
            mode.value = this.preferences.theme == 'system' ? 'auto' : this.preferences.theme
        },

        applyLanguage() {
            const { isSupported, language } = useNavigatorLanguage()

            if (isSupported) {
                loadLanguageAsync(this.preferences.lang == 'browser' ? language.value.slice(0, 2)  : this.preferences.lang)
            }
            else loadLanguageAsync('en')
        },

        applyUserPrefs() {
            this.applyTheme()
            this.applyLanguage()
        }

    },
})
