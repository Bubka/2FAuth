export default function noEmptyError({ to, stores }) {
    const { errorHandler } = stores

    if (errorHandler.lastError == null && ! to.query.err) {
        // return to home if no err object is set to prevent an empty error message
        return { name: 'accounts' }
    }

    return true
}