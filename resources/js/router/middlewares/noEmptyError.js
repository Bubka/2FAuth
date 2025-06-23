export default function noEmptyError({ to, next, nextMiddleware, stores }) {
    const { errorHandler } = stores

    if (errorHandler.lastError == null && ! to.query.err) {
        // return to home if no err object is set to prevent an empty error message
        next({ name: 'accounts' });
    }
    else nextMiddleware()
}