/**
 * Allows an authenticated user to access the main view only if he owns at least one twofaccount.
 * Push to the starter view otherwise.
 */
export default function starter({ to, next, stores }) {
    const { user } = stores
    const startPoint = useStorage(user.$2fauth.prefix + 'returnTo', 'accounts')
    startPoint.value = to.name
    
    next()
}