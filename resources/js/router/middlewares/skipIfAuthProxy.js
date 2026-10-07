/**
 * Prevents the view to be reached by users authenticated throught an auth proxy
 */
export default async function skipIfAuthProxy({ stores }) {
    const { appSettings } = stores

    if (appSettings.$2fauth.config.proxyAuth) {
        return { name: 'accounts' }
    }

    return true
}