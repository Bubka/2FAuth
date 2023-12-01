/**
 * Allows an authenticated user to access the main view only if he owns at least one twofaccount.
 * Push to the starter view otherwise.
 */
export default function setReturnTo({ to, next, nextMiddleware, stores }) {
    const { user } = stores
    const returnTo = useStorage(user.$2fauth.prefix + 'returnTo', 'accounts')
    returnTo.value = to.name
    
    nextMiddleware()
}