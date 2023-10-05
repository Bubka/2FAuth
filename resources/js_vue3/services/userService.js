import { httpClientFactory } from '@/services/httpClientFactory'

const apiClient = httpClientFactory('api')
const webClient = httpClientFactory('web')

export default {
    /**
     * Update a user preference
     * 
     * @param {string} name 
     * @param {any} value 
     * @returns promise
     */
    updatePreference(name, value) {
        return apiClient.put('/user/preferences/' + name, { value: value })
    },
    
}