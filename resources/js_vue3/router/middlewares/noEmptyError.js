export default function noEmptyError({ to, next }) {
    next()
    if (to.params.err == undefined) {
        // return to home if no err object is provided to prevent an empty error message
        next({ name: 'accounts' });
    }
    else next()
}