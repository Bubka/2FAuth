import appSettingService from '@/services/appSettingService'
import { defineStore } from 'pinia'
import { useNotifyStore } from '@/stores/notify'

export const useAppSettingsStore = defineStore({
    id: 'appSettings',

    state: () => {
        return { ...window.appSettings }
    },

    actions: {

        /**
         * Fetches the appSetting collection from the backend
         */
        async fetch() {
            appSettingService.getAll({ returnError: true })
            .then(response => {
                response.data.forEach(setting => {
                    this[setting.key] = setting.value
                })
            })
            .catch(error => {
                useNotifyStore().alert({ text: trans('errors.data_cannot_be_refreshed_from_server') })
            })
        },
    },
})
