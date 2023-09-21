import { createRouter, createWebHistory } from 'vue-router'

import Accounts from './views/Accounts.vue'

const router = createRouter({
  history: createWebHistory('/'),
  routes: [
    { path: '/accounts', name: 'accounts', component: Accounts, meta: { requiresAuth: true }, alias: '/', props: true },
    
    // Lazy loaded view
    { path: '/about', name: 'about', component: () => import('./views/About.vue') }
  ]
})

export default router
