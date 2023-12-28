/**
 * Prevents the view to be reached by users authenticated throught an auth proxy
 */
export default async function skipIfAuthProxy({ to, next, nextMiddleware, stores }) {
    const { appSettings } = stores

    if (appSettings.$2fauth.config.proxyAuth) {
        next({ name: 'accounts' })
    }
    else nextMiddleware()
}