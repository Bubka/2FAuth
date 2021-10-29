import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Start            from './views/Start'
import Capture          from './views/Capture'
import Accounts         from './views/Accounts'
import CreateAccount    from './views/twofaccounts/Create'
import EditAccount      from './views/twofaccounts/Edit'
import QRcodeAccount    from './views/twofaccounts/QRcode'
import Groups           from './views/Groups'
import CreateGroup      from './views/groups/Create'
import EditGroup        from './views/groups/Edit'
import Login            from './views/auth/Login'
import Register         from './views/auth/Register'
import PasswordRequest  from './views/auth/password/Request'
import PasswordReset    from './views/auth/password/Reset'
import SettingsOptions  from './views/settings/Options'
import SettingsAccount  from './views/settings/Account'
import SettingsOAuth    from './views/settings/OAuth'
import GeneratePAT      from './views/settings/PATokens/Create'
import Errors           from './views/Error'

const router = new Router({
    mode: 'history',
    routes: [
        { path: '/start', name: 'start', component: Start, meta: { requiresAuth: true }, props: true },
        { path: '/capture', name: 'capture', component: Capture, meta: { requiresAuth: true }, props: true },

        { path: '/accounts', name: 'accounts', component: Accounts, meta: { requiresAuth: true }, alias: '/', props: true },
        { path: '/account/create', name: 'createAccount', component: CreateAccount, meta: { requiresAuth: true } },
        { path: '/account/:twofaccountId/edit', name: 'editAccount', component: EditAccount, meta: { requiresAuth: true } },
        { path: '/account/:twofaccountId/qrcode', name: 'showQRcode', component: QRcodeAccount, meta: { requiresAuth: true } },

        { path: '/groups', name: 'groups', component: Groups, meta: { requiresAuth: true }, props: true },
        { path: '/group/create', name: 'createGroup', component: CreateGroup, meta: { requiresAuth: true } },
        { path: '/group/:groupId/edit', name: 'editGroup', component: EditGroup, meta: { requiresAuth: true }, props: true },

        { path: '/settings/options', name: 'settings.options', component: SettingsOptions, meta: { requiresAuth: true } },
        { path: '/settings/account', name: 'settings.account', component: SettingsAccount, meta: { requiresAuth: true } },
        { path: '/settings/oauth', name: 'settings.oauth', component: SettingsOAuth, meta: { requiresAuth: true } },
        { path: '/settings/oauth/pat/create', name: 'settings.oauth.generatePAT', component: GeneratePAT, meta: { requiresAuth: true } },

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

let isFirstLoad = true;

router.beforeEach((to, from, next) => {   

    if( to.name === 'accounts') {
        to.params.isFirstLoad = isFirstLoad ? true : false
        isFirstLoad = false;
    }

    next()

});

router.afterEach(to => {
    Vue.$storage.set('lastRoute', to.name)
});

export default router