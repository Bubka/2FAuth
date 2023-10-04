import { httpClientFactory } from '@/services/httpClientFactory'

const webClient = httpClientFactory('web')

export default {
    /**
     * 
     * @returns Promise
     */
    getSystemInfos() {
        return webClient.get('infos')
    },

    /**
     * 
     * @returns Promise
     */
    getLastRelease() {
        return webClient.get('latestRelease')
    }
    
}