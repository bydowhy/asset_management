import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\AssetSpecificationController::sync
 * @see app/Http/Controllers/AssetSpecificationController.php:16
 * @route '/assets/{asset}/specifications'
 */
export const sync = (args: { asset: string | { id: string } } | [asset: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: sync.url(args, options),
    method: 'put',
})

sync.definition = {
    methods: ["put"],
    url: '/assets/{asset}/specifications',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\AssetSpecificationController::sync
 * @see app/Http/Controllers/AssetSpecificationController.php:16
 * @route '/assets/{asset}/specifications'
 */
sync.url = (args: { asset: string | { id: string } } | [asset: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return sync.definition.url
            .replace('{asset}', parsedArgs.asset.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssetSpecificationController::sync
 * @see app/Http/Controllers/AssetSpecificationController.php:16
 * @route '/assets/{asset}/specifications'
 */
sync.put = (args: { asset: string | { id: string } } | [asset: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: sync.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\AssetSpecificationController::sync
 * @see app/Http/Controllers/AssetSpecificationController.php:16
 * @route '/assets/{asset}/specifications'
 */
    const syncForm = (args: { asset: string | { id: string } } | [asset: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: sync.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AssetSpecificationController::sync
 * @see app/Http/Controllers/AssetSpecificationController.php:16
 * @route '/assets/{asset}/specifications'
 */
        syncForm.put = (args: { asset: string | { id: string } } | [asset: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: sync.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    sync.form = syncForm
const specifications = {
    sync: Object.assign(sync, sync),
}

export default specifications