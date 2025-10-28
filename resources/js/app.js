import '@2fauth/styles/src/app.scss';

import i18n from './i18n'
import Notifications from '@kyvg/vue3-notification'
import App from './App.vue'
import router from './router'

const app = createApp(App)

// Immutable app properties provided by the laravel blade view
const $2fauth = {
    prefix: '2fauth_',
    config: window.appConfig,
    version: window.appVersion,
    isDemoApp: window.isDemoApp,
    isTestingApp: window.isTestingApp,
    langs: window.appLocales,
    urls: window.urls,
    context: 'webapp',
}
app.provide('2fauth', readonly($2fauth))

// Localization
app.use(i18n)

// Stores
const pinia = createPinia()
pinia.use(({ store }) => {
    store.$2fauth = $2fauth
    store.$i18n = i18n
    store.$router = markRaw(router)
})
app.use(pinia)

// Router
app.use(router)

// Notifications
app.use(Notifications)

// Global components registration
import {
    FormField,
    FormPasswordField,
    FormFieldError,
    FormCheckbox,
    FormSelect,
    FormToggle,
    FormButtons,
    NavigationButton,
    VueButton
} from '@2fauth/formcontrols'

import {
    FormWrapper,
    Modal,
    ResponsiveWidthWrapper,
    Spinner,
    VueFooter,
} from '@2fauth/ui'

app
    .component('ResponsiveWidthWrapper', ResponsiveWidthWrapper)
    .component('Spinner', Spinner)
    .component('FormWrapper', FormWrapper)
    .component('VueFooter', VueFooter)
    .component('Modal', Modal)
    .component('VueButton', VueButton)
    .component('NavigationButton', NavigationButton)
    .component('FormFieldError', FormFieldError)
    .component('FormField', FormField)
    .component('FormPasswordField', FormPasswordField)
    .component('FormSelect', FormSelect)
    .component('FormToggle', FormToggle)
    .component('FormCheckbox', FormCheckbox)
    .component('FormButtons', FormButtons)

// Global error handling
// import { useNotify } from '@2fauth/ui'
// if (process.env.NODE_ENV != 'development') {
//     app.config.errorHandler = (err) => {
//         useNotify().parse(err)
//         router.push({ name: 'genericError' })
//     }
// }

// Helpers
// app.config.globalProperties.$helpers = helpers

// App inject for footer
// TODO : Try to avoid those global injection
import { useUserStore } from '@/stores/user'
import { useAppSettingsStore } from '@/stores/appSettings'

const user = useUserStore()
const appSettings = useAppSettingsStore()

user.applyUserPrefs()

app.provide('userStore', user)
app.provide('appSettingsStore', appSettings)

// App mounting
app.mount('#app')