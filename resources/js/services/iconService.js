import { httpClientFactory } from '@/services/httpClientFactory'

const apiClient = httpClientFactory('api')

export default {
    getLogo(service, iconCollection, variant, config = {}) {
        return apiClient.post('/icons/default', { service: service, iconCollection: iconCollection, variant: variant }, { ...config })
    },

    getLogoFromPack(service, iconPack,  config = {}) {
        return apiClient.post('/icons/default', { service: service, iconPack: iconPack }, { ...config })
    },

    getIconPacks(config = {}) {
        return apiClient.get('/icons/packs', { ...config })
    },

    deleteIcon(icon, config = {}) {
        return apiClient.delete('/icons/' + icon, { ...config })
    },
}