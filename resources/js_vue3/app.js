import '/resources/js_vue3/assets/app.scss'; 

import { createApp } from 'vue'
import { i18nVue } from 'laravel-vue-i18n'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import Notifications from '@kyvg/vue3-notification'
import FontAwesomeIcon from './icons'

const app = createApp(App)

// Immutable app properties provided by the laravel blade view
const $2fauth = {
    prefix: '2fauth_',
    config: window.appConfig, //{"proxyAuth":false,"proxyLogoutUrl":false,"subdirectory":""}
    version: window.appVersion,
    isDemoApp: window.isDemoApp,
    isTestingApp: window.isTestingApp,
    langs: window.appLocales,
}
app.provide('2fauth', readonly($2fauth))

const pinia = createPinia()
pinia.use(({ store }) => {
    store.$2fauth = $2fauth;
});
app.use(pinia)

app.use(router)
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

import ResponsiveWidthWrapper from '@/layouts/ResponsiveWidthWrapper.vue'
import FormWrapper from '@/layouts/FormWrapper.vue'
import Footer from '@/layouts/Footer.vue'

// Components registration
app
    .component('font-awesome-icon', FontAwesomeIcon)
    .component('responsive-width-wrapper', ResponsiveWidthWrapper)
    .component('form-wrapper', FormWrapper)
    .component('vue-footer', Footer)

// App mounting
app.mount('#app')
