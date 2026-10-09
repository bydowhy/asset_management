import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\AssetRelationshipController::end
 * @see app/Http/Controllers/AssetRelationshipController.php:26
 * @route '/relationships/{relationship}/end'
 */
export const end = (args: { relationship: string | { id: string } } | [relationship: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: end.url(args, options),
    method: 'patch',
})

end.definition = {
    methods: ["patch"],
    url: '/relationships/{relationship}/end',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\AssetRelationshipController::end
 * @see app/Http/Controllers/AssetRelationshipController.php:26
 * @route '/relationships/{relationship}/end'
 */
end.url = (args: { relationship: string | { id: string } } | [relationship: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { relationship: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { relationship: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    relationship: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        relationship: typeof args.relationship === 'object'
                ? args.relationship.id
                : args.relationship,
                }

    return end.definition.url
            .replace('{relationship}', parsedArgs.relationship.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssetRelationshipController::end
 * @see app/Http/Controllers/AssetRelationshipController.php:26
 * @route '/relationships/{relationship}/end'
 */
end.patch = (args: { relationship: string | { id: string } } | [relationship: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: end.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\AssetRelationshipController::end
 * @see app/Http/Controllers/AssetRelationshipController.php:26
 * @route '/relationships/{relationship}/end'
 */
    const endForm = (args: { relationship: string | { id: string } } | [relationship: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: end.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AssetRelationshipController::end
 * @see app/Http/Controllers/AssetRelationshipController.php:26
 * @route '/relationships/{relationship}/end'
 */
        endForm.patch = (args: { relationship: string | { id: string } } | [relationship: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: end.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    end.form = endForm
/**
* @see \App\Http\Controllers\AssetRelationshipController::replace
 * @see app/Http/Controllers/AssetRelationshipController.php:33
 * @route '/relationships/{relationship}/replace'
 */
export const replace = (args: { relationship: string | { id: string } } | [relationship: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: replace.url(args, options),
    method: 'patch',
})

replace.definition = {
    methods: ["patch"],
    url: '/relationships/{relationship}/replace',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\AssetRelationshipController::replace
 * @see app/Http/Controllers/AssetRelationshipController.php:33
 * @route '/relationships/{relationship}/replace'
 */
replace.url = (args: { relationship: string | { id: string } } | [relationship: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { relationship: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { relationship: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    relationship: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        relationship: typeof args.relationship === 'object'
                ? args.relationship.id
                : args.relationship,
                }

    return replace.definition.url
            .replace('{relationship}', parsedArgs.relationship.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssetRelationshipController::replace
 * @see app/Http/Controllers/AssetRelationshipController.php:33
 * @route '/relationships/{relationship}/replace'
 */
replace.patch = (args: { relationship: string | { id: string } } | [relationship: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: replace.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\AssetRelationshipController::replace
 * @see app/Http/Controllers/AssetRelationshipController.php:33
 * @route '/relationships/{relationship}/replace'
 */
    const replaceForm = (args: { relationship: string | { id: string } } | [relationship: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: replace.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AssetRelationshipController::replace
 * @see app/Http/Controllers/AssetRelationshipController.php:33
 * @route '/relationships/{relationship}/replace'
 */
        replaceForm.patch = (args: { relationship: string | { id: string } } | [relationship: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: replace.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    replace.form = replaceForm
const relationships = {
    end: Object.assign(end, end),
replace: Object.assign(replace, replace),
}

export default relationships