import { httpClientFactory } from '@/services/httpClientFactory'

const webClient = httpClientFactory('web')
const apiClient = httpClientFactory('api')

export default {
    /**
     * 
     */
    logout() {
        return webClient.get('/user/logout')
    },

    /**
     * 
     */
    async getCurrentUser(config = {}) {
        return apiClient.get('/user', { ...config })
    },
    
}