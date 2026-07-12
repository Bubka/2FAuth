import authService from '@/services/authService'

export default async function authGuard({ to, next, nextMiddleware, stores }) {
    const { user, appSettings } = stores
    let twofaccount_count

    // No authenticated user on the front-end side, we try to
    // get an active user from the back-end side
    if (! user.isAuthenticated) {
        await authService.getCurrentUser({ returnError: true }).then(async (response) => {
            const currentUser = response.data
            twofaccount_count = parseInt(currentUser.twofaccount_count)
            const pageNumber = currentUser.preferences.usePagination && to.query['page[number]'] ? parseInt(to.query['page[number]']) : null

            await user.loginAs(
                {
                    id: currentUser.id,
                    name: currentUser.name,
                    email: currentUser.email,
                    oauth_provider: currentUser.oauth_provider,
                    authenticated_by_proxy: currentUser.authenticated_by_proxy,
                    preferences: currentUser.preferences,
                    isAdmin: currentUser.is_admin,
                },
                twofaccount_count > 0,
                pageNumber
            )

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
        if (twofaccount_count == 0) {
            next({ name: 'start' })
        }
        else
        {
            nextMiddleware()
        }
    }
}