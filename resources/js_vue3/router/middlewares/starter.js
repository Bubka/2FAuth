/**
 * Allows an authenticated user to access the main view only if he owns at least one twofaccount.
 * Push to the starter view otherwise.
 */
export default function starter({ to, next, stores }) {
    const { twofaccounts } = stores

    if (twofaccounts.isEmpty) {
        twofaccounts.fetch().then(() => {
            if (twofaccounts.isEmpty) {
                next({ name: 'start' });
            }
            else next()
        })
    }
    else next()
}