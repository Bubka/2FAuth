import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Accounts         from './views/Accounts'
import Create           from './views/twofaccounts/Create'
import Edit             from './views/twofaccounts/Edit'
import Login            from './views/auth/Login'
import Register         from './views/auth/Register'
import PasswordRequest  from './views/auth/password/Request'
import PasswordReset    from './views/auth/password/Reset'
import Settings         from './views/settings/Index'
import Errors           from './views/Error'

const router = new Router({
    mode: 'history',
    routes: [
        { path: '/accounts', name: 'accounts', component: Accounts, meta: { requiresAuth: true }, alias: '/', props: true },
        { path: '/settings', name: 'settings', component: Settings, meta: { requiresAuth: true } },
        { path: '/create', name: 'create', component: Create, meta: { requiresAuth: true } },
        { path: '/edit/:twofaccountId', name: 'edit', component: Edit, meta: { requiresAuth: true } },

        { path: '/login', name: 'login', component: Login },
        { path: '/register', name: 'register', component: Register },
        { path: '/password/request', name: 'password.request', component: PasswordRequest },
        { path: '/password/reset/:token', name: 'password.reset', component: PasswordReset },

        { path: '/flooded', name: 'flooded',component: Errors,props: true },
        { path: '/error', name: 'genericError',component: Errors,props: true },
        { path: '/404', name: '404',component: Errors,props: true },
        { path: '*', redirect: { name: '404' } }
    ],
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // Accesses to restricted pages without a jwt token are routed to the login page
        if ( !localStorage.getItem('jwt') ) {
            next({
                name: 'login'
            })
        }
        // If the jwt token is invalid, a 401 unauthorized is send by the php backend
        else {
            next()
        }
    }
    else {
        next()
    }
});

export default router