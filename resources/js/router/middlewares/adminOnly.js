/**
 * Allows an authenticated user to access the route only if he has administrator rights
 */
export default async function adminOnly({ to, next, nextMiddleware, stores }) {
    const { user } = stores
    const { errorHandler } = stores

    if (! user.isAdmin) {
        let err = new Error('unauthorized')
        const responseOptions = { status: 403, statusText: 'unauthorized' }
        const response = new Response(null, responseOptions);
        err.response = response
        errorHandler.show(err)
    }
    else nextMiddleware()
}