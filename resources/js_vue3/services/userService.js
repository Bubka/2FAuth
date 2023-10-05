import { httpClientFactory } from '@/services/httpClientFactory'

const apiClient = httpClientFactory('api')
const webClient = httpClientFactory('web')

export default {
    /**
     * Update a user preference
     * 
     * @param {string} name 
     * @param {any} value 
     * @returns promise
     */
    updatePreference(name, value) {
        return apiClient.put('/user/preferences/' + name, { value: value })
    },

    /**
     * Get all webauthn devices
     * 
     * @param {string} name 
     * @param {any} value 
     * @returns promise
     */
    getWebauthnDevices(config = {}) {
        return webClient.get('/webauthn/credentials', {...config})
    },

    /**
     * Revoke a webauthn device
     * 
     * @param {string} name 
     * @param {any} value 
     * @returns promise
     */
    revokeWebauthnDevice(credentialId, config = {}) {
        return webClient.delete('/webauthn/credentials/' + credentialId, {...config})
    },
    
}