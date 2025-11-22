<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Fatahi API',
            ],
            'routes' => [
                'api' => 'api/documentation',
            ],
            'paths' => [
                'use_absolute_path' => true,
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'format_to_use_for_docs' => 'json',
                'annotations' => [
                    base_path('app'),
                ],
            ],
            'security' => [
                [
                    'bearerAuth' => []
                ]
            ],
            'generate_always' => true,
            'swagger_version' => '3.0',
        ],
    ],
];
