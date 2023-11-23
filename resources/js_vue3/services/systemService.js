import { httpClientFactory } from '@/services/httpClientFactory'

const webClient = httpClientFactory('web')

export default {
    /**
     * 
     * @returns Promise
     */
    getSystemInfos(config = {}) {
        return webClient.get('infos', { ...config })
    },

    /**
     * 
     * @returns Promise
     */
    getLastRelease(config = {}) {
        return webClient.get('latestRelease', { ...config })
    }
    
}