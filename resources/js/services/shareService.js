import { httpClientFactory } from '@/services/httpClientFactory'

const apiClient = httpClientFactory('api')

export default {

    getShares(twofaccountId, config = {}) {
        return apiClient.get('/twofaccounts/' + twofaccountId + '/shares', { ...config })
    },

    shareWithUsers(twofaccountId, userIds, config = {}) {
        return apiClient.post('/twofaccounts/' + twofaccountId + '/shares', { user_ids: userIds }, { ...config })
    },

    unshareWithUser(twofaccountId, userId, config = {}) {
        return apiClient.delete('/twofaccounts/' + twofaccountId + '/shares/' + userId, { ...config })
    },

    unshare(twofaccountId, config = {}) {
        return apiClient.delete('/twofaccounts/' + twofaccountId + '/shares', { ...config })
    },

    getRecipients(twofaccountId, names = '', config = {}) {
        return apiClient.get('/twofaccounts/' + twofaccountId + '/recipients' + (names ? '?filter[name]=' + names : ''), { ...config })
    },

    shareWithAll(twofaccountId, config = {}) {
        return apiClient.post('/twofaccounts/' + twofaccountId + '/shares/all', { ...config })
    },
}