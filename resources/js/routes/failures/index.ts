import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\FailureController::index
 * @see app/Http/Controllers/FailureController.php:21
 * @route '/failures'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/failures',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FailureController::index
 * @see app/Http/Controllers/FailureController.php:21
 * @route '/failures'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FailureController::index
 * @see app/Http/Controllers/FailureController.php:21
 * @route '/failures'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\FailureController::index
 * @see app/Http/Controllers/FailureController.php:21
 * @route '/failures'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\FailureController::index
 * @see app/Http/Controllers/FailureController.php:21
 * @route '/failures'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\FailureController::index
 * @see app/Http/Controllers/FailureController.php:21
 * @route '/failures'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\FailureController::index
 * @see app/Http/Controllers/FailureController.php:21
 * @route '/failures'
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
* @see \App\Http\Controllers\FailureController::create
 * @see app/Http/Controllers/FailureController.php:50
 * @route '/failures/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/failures/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FailureController::create
 * @see app/Http/Controllers/FailureController.php:50
 * @route '/failures/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FailureController::create
 * @see app/Http/Controllers/FailureController.php:50
 * @route '/failures/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\FailureController::create
 * @see app/Http/Controllers/FailureController.php:50
 * @route '/failures/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\FailureController::create
 * @see app/Http/Controllers/FailureController.php:50
 * @route '/failures/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\FailureController::create
 * @see app/Http/Controllers/FailureController.php:50
 * @route '/failures/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\FailureController::create
 * @see app/Http/Controllers/FailureController.php:50
 * @route '/failures/create'
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
* @see \App\Http\Controllers\FailureController::store
 * @see app/Http/Controllers/FailureController.php:58
 * @route '/failures'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/failures',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\FailureController::store
 * @see app/Http/Controllers/FailureController.php:58
 * @route '/failures'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FailureController::store
 * @see app/Http/Controllers/FailureController.php:58
 * @route '/failures'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\FailureController::store
 * @see app/Http/Controllers/FailureController.php:58
 * @route '/failures'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\FailureController::store
 * @see app/Http/Controllers/FailureController.php:58
 * @route '/failures'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\FailureController::show
 * @see app/Http/Controllers/FailureController.php:76
 * @route '/failures/{failure}'
 */
export const show = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/failures/{failure}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FailureController::show
 * @see app/Http/Controllers/FailureController.php:76
 * @route '/failures/{failure}'
 */
show.url = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { failure: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { failure: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    failure: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        failure: typeof args.failure === 'object'
                ? args.failure.id
                : args.failure,
                }

    return show.definition.url
            .replace('{failure}', parsedArgs.failure.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\FailureController::show
 * @see app/Http/Controllers/FailureController.php:76
 * @route '/failures/{failure}'
 */
show.get = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\FailureController::show
 * @see app/Http/Controllers/FailureController.php:76
 * @route '/failures/{failure}'
 */
show.head = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\FailureController::show
 * @see app/Http/Controllers/FailureController.php:76
 * @route '/failures/{failure}'
 */
    const showForm = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\FailureController::show
 * @see app/Http/Controllers/FailureController.php:76
 * @route '/failures/{failure}'
 */
        showForm.get = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\FailureController::show
 * @see app/Http/Controllers/FailureController.php:76
 * @route '/failures/{failure}'
 */
        showForm.head = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\FailureController::edit
 * @see app/Http/Controllers/FailureController.php:83
 * @route '/failures/{failure}/edit'
 */
export const edit = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/failures/{failure}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FailureController::edit
 * @see app/Http/Controllers/FailureController.php:83
 * @route '/failures/{failure}/edit'
 */
edit.url = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { failure: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { failure: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    failure: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        failure: typeof args.failure === 'object'
                ? args.failure.id
                : args.failure,
                }

    return edit.definition.url
            .replace('{failure}', parsedArgs.failure.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\FailureController::edit
 * @see app/Http/Controllers/FailureController.php:83
 * @route '/failures/{failure}/edit'
 */
edit.get = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\FailureController::edit
 * @see app/Http/Controllers/FailureController.php:83
 * @route '/failures/{failure}/edit'
 */
edit.head = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\FailureController::edit
 * @see app/Http/Controllers/FailureController.php:83
 * @route '/failures/{failure}/edit'
 */
    const editForm = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\FailureController::edit
 * @see app/Http/Controllers/FailureController.php:83
 * @route '/failures/{failure}/edit'
 */
        editForm.get = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\FailureController::edit
 * @see app/Http/Controllers/FailureController.php:83
 * @route '/failures/{failure}/edit'
 */
        editForm.head = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\FailureController::update
 * @see app/Http/Controllers/FailureController.php:92
 * @route '/failures/{failure}'
 */
export const update = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/failures/{failure}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\FailureController::update
 * @see app/Http/Controllers/FailureController.php:92
 * @route '/failures/{failure}'
 */
update.url = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { failure: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { failure: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    failure: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        failure: typeof args.failure === 'object'
                ? args.failure.id
                : args.failure,
                }

    return update.definition.url
            .replace('{failure}', parsedArgs.failure.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\FailureController::update
 * @see app/Http/Controllers/FailureController.php:92
 * @route '/failures/{failure}'
 */
update.put = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\FailureController::update
 * @see app/Http/Controllers/FailureController.php:92
 * @route '/failures/{failure}'
 */
update.patch = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\FailureController::update
 * @see app/Http/Controllers/FailureController.php:92
 * @route '/failures/{failure}'
 */
    const updateForm = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\FailureController::update
 * @see app/Http/Controllers/FailureController.php:92
 * @route '/failures/{failure}'
 */
        updateForm.put = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\FailureController::update
 * @see app/Http/Controllers/FailureController.php:92
 * @route '/failures/{failure}'
 */
        updateForm.patch = (args: { failure: string | { id: string } } | [failure: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\FailureController::destroy
 * @see app/Http/Controllers/FailureController.php:0
 * @route '/failures/{failure}'
 */
export const destroy = (args: { failure: string | number } | [failure: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/failures/{failure}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\FailureController::destroy
 * @see app/Http/Controllers/FailureController.php:0
 * @route '/failures/{failure}'
 */
destroy.url = (args: { failure: string | number } | [failure: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { failure: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    failure: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        failure: args.failure,
                }

    return destroy.definition.url
            .replace('{failure}', parsedArgs.failure.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\FailureController::destroy
 * @see app/Http/Controllers/FailureController.php:0
 * @route '/failures/{failure}'
 */
destroy.delete = (args: { failure: string | number } | [failure: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\FailureController::destroy
 * @see app/Http/Controllers/FailureController.php:0
 * @route '/failures/{failure}'
 */
    const destroyForm = (args: { failure: string | number } | [failure: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\FailureController::destroy
 * @see app/Http/Controllers/FailureController.php:0
 * @route '/failures/{failure}'
 */
        destroyForm.delete = (args: { failure: string | number } | [failure: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const failures = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default failures