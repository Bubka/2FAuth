export default async function middlewarePipeline(context, middlewares) {
    for (const middleware of middlewares) {
        const result = await middleware(context)

        if (result !== true && result !== undefined) {
            return result
        }
    }

    return true
}