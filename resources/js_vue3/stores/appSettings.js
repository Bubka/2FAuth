import { defineStore } from 'pinia'
import appSettingService from '@/services/appSettingService'

export const useAppSettingsStore = defineStore({
    id: 'appSettings',

    state: () => {
        return { ...window.appSettings }
    },

    actions: {
        
    },
})
