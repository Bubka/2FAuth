/**
 * Retrieve app settings from the backend, only if the store is not synced yet
 */
export default function syncAppSettings({ to, next, nextMiddleware, stores }) {
    const { appSettings, user } = stores

    if (user.isAdmin && ! appSettings.isSynced ) {
        appSettings.fetch()
    }

    nextMiddleware()
}