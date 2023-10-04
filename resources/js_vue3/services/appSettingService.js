import { httpClientFactory } from '@/services/httpClientFactory'

const apiClient = httpClientFactory('api')

export default {
    /**
     * 
     * @returns 
     */
    update(name, value) {
        return apiClient.put('/settings/' + name, { value: value })
    },
    
}