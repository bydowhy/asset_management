import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\AssetTypeDefinitionController::sync
 * @see app/Http/Controllers/AssetTypeDefinitionController.php:18
 * @route '/asset-types/{assetType}/definitions'
 */
export const sync = (args: { assetType: string | { id: string } } | [assetType: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: sync.url(args, options),
    method: 'put',
})

sync.definition = {
    methods: ["put"],
    url: '/asset-types/{assetType}/definitions',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\AssetTypeDefinitionController::sync
 * @see app/Http/Controllers/AssetTypeDefinitionController.php:18
 * @route '/asset-types/{assetType}/definitions'
 */
sync.url = (args: { assetType: string | { id: string } } | [assetType: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { assetType: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { assetType: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    assetType: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        assetType: typeof args.assetType === 'object'
                ? args.assetType.id
                : args.assetType,
                }

    return sync.definition.url
            .replace('{assetType}', parsedArgs.assetType.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssetTypeDefinitionController::sync
 * @see app/Http/Controllers/AssetTypeDefinitionController.php:18
 * @route '/asset-types/{assetType}/definitions'
 */
sync.put = (args: { assetType: string | { id: string } } | [assetType: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: sync.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\AssetTypeDefinitionController::sync
 * @see app/Http/Controllers/AssetTypeDefinitionController.php:18
 * @route '/asset-types/{assetType}/definitions'
 */
    const syncForm = (args: { assetType: string | { id: string } } | [assetType: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: sync.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AssetTypeDefinitionController::sync
 * @see app/Http/Controllers/AssetTypeDefinitionController.php:18
 * @route '/asset-types/{assetType}/definitions'
 */
        syncForm.put = (args: { assetType: string | { id: string } } | [assetType: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: sync.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    sync.form = syncForm
const definitions = {
    sync: Object.assign(sync, sync),
}

export default definitions