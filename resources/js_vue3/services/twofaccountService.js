import { httpClientFactory } from '@/services/httpClientFactory'

const apiClient = httpClientFactory('api')

export default {
    getAll(withOtp = false) {
        return apiClient.get('/twofaccounts' + (withOtp ? '?withOtp=1' : ''))
    },

    getByIds(ids, withOtp = false) {
        return apiClient.get('/twofaccounts?ids=' + ids + (withOtp ? '&withOtp=1' : ''))
    },

    get(id, config = {}) {
        return apiClient.get('/twofaccounts/' + id, { ...config })
    },

    preview(uri, config = {}) {
        return apiClient.post('/twofaccounts/preview', { uri: uri }, { ...config })
    },

    getLogo(service, config = {}) {
        return apiClient.post('/icons/default', { service: service }, { ...config })
    },

    deleteIcon(icon, config = {}) {
        return apiClient.delete('/icons/' + icon, { ...config })
    },

    getOtpById(id, config = {}) {
        return apiClient.get('/twofaccounts/' + id + '/otp', { ...config }) 
    },

    getOtpByUri(uri, config = {}) {
        return apiClient.post('/twofaccounts/otp', { uri: uri }, { ...config }) 
    },

    getOtpByParams(params, config = {}) {
        return apiClient.post('/twofaccounts/otp', params, { ...config }) 
    },

    withdraw(ids, config = {}) {
        return apiClient.patch('/twofaccounts/withdraw?ids=' + ids.join(), { ...config })
    },

    saveOrder(orderedIds, config = {}) {
        return apiClient.post('/twofaccounts/reorder', { orderedIds: orderedIds }, { ...config })
    },

    batchDelete(ids, config = {}) {
        return apiClient.delete('/twofaccounts?ids=' + ids, { ...config })
    },

    export(ids, config = {}) {
        return apiClient.delete('/twofaccounts/export?ids=' + ids, { ...config })
    },

    getQrcode(id, config = {}) {
        return apiClient.get('/twofaccounts/' + id + '/qrcode', { ...config })
    },

    migrate(payload, config = {}) {
        return apiClient.post('/twofaccounts/migration', { payload: payload, withSecret: true }, { ...config })
    },

    count(config = {}) {
        return apiClient.get('/twofaccounts/count', { ...config })
    },
    
}