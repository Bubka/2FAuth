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
    updatePreference(name, value, config = {}) {
        return apiClient.put('/user/preferences/' + name, { value: value }, { ...config })
    },

    /**
     * Get all webauthn devices
     * 
     * @param {string} name 
     * @param {any} value 
     * @returns promise
     */
    getWebauthnDevices(config = {}) {
        return webClient.get('/webauthn/credentials', { ...config })
    },

    /**
     * Revoke a webauthn device
     * 
     * @param {string} name 
     * @param {any} value 
     * @returns promise
     */
    revokeWebauthnDevice(credentialId, config = {}) {
        return webClient.delete('/webauthn/credentials/' + credentialId, { ...config })
    },

    /**
     * Get all user PATs
     * 
     * @param {*} config 
     * @returns 
     */
    getPersonalAccessTokens(config = {}) {
        return webClient.get('/oauth/personal-access-tokens', { ...config })
    },

    /**
     * Delete a user PAT
     * 
     * @param {*} tokenId 
     * @returns 
     */
    deletePersonalAccessToken(tokenId, config = {}) {
        return webClient.delete('/oauth/personal-access-tokens/' + tokenId, { ...config })
    }
    
}