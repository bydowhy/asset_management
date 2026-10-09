import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import definitions from './definitions'
/**
* @see \App\Http\Controllers\AssetTypeController::index
 * @see app/Http/Controllers/AssetTypeController.php:17
 * @route '/asset-types'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/asset-types',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AssetTypeController::index
 * @see app/Http/Controllers/AssetTypeController.php:17
 * @route '/asset-types'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssetTypeController::index
 * @see app/Http/Controllers/AssetTypeController.php:17
 * @route '/asset-types'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AssetTypeController::index
 * @see app/Http/Controllers/AssetTypeController.php:17
 * @route '/asset-types'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AssetTypeController::index
 * @see app/Http/Controllers/AssetTypeController.php:17
 * @route '/asset-types'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AssetTypeController::index
 * @see app/Http/Controllers/AssetTypeController.php:17
 * @route '/asset-types'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AssetTypeController::index
 * @see app/Http/Controllers/AssetTypeController.php:17
 * @route '/asset-types'
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
* @see \App\Http\Controllers\AssetTypeController::create
 * @see app/Http/Controllers/AssetTypeController.php:28
 * @route '/asset-types/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/asset-types/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AssetTypeController::create
 * @see app/Http/Controllers/AssetTypeController.php:28
 * @route '/asset-types/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssetTypeController::create
 * @see app/Http/Controllers/AssetTypeController.php:28
 * @route '/asset-types/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AssetTypeController::create
 * @see app/Http/Controllers/AssetTypeController.php:28
 * @route '/asset-types/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AssetTypeController::create
 * @see app/Http/Controllers/AssetTypeController.php:28
 * @route '/asset-types/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AssetTypeController::create
 * @see app/Http/Controllers/AssetTypeController.php:28
 * @route '/asset-types/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AssetTypeController::create
 * @see app/Http/Controllers/AssetTypeController.php:28
 * @route '/asset-types/create'
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
* @see \App\Http\Controllers\AssetTypeController::store
 * @see app/Http/Controllers/AssetTypeController.php:33
 * @route '/asset-types'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/asset-types',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AssetTypeController::store
 * @see app/Http/Controllers/AssetTypeController.php:33
 * @route '/asset-types'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssetTypeController::store
 * @see app/Http/Controllers/AssetTypeController.php:33
 * @route '/asset-types'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AssetTypeController::store
 * @see app/Http/Controllers/AssetTypeController.php:33
 * @route '/asset-types'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AssetTypeController::store
 * @see app/Http/Controllers/AssetTypeController.php:33
 * @route '/asset-types'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\AssetTypeController::edit
 * @see app/Http/Controllers/AssetTypeController.php:52
 * @route '/asset-types/{asset_type}/edit'
 */
export const edit = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/asset-types/{asset_type}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AssetTypeController::edit
 * @see app/Http/Controllers/AssetTypeController.php:52
 * @route '/asset-types/{asset_type}/edit'
 */
edit.url = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { asset_type: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { asset_type: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    asset_type: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        asset_type: typeof args.asset_type === 'object'
                ? args.asset_type.id
                : args.asset_type,
                }

    return edit.definition.url
            .replace('{asset_type}', parsedArgs.asset_type.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssetTypeController::edit
 * @see app/Http/Controllers/AssetTypeController.php:52
 * @route '/asset-types/{asset_type}/edit'
 */
edit.get = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AssetTypeController::edit
 * @see app/Http/Controllers/AssetTypeController.php:52
 * @route '/asset-types/{asset_type}/edit'
 */
edit.head = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AssetTypeController::edit
 * @see app/Http/Controllers/AssetTypeController.php:52
 * @route '/asset-types/{asset_type}/edit'
 */
    const editForm = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AssetTypeController::edit
 * @see app/Http/Controllers/AssetTypeController.php:52
 * @route '/asset-types/{asset_type}/edit'
 */
        editForm.get = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AssetTypeController::edit
 * @see app/Http/Controllers/AssetTypeController.php:52
 * @route '/asset-types/{asset_type}/edit'
 */
        editForm.head = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\AssetTypeController::update
 * @see app/Http/Controllers/AssetTypeController.php:63
 * @route '/asset-types/{asset_type}'
 */
export const update = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/asset-types/{asset_type}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\AssetTypeController::update
 * @see app/Http/Controllers/AssetTypeController.php:63
 * @route '/asset-types/{asset_type}'
 */
update.url = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { asset_type: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { asset_type: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    asset_type: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        asset_type: typeof args.asset_type === 'object'
                ? args.asset_type.id
                : args.asset_type,
                }

    return update.definition.url
            .replace('{asset_type}', parsedArgs.asset_type.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssetTypeController::update
 * @see app/Http/Controllers/AssetTypeController.php:63
 * @route '/asset-types/{asset_type}'
 */
update.put = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\AssetTypeController::update
 * @see app/Http/Controllers/AssetTypeController.php:63
 * @route '/asset-types/{asset_type}'
 */
update.patch = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\AssetTypeController::update
 * @see app/Http/Controllers/AssetTypeController.php:63
 * @route '/asset-types/{asset_type}'
 */
    const updateForm = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AssetTypeController::update
 * @see app/Http/Controllers/AssetTypeController.php:63
 * @route '/asset-types/{asset_type}'
 */
        updateForm.put = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\AssetTypeController::update
 * @see app/Http/Controllers/AssetTypeController.php:63
 * @route '/asset-types/{asset_type}'
 */
        updateForm.patch = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\AssetTypeController::destroy
 * @see app/Http/Controllers/AssetTypeController.php:79
 * @route '/asset-types/{asset_type}'
 */
export const destroy = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/asset-types/{asset_type}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\AssetTypeController::destroy
 * @see app/Http/Controllers/AssetTypeController.php:79
 * @route '/asset-types/{asset_type}'
 */
destroy.url = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { asset_type: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { asset_type: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    asset_type: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        asset_type: typeof args.asset_type === 'object'
                ? args.asset_type.id
                : args.asset_type,
                }

    return destroy.definition.url
            .replace('{asset_type}', parsedArgs.asset_type.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssetTypeController::destroy
 * @see app/Http/Controllers/AssetTypeController.php:79
 * @route '/asset-types/{asset_type}'
 */
destroy.delete = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\AssetTypeController::destroy
 * @see app/Http/Controllers/AssetTypeController.php:79
 * @route '/asset-types/{asset_type}'
 */
    const destroyForm = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AssetTypeController::destroy
 * @see app/Http/Controllers/AssetTypeController.php:79
 * @route '/asset-types/{asset_type}'
 */
        destroyForm.delete = (args: { asset_type: string | { id: string } } | [asset_type: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const assetTypes = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
definitions: Object.assign(definitions, definitions),
}

export default assetTypes