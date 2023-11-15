/**
 * Allows an authenticated user to access the main view only if he owns at least one twofaccount.
 * Push to the starter view otherwise.
 */
export default async function starter({ to, next, nextMiddleware, stores }) {
    const { twofaccounts } = stores

    if (twofaccounts.isEmpty) {
        await twofaccounts.fetch().then(() => {
            if (twofaccounts.isEmpty) {
                next({ name: 'start' })
            }
            else nextMiddleware()
        })
    }
    else nextMiddleware()
}