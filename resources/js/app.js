import Vue              from 'vue'
import mixins           from './mixins'
import router           from './routes'
import api              from './api'
import i18n             from './langs/i18n'
import FontAwesome      from './packages/fontawesome'
import Clipboard        from './packages/clipboard'
import QrcodeReader     from './packages/qrcodeReader'
import Notifications    from 'vue-notification'

import './components'

Vue.use(Notifications)

const app = new Vue({
    el: '#app',
    data: {
        appSettings: window.appSettings
    },
    i18n,
    router,
});