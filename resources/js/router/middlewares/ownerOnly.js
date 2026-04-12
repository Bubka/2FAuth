/**
 * Allows an authenticated user to access the route only if he is the owner of the resource he is trying to access.
 */
export default async function ownerOnly({ to, next, nextMiddleware, stores }) {
    const { user, twofaccounts } = stores
    const { errorHandler } = stores
    const twofaccount = twofaccounts.getById(to.params.twofaccountId)

    if (twofaccount == undefined || twofaccount.is_borrowed) {
        let err = new Error('unauthorized')
        const responseOptions = { status: 403, statusText: 'unauthorized' }
        const response = new Response(null, responseOptions);
        err.response = response
        errorHandler.show(err)
    }
    else nextMiddleware()
}