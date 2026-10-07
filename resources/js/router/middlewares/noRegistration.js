/**
 * Prevent the Register view to be reachable when registration is disabled
 */
export default async function noRegistration({ stores }) {
    const { appSettings } = stores

    if (appSettings.disableRegistration) {
        return { name: 'notFound' }
    }

    return true
}