import '/resources/js_vue3/assets/app.scss'; 

import { createApp } from 'vue'
// import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import Notifications from '@kyvg/vue3-notification'

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
app.use(Notifications)

app.mount('#app')
