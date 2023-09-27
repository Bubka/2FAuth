import { defineStore } from 'pinia'
import authService from '@/services/authService'
import router from '@/router'

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
        updatePreference(preference) {
            this.preferences = { ...this.state.preferences, ...preference }
        },

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
                    localStorage.clear()
                    this.$reset()
                    router.push({ name: 'login' })
                })

                // this.$router.push({ name: 'login', params: { forceRefresh: true } })
            }
            // },
        }

    },
})
