import authService from '@/services/authService'

export default async function authGuard({ to, next, nextMiddleware, stores }) {
    const { user } = stores

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
                preferences: currentUser.preferences,
                isAdmin: currentUser.is_admin,
            })
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