import Vue          from 'vue'
import VueRouter    from 'vue-router'

Vue.use(VueRouter)

import App          from './views/App'
import Login        from './views/Login'
import Register     from './views/Register'
import Accounts     from './views/Accounts'
import Create       from './views/Create'
import Edit         from './views/Edit'
import NotFound     from './views/Error'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faPlus, faQrcode, faImage, faTrash, faEdit, faCheck, faLock, faLockOpen } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faPlus, faQrcode, faImage, faTrash, faEdit, faCheck, faLock, faLockOpen);

Vue.component('font-awesome-icon', FontAwesomeIcon)

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
            path: '/404',
            name: '404',
            component: NotFound
        },
        {
            path: '/error',
            name: 'GenericError',
            component: NotFound
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});