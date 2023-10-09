import { defineStore } from 'pinia'

export const useDataStore = defineStore({
    id: 'data',

    state: () => {
        return {
            twofaccounts: [],
            groups: [],
        }
    },

    actions: {
        
    },
})
