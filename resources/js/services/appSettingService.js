import { httpClientFactory } from '@/services/httpClientFactory'

const apiClient = httpClientFactory('api')

export default {
    /**
     * 
     * @returns 
     */
    getAll(config = {}) {
        return apiClient.get('/settings', { ...config })
    },

    /**
     * 
     * @returns 
     */
    update(name, value, config = {}) {
        return apiClient.put('/settings/' + name, { value: value }, { ...config })
    },
    
    /**
     * 
     * @returns 
     */
    delete(name, config = {}) {
        return apiClient.delete('/settings/' + name, { ...config })
    },
}