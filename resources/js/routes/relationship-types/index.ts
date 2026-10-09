import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\RelationshipTypeController::index
 * @see app/Http/Controllers/RelationshipTypeController.php:17
 * @route '/relationship-types'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/relationship-types',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RelationshipTypeController::index
 * @see app/Http/Controllers/RelationshipTypeController.php:17
 * @route '/relationship-types'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\RelationshipTypeController::index
 * @see app/Http/Controllers/RelationshipTypeController.php:17
 * @route '/relationship-types'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\RelationshipTypeController::index
 * @see app/Http/Controllers/RelationshipTypeController.php:17
 * @route '/relationship-types'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\RelationshipTypeController::index
 * @see app/Http/Controllers/RelationshipTypeController.php:17
 * @route '/relationship-types'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\RelationshipTypeController::index
 * @see app/Http/Controllers/RelationshipTypeController.php:17
 * @route '/relationship-types'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\RelationshipTypeController::index
 * @see app/Http/Controllers/RelationshipTypeController.php:17
 * @route '/relationship-types'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\RelationshipTypeController::create
 * @see app/Http/Controllers/RelationshipTypeController.php:28
 * @route '/relationship-types/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/relationship-types/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RelationshipTypeController::create
 * @see app/Http/Controllers/RelationshipTypeController.php:28
 * @route '/relationship-types/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\RelationshipTypeController::create
 * @see app/Http/Controllers/RelationshipTypeController.php:28
 * @route '/relationship-types/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\RelationshipTypeController::create
 * @see app/Http/Controllers/RelationshipTypeController.php:28
 * @route '/relationship-types/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\RelationshipTypeController::create
 * @see app/Http/Controllers/RelationshipTypeController.php:28
 * @route '/relationship-types/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\RelationshipTypeController::create
 * @see app/Http/Controllers/RelationshipTypeController.php:28
 * @route '/relationship-types/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\RelationshipTypeController::create
 * @see app/Http/Controllers/RelationshipTypeController.php:28
 * @route '/relationship-types/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\RelationshipTypeController::store
 * @see app/Http/Controllers/RelationshipTypeController.php:33
 * @route '/relationship-types'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/relationship-types',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\RelationshipTypeController::store
 * @see app/Http/Controllers/RelationshipTypeController.php:33
 * @route '/relationship-types'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\RelationshipTypeController::store
 * @see app/Http/Controllers/RelationshipTypeController.php:33
 * @route '/relationship-types'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\RelationshipTypeController::store
 * @see app/Http/Controllers/RelationshipTypeController.php:33
 * @route '/relationship-types'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RelationshipTypeController::store
 * @see app/Http/Controllers/RelationshipTypeController.php:33
 * @route '/relationship-types'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\RelationshipTypeController::edit
 * @see app/Http/Controllers/RelationshipTypeController.php:52
 * @route '/relationship-types/{relationship_type}/edit'
 */
export const edit = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/relationship-types/{relationship_type}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RelationshipTypeController::edit
 * @see app/Http/Controllers/RelationshipTypeController.php:52
 * @route '/relationship-types/{relationship_type}/edit'
 */
edit.url = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { relationship_type: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { relationship_type: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    relationship_type: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        relationship_type: typeof args.relationship_type === 'object'
                ? args.relationship_type.id
                : args.relationship_type,
                }

    return edit.definition.url
            .replace('{relationship_type}', parsedArgs.relationship_type.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RelationshipTypeController::edit
 * @see app/Http/Controllers/RelationshipTypeController.php:52
 * @route '/relationship-types/{relationship_type}/edit'
 */
edit.get = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\RelationshipTypeController::edit
 * @see app/Http/Controllers/RelationshipTypeController.php:52
 * @route '/relationship-types/{relationship_type}/edit'
 */
edit.head = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\RelationshipTypeController::edit
 * @see app/Http/Controllers/RelationshipTypeController.php:52
 * @route '/relationship-types/{relationship_type}/edit'
 */
    const editForm = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\RelationshipTypeController::edit
 * @see app/Http/Controllers/RelationshipTypeController.php:52
 * @route '/relationship-types/{relationship_type}/edit'
 */
        editForm.get = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\RelationshipTypeController::edit
 * @see app/Http/Controllers/RelationshipTypeController.php:52
 * @route '/relationship-types/{relationship_type}/edit'
 */
        editForm.head = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\RelationshipTypeController::update
 * @see app/Http/Controllers/RelationshipTypeController.php:59
 * @route '/relationship-types/{relationship_type}'
 */
export const update = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/relationship-types/{relationship_type}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\RelationshipTypeController::update
 * @see app/Http/Controllers/RelationshipTypeController.php:59
 * @route '/relationship-types/{relationship_type}'
 */
update.url = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { relationship_type: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { relationship_type: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    relationship_type: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        relationship_type: typeof args.relationship_type === 'object'
                ? args.relationship_type.id
                : args.relationship_type,
                }

    return update.definition.url
            .replace('{relationship_type}', parsedArgs.relationship_type.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RelationshipTypeController::update
 * @see app/Http/Controllers/RelationshipTypeController.php:59
 * @route '/relationship-types/{relationship_type}'
 */
update.put = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\RelationshipTypeController::update
 * @see app/Http/Controllers/RelationshipTypeController.php:59
 * @route '/relationship-types/{relationship_type}'
 */
update.patch = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\RelationshipTypeController::update
 * @see app/Http/Controllers/RelationshipTypeController.php:59
 * @route '/relationship-types/{relationship_type}'
 */
    const updateForm = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RelationshipTypeController::update
 * @see app/Http/Controllers/RelationshipTypeController.php:59
 * @route '/relationship-types/{relationship_type}'
 */
        updateForm.put = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\RelationshipTypeController::update
 * @see app/Http/Controllers/RelationshipTypeController.php:59
 * @route '/relationship-types/{relationship_type}'
 */
        updateForm.patch = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\RelationshipTypeController::destroy
 * @see app/Http/Controllers/RelationshipTypeController.php:75
 * @route '/relationship-types/{relationship_type}'
 */
export const destroy = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/relationship-types/{relationship_type}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\RelationshipTypeController::destroy
 * @see app/Http/Controllers/RelationshipTypeController.php:75
 * @route '/relationship-types/{relationship_type}'
 */
destroy.url = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { relationship_type: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { relationship_type: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    relationship_type: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        relationship_type: typeof args.relationship_type === 'object'
                ? args.relationship_type.id
                : args.relationship_type,
                }

    return destroy.definition.url
            .replace('{relationship_type}', parsedArgs.relationship_type.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RelationshipTypeController::destroy
 * @see app/Http/Controllers/RelationshipTypeController.php:75
 * @route '/relationship-types/{relationship_type}'
 */
destroy.delete = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\RelationshipTypeController::destroy
 * @see app/Http/Controllers/RelationshipTypeController.php:75
 * @route '/relationship-types/{relationship_type}'
 */
    const destroyForm = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\RelationshipTypeController::destroy
 * @see app/Http/Controllers/RelationshipTypeController.php:75
 * @route '/relationship-types/{relationship_type}'
 */
        destroyForm.delete = (args: { relationship_type: string | { id: string } } | [relationship_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const relationshipTypes = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default relationshipTypes