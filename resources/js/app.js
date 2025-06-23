import '@2fauth/styles/src/app.scss';

import i18n from './i18n'
import Notifications from '@kyvg/vue3-notification'
import App from './App.vue'
import router from './router'
import FontAwesomeIcon from './icons'
// import helpers from './helpers'

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
}
app.provide('2fauth', readonly($2fauth))

// Localization
app.use(i18n)

// Stores
const pinia = createPinia()
pinia.use(({ store }) => {
    store.$2fauth = $2fauth;
    store.$i18n = i18n
})
app.use(pinia)

// Router
app.use(router)

// Notifications
app.use(Notifications)

// Global components registration
import ResponsiveWidthWrapper from '@/layouts/ResponsiveWidthWrapper.vue'
import FormWrapper from '@/layouts/FormWrapper.vue'
import Footer from '@/layouts/Footer.vue'
import Modal from '@/layouts/Modal.vue'
import Kicker           from '@/components/Kicker.vue'

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

app
    .component('FontAwesomeIcon', FontAwesomeIcon)
    .component('ResponsiveWidthWrapper', ResponsiveWidthWrapper)
    .component('FormWrapper', FormWrapper)
    .component('VueFooter', Footer)
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
    .component('Kicker', Kicker)

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

// App mounting
app.mount('#app')

// Theme
import { useUserStore } from '@/stores/user'
useUserStore().applyUserPrefs()
