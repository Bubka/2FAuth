import { httpClientFactory } from '@/services/httpClientFactory'

const apiClient = httpClientFactory('api')

export default {
    getAll(withOtp = false, config = {}) {
        return apiClient.get('/twofaccounts' + (withOtp ? '?withOtp=1' : ''), { ...config })
    },

    getByIds(ids, withOtp = false, config = {}) {
        return apiClient.get('/twofaccounts?ids=' + ids + (withOtp ? '&withOtp=1' : ''), { ...config })
    },

    get(id, config = {}) {
        return apiClient.get('/twofaccounts/' + id, { ...config })
    },

    preview(uri, config = {}) {
        return apiClient.post('/twofaccounts/preview', { uri: uri }, { ...config })
    },

    storeFromUri(uri, config = {}) {
        return apiClient.post('/twofaccounts', { uri: uri }, { ...config })
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

    export(ids, otpauthFormat, config = {}) {
        return apiClient.get('/twofaccounts/export?ids=' + ids + (otpauthFormat ? '&otpauth=1' : ''), { ...config })
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