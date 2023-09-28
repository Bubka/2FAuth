import '/resources/js_vue3/assets/app.scss'; 

import Notifications from '@kyvg/vue3-notification'
import App from './App.vue'
import router from './router'
import FontAwesomeIcon from './icons'

const app = createApp(App)

// Immutable app properties provided by the laravel blade view
const $2fauth = {
    prefix: '2fauth_',
    config: window.appConfig,
    version: window.appVersion,
    isDemoApp: window.isDemoApp,
    isTestingApp: window.isTestingApp,
    langs: window.appLocales,
}
app.provide('2fauth', readonly($2fauth))

// Stores
const pinia = createPinia()
pinia.use(({ store }) => {
    store.$2fauth = $2fauth;
});
app.use(pinia)

// Router
app.use(router)

// Localization
app.use(i18nVue, {
    lang: document.documentElement.lang.substring(0, 2),
    resolve: async lang => {
        const langs = import.meta.glob('../lang/*.json');
        if (lang.includes('php_')) {
            return await langs[`../lang/${lang}.json`]();
        }
    }
})
app.use(Notifications)

// Components registration
import ResponsiveWidthWrapper from '@/layouts/ResponsiveWidthWrapper.vue'
import FormWrapper from '@/layouts/FormWrapper.vue'
import Footer from '@/layouts/Footer.vue'
import Modal from '@/layouts/Modal.vue'
import VueButton           from '@/components/formElements/Button.vue'
import FieldError       from '@/components/formElements/FieldError.vue'
import FormField        from '@/components/formElements/FormField.vue'
import FormPasswordField        from '@/components/formElements/FormPasswordField.vue'
// import FormSelect       from './FormSelect'
// import FormSwitch       from './FormSwitch'
// import FormToggle       from './FormToggle'
// import FormCheckbox     from './FormCheckbox'
import FormButtons      from '@/components/formElements/FormButtons.vue'
// import Kicker           from './Kicker'
// import SettingTabs      from './SettingTabs'

app
    .component('FontAwesomeIcon', FontAwesomeIcon)
    .component('ResponsiveWidthWrapper', ResponsiveWidthWrapper)
    .component('FormWrapper', FormWrapper)
    .component('VueFooter', Footer)
    .component('Modal', Modal)
    .component('VueButton', VueButton)
    .component('FieldError', FieldError)
    .component('FormField', FormField)
    .component('FormPasswordField', FormPasswordField)
    .component('FormButtons', FormButtons)

// Global error handling
import { useNotifyStore } from '@/stores/notify'

app.config.errorHandler = (err, instance, info) => {
    useNotifyStore().error(err)
}

// App mounting
app.mount('#app')