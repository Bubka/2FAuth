import { defineStore } from 'pinia'
// import { useApi } from '@/api/useAPI.js'

// const api = useApi()

export const useUserStore = defineStore({
	id: 'user',

	state: () => {
        return {
            name: 'guest',
            preferences: window.userPreferences,
            isAdmin: false,
        }
    },

	actions: {
		updatePreference(preference) {
			this.preferences = { ...this.state.preferences, ...preference }
		},
	},
})
