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
    async getCurrentUser() {
        try {
            const { data } = await apiClient.get('/user')
            return data
        } catch (error) {
            return null
        }
    },
    
}