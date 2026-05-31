import authService from '@/services/authService'
import { asArray } from '@/composables/helpers'

export default async function authGuard({ to, next, nextMiddleware, stores }) {
    const { user, appSettings } = stores

    // No authenticated user on the front-end side, we try to
    // get an active user from the back-end side
    if (! user.isAuthenticated) {
        await authService.getCurrentUser({ returnError: true }).then(async (response) => {
            const currentUser = response.data
            await user.loginAs({
                id: currentUser.id,
                name: currentUser.name,
                email: currentUser.email,
                oauth_provider: currentUser.oauth_provider,
                authenticated_by_proxy: currentUser.authenticated_by_proxy,
                preferences: currentUser.preferences,
                isAdmin: currentUser.is_admin,
            })

            for (const [key, value] of Object.entries(currentUser.appSettings || {})) {
                appSettings[key] = value
            }
        })
        .catch(error => {
            // nothing to do
        })
    }

    if (! user.isAuthenticated) {
        next({ name: 'login' })
    } else {
        nextMiddleware()
    }
}