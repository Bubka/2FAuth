import appSettingService from '@/services/appSettingService'
import { defineStore } from 'pinia'
import { useNotify } from '@2fauth/ui'

export const useAppSettingsStore = defineStore('appSettings', {
    state: () => {
        return {
            lockedPreferences: window.lockedPreferences,
            ...window.appSettings
        }
    },

    getters: {
        // Tells if all properties have been fetched from the backend.
        // Here we test useEncryption but we could have test any other property
        // appart from the ones pushed by Laravel in the html template.
        isSynced: (state) => state.useEncryption != null,
      },
    
    actions: {
        /**
         * Fetches the appSetting collection from the backend
         */
        async fetch() {
            appSettingService.getAll({ returnError: true }).then(response => {
                response.data.forEach(setting => {
                    this[setting.key] = setting.value
                })
            })
            .catch(error => {
                useNotify().alert({ text: this.$i18n.global.t('error.failed_to_retrieve_app_settings') })
            })
        },
    },
})
