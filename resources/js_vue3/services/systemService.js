import { apiFactory } from '@/services/apiFactory'

const web = apiFactory('web')

export default {
    /**
     * 
     * @returns 
     */
    getSystemInfos() {
        return web.get('infos')
    },
    
}