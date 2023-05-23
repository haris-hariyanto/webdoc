<?php

return [
    // Optional
    'controllerNamespace' => 'App\Http\Controllers\Admin', // App\Http\Controllers\Admin
    'controllerPath' => 'Http/Controllers/Admin', // Http/Controllers/Admin

    // Required
    'modelName' => 'Keyword', // Product,
    'modelInstance' => 'keyword', // product
    'modelInstancePlural' => 'keywords', // products
    'resourceName' => 'Keywords', // Products
    'resourceNameSingular' => 'Keyword', // Product
    'viewPath' => 'admin/keywords', // admin/products
    'route' => 'admin.keywords', // admin.products

    // Data to return from JSON
    'data' => [
        [
            'JSONName' => 'keyword',
            'databaseColumn' => 'keyword',
            'tableName' => 'Keyword',
        ],
        [
            'JSONName' => 'status',
            'databaseColumn' => 'status',
            'tableName' => 'Status',
        ],
        [
            'JSONName' => 'priority',
            'databaseColumn' => 'priority',
            'tableName' => 'Priority',
        ],
    ],

    // Fields
    'fields' => [
        [
            'name' => 'keyword',
            'label' => 'Keyword',
            'type' => 'text',
            'create_validation' => ['required', 'string'],
            'edit_validation' => ['required', 'string'],
        ],
    ],
];