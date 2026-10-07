/**
 * Allows an authenticated user to access the main view only if he owns at least one twofaccount.
 * Push to the starter view otherwise.
 */
export default async function starter({ stores }) {
    const { twofaccounts } = stores

    if (twofaccounts.isEmpty) {
        await twofaccounts.fetch()
        
        if (twofaccounts.isEmpty) {
            return { name: 'start' }
        }
    }

    return true
}