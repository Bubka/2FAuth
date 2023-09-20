import { createRouter, createWebHistory } from 'vue-router'

import Accounts from './views/Accounts.vue'

const router = createRouter({
  history: createWebHistory('/'),
  routes: [
    { path: '/accounts', name: 'accounts', component: Accounts, meta: { requiresAuth: true }, alias: '/', props: true },
    // {
    //   path: '/about',
    //   name: 'about',
    //   // route level code-splitting
    //   // this generates a separate chunk (About.[hash].js) for this route
    //   // which is lazy-loaded when the route is visited.
    //   component: () => import('../views/AboutView.vue')
    // }
  ]
})

export default router
