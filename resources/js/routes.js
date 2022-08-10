import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Start            from './views/Start'
import Capture          from './views/Capture'
import Accounts         from './views/Accounts'
import CreateAccount    from './views/twofaccounts/Create'
import EditAccount      from './views/twofaccounts/Edit'
import ImportAccount    from './views/twofaccounts/Import'
import QRcodeAccount    from './views/twofaccounts/QRcode'
import Groups           from './views/Groups'
import CreateGroup      from './views/groups/Create'
import EditGroup        from './views/groups/Edit'
import Login            from './views/auth/Login'
import Register         from './views/auth/Register'
import Autolock         from './views/auth/Autolock'
import PasswordRequest  from './views/auth/password/Request'
import PasswordReset    from './views/auth/password/Reset'
import WebauthnLost     from './views/auth/webauthn/Lost'
import WebauthnRecover  from './views/auth/webauthn/Recover'
import SettingsOptions  from './views/settings/Options'
import SettingsAccount  from './views/settings/Account'
import SettingsOAuth    from './views/settings/OAuth'
import SettingsWebAuthn from './views/settings/WebAuthn'
import EditCredential   from './views/settings/Credentials/Edit'
import GeneratePAT      from './views/settings/PATokens/Create'
import Errors           from './views/Error'
import About            from './views/About'

const router = new Router({
    mode: 'history',
    routes: [
        { path: '/start', name: 'start', component: Start, meta: { requiresAuth: true }, props: true },
        { path: '/capture', name: 'capture', component: Capture, meta: { requiresAuth: true }, props: true },

        { path: '/accounts', name: 'accounts', component: Accounts, meta: { requiresAuth: true }, alias: '/', props: true },
        { path: '/account/create', name: 'createAccount', component: CreateAccount, meta: { requiresAuth: true } },
        { path: '/account/import', name: 'importAccounts', component: ImportAccount, meta: { requiresAuth: true } },
        { path: '/account/:twofaccountId/edit', name: 'editAccount', component: EditAccount, meta: { requiresAuth: true } },
        { path: '/account/:twofaccountId/qrcode', name: 'showQRcode', component: QRcodeAccount, meta: { requiresAuth: true } },

        { path: '/groups', name: 'groups', component: Groups, meta: { requiresAuth: true }, props: true },
        { path: '/group/create', name: 'createGroup', component: CreateGroup, meta: { requiresAuth: true } },
        { path: '/group/:groupId/edit', name: 'editGroup', component: EditGroup, meta: { requiresAuth: true }, props: true },

        { path: '/settings/options', name: 'settings.options', component: SettingsOptions, meta: { requiresAuth: true, showAbout: true } },
        { path: '/settings/account', name: 'settings.account', component: SettingsAccount, meta: { requiresAuth: true, showAbout: true } },
        { path: '/settings/oauth', name: 'settings.oauth.tokens', component: SettingsOAuth, meta: { requiresAuth: true, showAbout: true } },
        { path: '/settings/oauth/pat/create', name: 'settings.oauth.generatePAT', component: GeneratePAT, meta: { requiresAuth: true, showAbout: true } },
        { path: '/settings/webauthn/:credentialId/edit', name: 'settings.webauthn.editCredential', component: EditCredential, meta: { requiresAuth: true, showAbout: true }, props: true },
        { path: '/settings/webauthn', name: 'settings.webauthn.devices', component: SettingsWebAuthn, meta: { requiresAuth: true, showAbout: true } },

        { path: '/login', name: 'login', component: Login, meta: { disabledWithAuthProxy: true, showAbout: true } },
        { path: '/register', name: 'register', component: Register, meta: { disabledWithAuthProxy: true, showAbout: true } },
        { path: '/autolock', name: 'autolock',component: Autolock, meta: { disabledWithAuthProxy: true, showAbout: true } },
        { path: '/password/request', name: 'password.request', component: PasswordRequest, meta: { disabledWithAuthProxy: true, showAbout: true } },
        { path: '/user/password/reset', name: 'password.reset', component: PasswordReset, meta: { disabledWithAuthProxy: true, showAbout: true } },
        { path: '/webauthn/lost', name: 'webauthn.lost', component: WebauthnLost, meta: { disabledWithAuthProxy: true, showAbout: true } },
        { path: '/webauthn/recover', name: 'webauthn.recover', component: WebauthnRecover, meta: { disabledWithAuthProxy: true, showAbout: true } },

        { path: '/about', name: 'about',component: About, meta: { showAbout: true } },
        { path: '/flooded', name: 'flooded',component: Errors, props: true },
        { path: '/error', name: 'genericError',component: Errors, props: true },
        { path: '/404', name: '404',component: Errors, props: true },
        { path: '*', redirect: { name: '404' } }
    ],
});

let isFirstLoad = true;

router.beforeEach((to, from, next) => {
    
    document.title = router.app.$options.i18n.t('titles.' + to.name)
    
    if( to.name === 'accounts') {
        to.params.isFirstLoad = isFirstLoad ? true : false
        isFirstLoad = false;
    }

    if (to.matched.some(record => record.meta.disabledWithAuthProxy)) {
        if (window.appConfig.proxyAuth) {
            next({ name: 'accounts' })
        }
        else next()
    }
    else next()
});

router.afterEach(to => {
    Vue.$storage.set('lastRoute', to.name)
});

export default router