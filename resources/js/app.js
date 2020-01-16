import Vue          from 'vue'
import router       from './routes/routes'
import axios        from './packages/axios'
import i18n         from './packages/i18n'
import FontAwesome  from './packages/fontawesome'
import App          from './components/App'

import './components'

const app = new Vue({
    el: '#app',
    components: { App },
    i18n,
    router,
});