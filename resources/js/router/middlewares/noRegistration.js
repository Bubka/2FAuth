/**
 * Prevent the Register view to be reachable when registration is disabled
 */
export default async function noRegistration({ to, next, nextMiddleware, stores }) {
    const { appSettings } = stores

    if (appSettings.disableRegistration) {
        next({ name: 'notFound' })
    }
    else nextMiddleware()
}