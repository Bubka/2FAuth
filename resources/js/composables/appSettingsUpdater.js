import appSettingService from '@/services/appSettingService'
import { useErrorHandler } from '@2fauth/stores'

/**
 * Saves a setting on the backend
 * @param {string} preference 
 * @param {any} value 
 */
export async function useAppSettingsUpdater(setting, value, returnValidationError = false) {

    let data = null
    let error = null

    await appSettingService.update(setting, value, { returnError: true })
    .then(response => {
        data = value
    })
    .catch(err => {
        if( returnValidationError && err.response.status === 422 ) {
            error = err.response.data.message
        }
        else {
            useErrorHandler().show(err)
        }
    })

    return { data, error }
}