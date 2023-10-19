import { httpClientFactory } from '@/services/httpClientFactory'

const apiClient = httpClientFactory('api')

export default {
    /**
     * 
     * @returns 
     */
    getAll() {
        return apiClient.get('/twofaccounts')
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
    
}