import Vue          from 'vue'
import VueRouter    from 'vue-router'

Vue.use(VueRouter)

import App          from './views/App'
import Login        from './views/Login'
import Register     from './views/Register'
import Accounts         from './views/Accounts'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faPlusCircle } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faPlusCircle);

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
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});