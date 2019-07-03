<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Github client
        'githubClient_config' => [
            'baseUri'    => 'https://api.github.com/',
            'debug'      => true,
            'operations' => [
                'applyProtection' => [
                    'httpMethod'    => 'PUT',
                    'uri'           => '/repos/{organization}/{repository}/branches/master/protection',
                    'responseModel' => 'getResponse',
                    'parameters'    => [
                        'Accept'       => [
                            'type'      => 'string',
                            'location'  => 'header',                            
                        ],
                        'organization' => [
                            'type'      => 'string',
                            'location'  => 'uri',
                        ],
                        'repository' => [
                            'type'      => 'string',
                            'location'  => 'uri',
                        ],
                        'payload'  => [
                            'type'      => 'string',
                            'location'  => 'body',
                        ],
                    ],
                ]
            ],
            'models' => [
                'getResponse' => [
                    'type' => 'object',
                    'additionalProperties' => [
                        'location' => 'json'
                    ]
                ],
            ]
        ],
        'githubClient_options' => [
            'base_uri'    => 'https://api.github.com/',
            'headers'  => [
                'content-type' => 'application/json', 
            ],
            'auth' => ['proofek', 'aNiVpdxZcRupA3zLuuN4'],
            'debug'      => true,
        ],
    ],
];
