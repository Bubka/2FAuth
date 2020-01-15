import Vue          from 'vue'
import VueRouter    from 'vue-router'
import axios        from './packages/axios'
import i18n         from './packages/i18n'
import FontAwesome  from './packages/fontawesome'

import App              from './views/App'
import Accounts         from './views/Accounts'
import Create           from './views/twofaccounts/Create'
import Edit             from './views/twofaccounts/Edit'
import Login            from './views/auth/Login'
import Register         from './views/auth/Register'
import PasswordRequest  from './views/auth/password/Request'
import PasswordReset    from './views/auth/password/Reset'
import NotFound         from './views/Error'

import './components'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', name: 'accounts', component: Accounts, props: true },
        { path: '/login', name: 'login',component: Login },
        { path: '/register', name: 'register',component: Register },
        { path: '/create', name: 'create',component: Create },
        { path: '/edit/:twofaccountId', name: 'edit',component: Edit },

        { path: '/password/request', name: 'password.request', component: PasswordRequest },
        { path: '/password/reset/:token', name: 'password.reset', component: PasswordReset },

        { path: '/flooded', name: 'flooded',component: NotFound,props: true },
        { path: '/error', name: 'genericError',component: NotFound,props: true },
        { path: '/404', name: '404',component: NotFound,props: true },
        { path: '*', redirect: { name: '404' } }
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    i18n,
    router,
});