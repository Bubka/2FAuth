import { createRouter, createWebHistory } from 'vue-router'
import middlewarePipeline from "@/router/middlewarePipeline";
import { useUserStore } from '@/stores/user'
import { useTwofaccounts } from '@/stores/twofaccounts'
import { useAppSettingsStore } from '@/stores/appSettings'
import { useNotify } from '@2fauth/ui'
import { useErrorHandler } from '@2fauth/stores'

import authGuard        from './middlewares/authGuard'
import adminOnly        from './middlewares/adminOnly'
import starter          from './middlewares/starter'
import noEmptyError     from './middlewares/noEmptyError'
import noRegistration   from './middlewares/noRegistration'
import setReturnTo      from './middlewares/setReturnTo'
import skipIfAuthProxy  from './middlewares/skipIfAuthProxy'
import syncAppSettings  from './middlewares/syncAppSettings'

const router = createRouter({
	history: createWebHistory(window.appConfig.subdirectory ? window.appConfig.subdirectory : '/'),
	routes: [
		{ path: '/start', name: 'start', component: () => import('../views/Start.vue'), meta: { middlewares: [authGuard, syncAppSettings, setReturnTo], watchedByKicker: true } },
        { path: '/capture', name: 'capture', component: () => import('../views/twofaccounts/Capture.vue'), meta: { middlewares: [authGuard, syncAppSettings, setReturnTo], watchedByKicker: true } },

        { path: '/accounts', name: 'accounts', component: () => import('../views/twofaccounts/Accounts.vue'), meta: { middlewares: [authGuard, syncAppSettings, starter, setReturnTo], watchedByKicker: true }, alias: '/' },
        { path: '/account/create', name: 'createAccount', component: () => import('../views/twofaccounts/CreateUpdate.vue'), meta: { middlewares: [authGuard, syncAppSettings, setReturnTo], watchedByKicker: true } },
        { path: '/account/import', name: 'importAccounts', component: () => import('../views/twofaccounts/Import.vue'), meta: { middlewares: [authGuard, syncAppSettings, setReturnTo], watchedByKicker: true } },
        { path: '/account/:twofaccountId/edit', name: 'editAccount', component: () => import('../views/twofaccounts/CreateUpdate.vue'), meta: { middlewares: [authGuard, syncAppSettings, setReturnTo], watchedByKicker: true }, props: true },
        { path: '/account/:twofaccountId/qrcode', name: 'showQRcode', component: () => import('../views/twofaccounts/QRcode.vue'), meta: { middlewares: [authGuard, syncAppSettings, setReturnTo], watchedByKicker: true } },

        { path: '/groups', name: 'groups', component: () => import('../views/groups/Groups.vue'), meta: { middlewares: [authGuard, syncAppSettings, setReturnTo], watchedByKicker: true }, props: true },
        { path: '/group/create', name: 'createGroup', component: () => import('../views/groups/CreateUpdate.vue'), meta: { middlewares: [authGuard, syncAppSettings, setReturnTo], watchedByKicker: true } },
        { path: '/group/:groupId/edit', name: 'editGroup', component: () => import('../views/groups/CreateUpdate.vue'), meta: { middlewares: [authGuard, syncAppSettings, setReturnTo], watchedByKicker: true }, props: true },

        { path: '/settings/options', name: 'settings.options', component: () => import('../views/settings/Options.vue'), meta: { middlewares: [authGuard, syncAppSettings], watchedByKicker: true, showAbout: true } },
        { path: '/settings/account', name: 'settings.account', component: () => import('../views/settings/Account.vue'), meta: { middlewares: [authGuard, syncAppSettings], watchedByKicker: true, showAbout: true } },
        { path: '/settings/oauth', name: 'settings.oauth.tokens', component: () => import('../views/settings/OAuth.vue'), meta: { middlewares: [authGuard, syncAppSettings], watchedByKicker: true, showAbout: true, props: true } },
        { path: '/settings/webauthn/:credentialId/edit', name: 'settings.webauthn.editCredential', component: () => import('../views/settings/Credentials/Edit.vue'), meta: { middlewares: [authGuard, syncAppSettings], watchedByKicker: true, showAbout: true }, props: true },
        { path: '/settings/webauthn', name: 'settings.webauthn.devices', component: () => import('../views/settings/WebAuthn.vue'), meta: { middlewares: [authGuard, syncAppSettings], watchedByKicker: true, showAbout: true } },

        { path: '/admin/app', name: 'admin.appSetup', component: () => import('../views/admin/AppSetup.vue'), meta: { middlewares: [authGuard, adminOnly], watchedByKicker: true, showAbout: true } },
        { path: '/admin/auth', name: 'admin.auth', component: () => import('../views/admin/Auth.vue'), meta: { middlewares: [authGuard, adminOnly], watchedByKicker: true, showAbout: true } },
        { path: '/admin/users', name: 'admin.users', component: () => import('../views/admin/Users.vue'), meta: { middlewares: [authGuard, syncAppSettings, adminOnly], watchedByKicker: true, showAbout: true } },
        { path: '/admin/users/create', name: 'admin.createUser', component: () => import('../views/admin/users/Create.vue'), meta: { middlewares: [authGuard, syncAppSettings, adminOnly], watchedByKicker: true, showAbout: true } },
        { path: '/admin/users/:userId/manage', name: 'admin.manageUser', component: () => import('../views/admin/users/Manage.vue'), meta: { middlewares: [authGuard, syncAppSettings, adminOnly], watchedByKicker: true, showAbout: true }, props: true },
        { path: '/admin/logs/:userId/access', name: 'admin.logs.access', component: () => import('../views/admin/logs/Access.vue'), meta: { middlewares: [authGuard, syncAppSettings, adminOnly], watchedByKicker: true, showAbout: true }, props: true },
        
        { path: '/login', name: 'login', component: () => import('../views/auth/Login.vue'), meta: { middlewares: [skipIfAuthProxy, setReturnTo], showAbout: true } },
        { path: '/register', name: 'register', component: () => import('../views/auth/Register.vue'), meta: { middlewares: [skipIfAuthProxy, noRegistration, setReturnTo], showAbout: true } },
        { path: '/password/request', name: 'password.request', component: () => import('../views/auth/RequestReset.vue'), meta: { middlewares: [skipIfAuthProxy, setReturnTo], showAbout: true } },
        { path: '/user/password/reset', name: 'password.reset', component: () => import('../views/auth/password/Reset.vue'), meta: { middlewares: [skipIfAuthProxy, setReturnTo], showAbout: true } },
        { path: '/webauthn/lost', name: 'webauthn.lost', component: () => import('../views/auth/RequestReset.vue'), meta: { middlewares: [skipIfAuthProxy, setReturnTo], showAbout: true } },
        { path: '/webauthn/recover', name: 'webauthn.recover', component: () => import('../views/auth/webauthn/Recover.vue'), meta: { middlewares: [skipIfAuthProxy, setReturnTo], showAbout: true } },

        { path: '/about', name: 'about', component: () => import('../views/About.vue'), meta: { showAbout: true, watchedByKicker: true } },
        { path: '/error', name: 'genericError', component: () => import('../views/Error.vue'), meta: { middlewares: [noEmptyError], watchedByKicker: true } },
        { path: '/404', name: '404', component: () => import('../views/Error.vue'), meta: { watchedByKicker: true }, props: true },
        { path: '/:pathMatch(.*)*', name: 'notFound', component: () => import('../views/Error.vue'), meta: { watchedByKicker: true }, props: true },
	]
})

router.beforeEach((to, from, next) => {
    const middlewares = to.meta.middlewares
    const user = useUserStore()
    const twofaccounts = useTwofaccounts()
    const appSettings = useAppSettingsStore()
    const notify = useNotify()
    const errorHandler = useErrorHandler()
    const stores = { user: user, twofaccounts: twofaccounts, appSettings: appSettings, notify: notify, errorHandler: errorHandler }
    const nextMiddleware = {}
    const context = { to, from, next, nextMiddleware, stores }

    if (!middlewares) {
        return next();
    }

    middlewares[0]({
        ...context,
        nextMiddleware: middlewarePipeline(context, middlewares, 1),
    });
})

export default router
