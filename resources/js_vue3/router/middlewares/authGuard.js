import authService from '@/services/authService'

export default async function auth({ to, next, stores }) {
    const { user } = stores

    // No authenticated user on the front-end side, we try to
    // get an active user from the back-end side
    if (! user.isAuthenticated) {
        const currentUser = await authService.getCurrentUser()
        if (currentUser) {
            user.loginAs({
                name: currentUser.name,
                email: currentUser.email,
                preferences: currentUser.preferences,
                isAdmin: currentUser.is_admin,
            })
        }
    }

    if (! user.isAuthenticated) {
        next({ name: 'login' })
    } else {
        next()
    }
}