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

    get(id, config = {}) {
        return apiClient.get('/groups/' + id, { ...config })
    },

    assign(accountsIds, groupId, config = {}) {
        return apiClient.post('/groups/' + groupId + '/assign', { ids: accountsIds }, { ...config })
    },

    delete(id, config = {}) {
        return apiClient.delete('/groups/' + id, { ...config })
    },

    saveOrder(orderedIds, config = {}) {
        return apiClient.post('/groups/reorder', { orderedIds: orderedIds }, { ...config })
    },
    
}