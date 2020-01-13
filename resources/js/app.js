import Vue          from 'vue'
import VueRouter    from 'vue-router'
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';

Vue.use(VueRouter)
Vue.use(VueInternationalization);

import App          from './views/App'
import Login        from './views/Login'
import Register     from './views/Register'
import Accounts     from './views/Accounts'
import Create       from './views/Create'
import Edit         from './views/Edit'
import NotFound     from './views/Error'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faPlus, faQrcode, faImage, faTrash, faEdit, faCheck, faLock, faLockOpen, faSearch } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faPlus, faQrcode, faImage, faTrash, faEdit, faCheck, faLock, faLockOpen, faSearch);

Vue.component('font-awesome-icon', FontAwesomeIcon)

// const lang = document.documentElement.lang.substr(0, 2);
const lang = 'en';

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'accounts',
            component: Accounts,
            props: true
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
        },
        {
            path: '/create',
            name: 'create',
            component: Create,
        },
        {
            path: '/edit/:twofaccountId',
            name: 'edit',
            component: Edit,
        },
        {
            path: '/flooded',
            name: 'flooded',
            component: NotFound,
            props: true
        },
        {
            path: '/error',
            name: 'genericError',
            component: NotFound,
            props: true
        },
        {
            path: '/404',
            name: '404',
            component: NotFound,
            props: true
        },
        {
            path: '*',
            redirect: { name: '404' }
        }
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    i18n,
    router,
});