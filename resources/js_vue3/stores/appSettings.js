import { defineStore } from 'pinia'

export const useAppSettingsStore = defineStore({
    id: 'appSettings',

    state: () => {
        return { ...window.appSettings }
    },

    actions: {
        
    },
})
