import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\AssetRelationshipController::store
 * @see app/Http/Controllers/AssetRelationshipController.php:19
 * @route '/assets/{asset}/relationships'
 */
export const store = (args: { asset: string | { id: string } } | [asset: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/assets/{asset}/relationships',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AssetRelationshipController::store
 * @see app/Http/Controllers/AssetRelationshipController.php:19
 * @route '/assets/{asset}/relationships'
 */
store.url = (args: { asset: string | { id: string } } | [asset: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { asset: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { asset: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    asset: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        asset: typeof args.asset === 'object'
                ? args.asset.id
                : args.asset,
                }

    return store.definition.url
            .replace('{asset}', parsedArgs.asset.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssetRelationshipController::store
 * @see app/Http/Controllers/AssetRelationshipController.php:19
 * @route '/assets/{asset}/relationships'
 */
store.post = (args: { asset: string | { id: string } } | [asset: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AssetRelationshipController::store
 * @see app/Http/Controllers/AssetRelationshipController.php:19
 * @route '/assets/{asset}/relationships'
 */
    const storeForm = (args: { asset: string | { id: string } } | [asset: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AssetRelationshipController::store
 * @see app/Http/Controllers/AssetRelationshipController.php:19
 * @route '/assets/{asset}/relationships'
 */
        storeForm.post = (args: { asset: string | { id: string } } | [asset: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
const relationships = {
    store: Object.assign(store, store),
}

export default relationships