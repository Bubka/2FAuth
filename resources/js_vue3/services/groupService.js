import { httpClientFactory } from '@/services/httpClientFactory'

const apiClient = httpClientFactory('api')

export default {
    /**
     * 
     * @returns 
     */
    getAll() {
        return apiClient.get('groups')
    },

    assign(accountsIds, groupId, config = {}) {
        return apiClient.post('/groups/' + groupId + '/assign', {ids: accountsIds})
    }
    
}