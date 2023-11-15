export default function middlewarePipeline(context, middlewares, index) {
    const nextMiddleware = middlewares[index];
    if (!nextMiddleware) {
        return context.next;
    }
    return () => {
        nextMiddleware({
            ...context,
            nextMiddleware: middlewarePipeline(context, middlewares, index + 1),
        });
    };
}