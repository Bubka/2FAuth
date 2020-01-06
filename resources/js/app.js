import Vue          from 'vue'
import VueRouter    from 'vue-router'

Vue.use(VueRouter)

import App          from './views/App'
import Login        from './views/Login'
import Register     from './views/Register'
import Accounts     from './views/Accounts'
import Create       from './views/Create'
import Edit         from './views/Edit'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faPlusCircle, faQrcode, faImage } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faPlusCircle, faQrcode, faImage);

Vue.component('font-awesome-icon', FontAwesomeIcon)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'accounts',
            component: Accounts
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
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});