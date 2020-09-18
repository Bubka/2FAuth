import Vue          from 'vue'
import router       from './routes'
import api          from './api'
import i18n         from './langs/i18n'
import FontAwesome  from './packages/fontawesome'
import Clipboard    from './packages/clipboard'
import QrcodeReader from './packages/qrcodeReader'
import App          from './components/App'

import './components'

const app = new Vue({
    el: '#app',
    data: {
        appSettings: window.appSettings,
        appVersion: window.appVersion
    },
    components: { App },
    i18n,
    router,
});