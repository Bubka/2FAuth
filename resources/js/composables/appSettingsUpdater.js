import appSettingService from '@/services/appSettingService'
import { useI18n } from 'vue-i18n'
import { useErrorHandler } from '@2fauth/stores'
import { useNotify } from '@2fauth/ui'

/**
 * Saves a setting on the backend
 * @param {string} preference 
 * @param {any} value 
 */
export async function useAppSettingsUpdater(setting, value, returnValidationError = false) {

    const { t } = useI18n()

    let data = null
    let error = null

    await appSettingService.update(setting, value, { returnError: true })
    .then(response => {
        data = value
        useNotify().success({ text: t('message.settings.forms.setting_saved') })
    })
    .catch(err => {
        if( returnValidationError && err.response.status === 422 ) {
            error = err
        }
        else {
            useErrorHandler().parse(err)
            router.push({ name: 'genericError' })
        }
    })

    return { data, error }
}