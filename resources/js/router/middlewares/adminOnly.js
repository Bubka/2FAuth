/**
 * Allows an authenticated user to access the route only if he has administrator rights
 */
export default async function adminOnly({ to, next, nextMiddleware, stores }) {
    const { user } = stores
    const { errorHandler } = stores

    if (! user.isAdmin) {
        let err = new Error('unauthorized')
        err.response.status = 403
        errorHandler.show(err)
    }
    else nextMiddleware()
}