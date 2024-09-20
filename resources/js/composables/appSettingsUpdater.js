import appSettingService from '@/services/appSettingService'
import { useAppSettingsStore } from '@/stores/appSettings'
import { useNotifyStore } from '@/stores/notify'

/**
 * Saves a setting on the backend
 * @param {string} preference 
 * @param {any} value 
 */
export async function useAppSettingsUpdater(setting, value, returnValidationError = false) {

    // const appSettings = useAppSettingsStore()
    let data = null
    let error = null

    await appSettingService.update(setting, value, { returnError: true })
    .then(response => {
        // appSettings[setting] = value
        data = value
        useNotifyStore().success({ type: 'is-success', text: trans('settings.forms.setting_saved') })
    })
    .catch(err => {
        if( returnValidationError && err.response.status === 422 ) {
            error = err
        }
        else {
            useNotifyStore().error(err);
        }
    })

    return { data, error }
}