import { defineStore } from 'pinia'

export const useBusStore = defineStore({
    id: 'bus',

    state: () => {
        return {
            migrationUri: null,
            decodedUri: null,
            goBackTo: null,
            returnTo: null,
            inManagementMode: false,
        }
    },

    actions: {
        
    },
})
