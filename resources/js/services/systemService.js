import { httpClientFactory } from '@/services/httpClientFactory'

const webClient = httpClientFactory('web')

export default {
    /**
     * 
     * @returns Promise
     */
    getSystemInfos(config = {}) {
        return webClient.get('system/infos', { ...config })
    },

    /**
     * 
     * @returns Promise
     */
    getLastRelease(config = {}) {
        return webClient.get('system/latestRelease', { ...config })
    },

    /**
     * 
     * @returns Promise
     */
    sendTestEmail(config = {}) {
        return webClient.post('system/test-email', {}, { ...config })
    },

    /**
     * 
     * @returns Promise
     */
    clearCache(config = {}) {
        return webClient.get('system/clear-cache', { ...config })
    },

    /**
     * 
     * @returns Promise
     */
    optimize(config = {}) {
        return webClient.get('system/optimize', { ...config })
    },
    
}