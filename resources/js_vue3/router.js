import { createRouter, createWebHistory } from 'vue-router'

import Accounts 		from './views/Accounts.vue'
import SettingsOptions  from './views/settings/Options.vue'
// import SettingsAccount  from './views/settings/Account'
// import SettingsOAuth    from './views/settings/OAuth'
// import SettingsWebAuthn from './views/settings/WebAuthn'
// import EditCredential   from './views/settings/Credentials/Edit'
// import GeneratePAT      from './views/settings/PATokens/Create'

const router = createRouter({
	history: createWebHistory('/'),
	routes: [
		{ path: '/accounts', name: 'accounts', component: Accounts, meta: { requiresAuth: true }, alias: '/', props: true },

		{ path: '/settings/options', name: 'settings.options', component: SettingsOptions, meta: { requiresAuth: true, showAbout: true } },
		// { path: '/settings/account', name: 'settings.account', component: SettingsAccount, meta: { requiresAuth: true, showAbout: true } },
		// { path: '/settings/oauth', name: 'settings.oauth.tokens', component: SettingsOAuth, meta: { requiresAuth: true, showAbout: true } },
		// { path: '/settings/oauth/pat/create', name: 'settings.oauth.generatePAT', component: GeneratePAT, meta: { requiresAuth: true, showAbout: true } },
		// { path: '/settings/webauthn/:credentialId/edit', name: 'settings.webauthn.editCredential', component: EditCredential, meta: { requiresAuth: true, showAbout: true }, props: true },
		// { path: '/settings/webauthn', name: 'settings.webauthn.devices', component: SettingsWebAuthn, meta: { requiresAuth: true, showAbout: true } },

		// Lazy loaded view
		{ path: '/about', name: 'about', component: () => import('./views/About.vue') }
	]
})

export default router
