import { defineStore } from 'pinia'
// import { useApi } from '@/api/useAPI.js'

// const api = useApi()

export const useAppSettingsStore = defineStore({
	id: 'settings',

	state: () => {
        return { ...window.appSettings }
    },

	actions: {
		updateSetting(setting) {
			this.settings = { ...this.state.settings, ...setting }
		},
	},
})
