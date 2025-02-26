import appSettingService from '@/services/appSettingService'
import { defineStore } from 'pinia'
import { useNotifyStore } from '@/stores/notify'

export const useAppSettingsStore = defineStore({
    id: 'appSettings',

    state: () => {
        return { ...window.appSettings }
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
                useNotifyStore().alert({ text: trans('errors.failed_to_retrieve_app_settings') })
            })
        },
    },
})
