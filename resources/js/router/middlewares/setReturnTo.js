/**
 * Sets the returnTo value in local storage
 */
export default function setReturnTo({ to, stores }) {
    const { user } = stores
    const returnTo = useStorage(user.$2fauth.prefix + 'returnTo', 'accounts')
    returnTo.value = to.name

    return true
}