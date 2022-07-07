import Vue              from 'vue'
import mixins           from './mixins'
import VueStorage       from './packages/vue-storage'
import i18n             from './langs/i18n'
import router           from './routes'
import api              from './api'
import FontAwesome      from './packages/fontawesome'
import Clipboard        from './packages/clipboard'
import Notifications    from 'vue-notification'

import './components'

Vue.use(Notifications)

const app = new Vue({
    el: '#app',
    data: {
        appSettings: window.appSettings,
        appConfig: window.appConfig,
        isDemoApp: window.isDemoApp,
        isTestingApp: window.isTestingApp
    },
    i18n,
    router,
});