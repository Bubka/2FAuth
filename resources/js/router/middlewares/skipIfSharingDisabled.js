/**
 * Prevents the view to be reached by users when sharing is disabled
 */
export default async function skipIfSharingDisabled({ to, next, nextMiddleware, stores }) {
    const { appSettings } = stores
    const { errorHandler } = stores

    if (!appSettings.enableSharing) {
        let err = new Error('unauthorized')
        const responseOptions = { status: 403, statusText: 'unauthorized' }
        const response = new Response(null, responseOptions);
        err.response = response
        errorHandler.show(err)
    }
    else nextMiddleware()
}