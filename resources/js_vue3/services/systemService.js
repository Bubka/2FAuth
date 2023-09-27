import { httpClientFactory } from '@/services/httpClientFactory'

const webClient = httpClientFactory('web')

export default {
    /**
     * 
     * @returns 
     */
    getSystemInfos() {
        return webClient.get('infos')
    },
    
}