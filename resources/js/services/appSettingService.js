import { httpClientFactory } from '@/services/httpClientFactory'

const apiClient = httpClientFactory('api')

export default {
    /**
     * 
     * @returns 
     */
    get(config = {}) {
        return apiClient.get('/settings', { ...config })
    },

    /**
     * 
     * @returns 
     */
    update(name, value) {
        return apiClient.put('/settings/' + name, { value: value })
    },
    
    /**
     * 
     * @returns 
     */
    delete(name, config = {}) {
        return apiClient.delete('/settings/' + name, { ...config })
    },
}