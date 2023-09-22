import '/resources/js_vue3/assets/app.scss'; 

import { createApp } from 'vue'
import { i18nVue } from 'laravel-vue-i18n'
// import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import Notifications from '@kyvg/vue3-notification'
import FontAwesomeIcon from './icons'

const app = createApp(App)

// Immutable app properties provided by the laravel blade view
app.config.globalProperties.$2fauth = {
    config: window.appConfig,
    version: window.appVersion,
    isDemoApp: window.isDemoApp,
    isTestingApp: window.isTestingApp,
    langs: window.appLocales
}

// app.use(createPinia())
app.use(router)
app.use(i18nVue, {
    resolve: async lang => {
        const langs = import.meta.glob('../lang/*.json');
        return await langs[`../lang/${lang}.json`]();
    }
})
app.use(Notifications)

app.component('font-awesome-icon', FontAwesomeIcon)

app.mount('#app')
