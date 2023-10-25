import { useTwofaccounts } from '@/stores/twofaccounts'

/**
 * Allows the user to access the main view only if he owns at least one twofaccount.
 * Push to the starter view otherwise.
 */
export default function starter({ to, next }) {
    const twofaccounts = useTwofaccounts()

    if (twofaccounts.isEmpty) {
        twofaccounts.refresh().then(() => {
            if (twofaccounts.isEmpty) {
                next({ name: 'start' });
            }
            else next()
        })
    }
    else next()
}