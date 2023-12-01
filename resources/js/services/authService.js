import { httpClientFactory } from '@/services/httpClientFactory'

const webClient = httpClientFactory('web')
const apiClient = httpClientFactory('api')

export default {
    /**
     * 
     */
    logout(config = {}) {
        return webClient.get('/user/logout', { ...config })
    },

    /**
     * 
     */
    async getCurrentUser(config = {}) {
        return apiClient.get('/user', { ...config })
    },
    
}