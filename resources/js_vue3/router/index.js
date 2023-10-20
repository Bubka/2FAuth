import { createRouter, createWebHistory } from 'vue-router'
import middlewarePipeline from "@/router/middlewarePipeline";
import { useUserStore } from '@/stores/user'

import Start            from '../views/Start.vue'
import Accounts         from '../views/Accounts.vue'
import Capture          from '../views/twofaccounts/Capture.vue'
import CreateAccount    from '../views/twofaccounts/Create.vue'
import EditAccount      from '../views/twofaccounts/Edit.vue'
import ImportAccount    from '../views/twofaccounts/Import.vue'
import QRcodeAccount    from '../views/twofaccounts/QRcode.vue'
import Groups           from '../views/Groups.vue'
// import CreateGroup      from './views/groups/Create.vue'
// import EditGroup        from './views/groups/Edit.vue'
import Login            from '../views/auth/Login.vue'
import Register         from '../views/auth/Register.vue'
// import Autolock         from './views/auth/Autolock.vue'
import PasswordRequest  from '../views/auth/password/Request.vue'
// import PasswordReset    from './views/auth/password/Reset.vue'
import WebauthnLost     from '../views/auth/webauthn/Lost.vue'
// import WebauthnRecover  from './views/auth/webauthn/Recover.vue'
import SettingsOptions  from '../views/settings/Options.vue'
import SettingsAccount  from '../views/settings/Account.vue'
import SettingsOAuth    from '../views/settings/OAuth.vue'
import SettingsWebAuthn from '../views/settings/WebAuthn.vue'
import EditCredential   from '../views/settings/Credentials/Edit.vue'
import Errors           from '../views/Error.vue'
import About            from '../views/About.vue'

import authGuard from './middlewares/authGuard'
import noEmptyError from './middlewares/noEmptyError'

const router = createRouter({
	history: createWebHistory('/'),
	routes: [
		{ path: '/start', name: 'start', component: Start, meta: { middlewares: [authGuard] } },
        { path: '/capture', name: 'capture', component: Capture, meta: { middlewares: [authGuard] } },

        { path: '/accounts', name: 'accounts', component: Accounts, meta: { middlewares: [authGuard] }, alias: '/' },
        { path: '/account/create', name: 'createAccount', component: CreateAccount, meta: { middlewares: [authGuard] } },
        { path: '/account/import', name: 'importAccounts', component: ImportAccount, meta: { middlewares: [authGuard] } },
        { path: '/account/:twofaccountId/edit', name: 'editAccount', component: EditAccount, meta: { middlewares: [authGuard] } },
        { path: '/account/:twofaccountId/qrcode', name: 'showQRcode', component: QRcodeAccount, meta: { middlewares: [authGuard] } },

        { path: '/groups', name: 'groups', component: Groups, meta: { middlewares: [authGuard] }, props: true },
        // { path: '/group/create', name: 'createGroup', component: CreateGroup, meta: { middlewares: [authGuard] } },
        // { path: '/group/:groupId/edit', name: 'editGroup', component: EditGroup, meta: { middlewares: [authGuard] }, props: true },

        { path: '/settings/options', name: 'settings.options', component: SettingsOptions, meta: { middlewares: [authGuard], showAbout: true } },
        { path: '/settings/account', name: 'settings.account', component: SettingsAccount, meta: { middlewares: [authGuard], showAbout: true } },
        { path: '/settings/oauth', name: 'settings.oauth.tokens', component: SettingsOAuth, meta: { middlewares: [authGuard], showAbout: true, props: true } },
        { path: '/settings/webauthn/:credentialId/edit', name: 'settings.webauthn.editCredential', component: EditCredential, meta: { middlewares: [authGuard], showAbout: true }, props: true },
        { path: '/settings/webauthn', name: 'settings.webauthn.devices', component: SettingsWebAuthn, meta: { middlewares: [authGuard], showAbout: true } },

        { path: '/login', name: 'login', component: Login, meta: { disabledWithAuthProxy: true, showAbout: true } },
        { path: '/register', name: 'register', component: Register, meta: { disabledWithAuthProxy: true, showAbout: true } },
        // { path: '/autolock', name: 'autolock',component: Autolock, meta: { disabledWithAuthProxy: true, showAbout: true } },
        { path: '/password/request', name: 'password.request', component: PasswordRequest, meta: { disabledWithAuthProxy: true, showAbout: true } },
        // { path: '/user/password/reset', name: 'password.reset', component: PasswordReset, meta: { disabledWithAuthProxy: true, showAbout: true } },
        { path: '/webauthn/lost', name: 'webauthn.lost', component: WebauthnLost, meta: { disabledWithAuthProxy: true, showAbout: true } },
        // { path: '/webauthn/recover', name: 'webauthn.recover', component: WebauthnRecover, meta: { disabledWithAuthProxy: true, showAbout: true } },

        { path: '/about', name: 'about', component: About, meta: { showAbout: true } },
        { path: '/error', name: 'genericError', component: Errors, meta: { middlewares: [noEmptyError], err: null } },
        // { path: '/404', name: '404',component: Errors, props: true },
        // { path: '*', redirect: { name: '404' } },

		// Lazy loaded view
		{ path: '/about', name: 'about', component: () => import('../views/About.vue') }
	]
})

router.beforeEach((to, from, next) => {
    const middlewares = to.meta.middlewares
    const user = useUserStore()
    const stores = { user: user }
    const context = { to, from, next, stores }

    if (!middlewares) {
        return next();
    }

    middlewares[0]({
        ...context,
        next: middlewarePipeline(context, middlewares, 1),
    });
})

router.afterEach((to, from) => {
    to.meta.title = trans('titles.' + to.name)
    document.title = to.meta.title
})

export default router
