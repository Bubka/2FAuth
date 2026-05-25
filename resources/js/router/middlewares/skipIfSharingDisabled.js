/**
 * Prevents the view to be reached by users when sharing is disabled
 */
export default async function skipIfSharingDisabled({ to, next, nextMiddleware, stores }) {
    const { appSettings } = stores
    const { errorHandler } = stores

    const until = (predFn) => {
        const poll = (done) => (predFn() ? done() : setTimeout(() => poll(done), 100))
        return new Promise(poll)
    }

    if (! appSettings.isSynced ) {
        appSettings.fetch()
        await until(() => appSettings.isSynced);
    }

    if (! appSettings.enableSharing) {
        let err = new Error('unauthorized')
        const responseOptions = { status: 403, statusText: 'unauthorized' }
        const response = new Response(null, responseOptions);
        err.response = response
        errorHandler.show(err)
    }
    else nextMiddleware()
}