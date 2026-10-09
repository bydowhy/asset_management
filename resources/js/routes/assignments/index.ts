import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\EquipmentAssetController::remove
 * @see app/Http/Controllers/EquipmentAssetController.php:37
 * @route '/assignments/{assignment}/remove'
 */
export const remove = (args: { assignment: string | { id: string } } | [assignment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: remove.url(args, options),
    method: 'patch',
})

remove.definition = {
    methods: ["patch"],
    url: '/assignments/{assignment}/remove',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\EquipmentAssetController::remove
 * @see app/Http/Controllers/EquipmentAssetController.php:37
 * @route '/assignments/{assignment}/remove'
 */
remove.url = (args: { assignment: string | { id: string } } | [assignment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { assignment: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { assignment: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    assignment: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        assignment: typeof args.assignment === 'object'
                ? args.assignment.id
                : args.assignment,
                }

    return remove.definition.url
            .replace('{assignment}', parsedArgs.assignment.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EquipmentAssetController::remove
 * @see app/Http/Controllers/EquipmentAssetController.php:37
 * @route '/assignments/{assignment}/remove'
 */
remove.patch = (args: { assignment: string | { id: string } } | [assignment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: remove.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\EquipmentAssetController::remove
 * @see app/Http/Controllers/EquipmentAssetController.php:37
 * @route '/assignments/{assignment}/remove'
 */
    const removeForm = (args: { assignment: string | { id: string } } | [assignment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: remove.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\EquipmentAssetController::remove
 * @see app/Http/Controllers/EquipmentAssetController.php:37
 * @route '/assignments/{assignment}/remove'
 */
        removeForm.patch = (args: { assignment: string | { id: string } } | [assignment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: remove.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    remove.form = removeForm
/**
* @see \App\Http\Controllers\EquipmentAssetController::replace
 * @see app/Http/Controllers/EquipmentAssetController.php:59
 * @route '/assignments/{assignment}/replace'
 */
export const replace = (args: { assignment: string | { id: string } } | [assignment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: replace.url(args, options),
    method: 'patch',
})

replace.definition = {
    methods: ["patch"],
    url: '/assignments/{assignment}/replace',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\EquipmentAssetController::replace
 * @see app/Http/Controllers/EquipmentAssetController.php:59
 * @route '/assignments/{assignment}/replace'
 */
replace.url = (args: { assignment: string | { id: string } } | [assignment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { assignment: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { assignment: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    assignment: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        assignment: typeof args.assignment === 'object'
                ? args.assignment.id
                : args.assignment,
                }

    return replace.definition.url
            .replace('{assignment}', parsedArgs.assignment.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EquipmentAssetController::replace
 * @see app/Http/Controllers/EquipmentAssetController.php:59
 * @route '/assignments/{assignment}/replace'
 */
replace.patch = (args: { assignment: string | { id: string } } | [assignment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: replace.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\EquipmentAssetController::replace
 * @see app/Http/Controllers/EquipmentAssetController.php:59
 * @route '/assignments/{assignment}/replace'
 */
    const replaceForm = (args: { assignment: string | { id: string } } | [assignment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: replace.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\EquipmentAssetController::replace
 * @see app/Http/Controllers/EquipmentAssetController.php:59
 * @route '/assignments/{assignment}/replace'
 */
        replaceForm.patch = (args: { assignment: string | { id: string } } | [assignment: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: replace.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    replace.form = replaceForm
const assignments = {
    remove: Object.assign(remove, remove),
replace: Object.assign(replace, replace),
}

export default assignments