<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'host' => env('L5_SWAGGER_CONST_HOST', 'http://127.0.0.1:8000'),
                'basePath' => env('L5_SWAGGER_CONST_BASEPATH', '/api'),
            ],
            'info' => [
                'title' => env('L5_SWAGGER_CONST_TITLE', 'Tu Título de API'),
                'description' => env('L5_SWAGGER_CONST_DESCRIPTION', 'Una descripción de tu API.'),
                'version' => env('L5_SWAGGER_CONST_VERSION', '1.0.0'),
                'contact' => [
                    'email' => 'tu-correo@ejemplo.com',
                ],
            ],
            'paths' => [
                'annotations' => [
                    base_path('app'), // o la ruta a tus controladores
                ],
                'docs' => storage_path('api-docs'),
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'excludes' => [],
                'base' => base_path(), // Esta es la línea que faltaba
            ],
        ],
    ],
    'defaults' => [
        'routes' => [
            'api' => 'api.php',
        ],
    ],
    'diff' => [
        'paths' => [
            'storage' => storage_path('swagger-diff'),
        ],
    ],
];
