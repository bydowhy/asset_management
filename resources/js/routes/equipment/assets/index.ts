import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\EquipmentAssetController::store
 * @see app/Http/Controllers/EquipmentAssetController.php:21
 * @route '/equipment/{equipment}/assets'
 */
export const store = (args: { equipment: string | { id: string } } | [equipment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/equipment/{equipment}/assets',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\EquipmentAssetController::store
 * @see app/Http/Controllers/EquipmentAssetController.php:21
 * @route '/equipment/{equipment}/assets'
 */
store.url = (args: { equipment: string | { id: string } } | [equipment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { equipment: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { equipment: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    equipment: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        equipment: typeof args.equipment === 'object'
                ? args.equipment.id
                : args.equipment,
                }

    return store.definition.url
            .replace('{equipment}', parsedArgs.equipment.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EquipmentAssetController::store
 * @see app/Http/Controllers/EquipmentAssetController.php:21
 * @route '/equipment/{equipment}/assets'
 */
store.post = (args: { equipment: string | { id: string } } | [equipment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\EquipmentAssetController::store
 * @see app/Http/Controllers/EquipmentAssetController.php:21
 * @route '/equipment/{equipment}/assets'
 */
    const storeForm = (args: { equipment: string | { id: string } } | [equipment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\EquipmentAssetController::store
 * @see app/Http/Controllers/EquipmentAssetController.php:21
 * @route '/equipment/{equipment}/assets'
 */
        storeForm.post = (args: { equipment: string | { id: string } } | [equipment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
const assets = {
    store: Object.assign(store, store),
}

export default assets