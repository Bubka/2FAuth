export default function noEmptyError({ to, next, nextMiddleware, stores }) {
    const { notify } = stores

    if (notify.err == null && ! to.query.err) {
        // return to home if no err object is set to prevent an empty error message
        next({ name: 'accounts' });
    }
    else nextMiddleware()
}