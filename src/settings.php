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

        'githubClient_options' => [
            'base_uri'    => 'https://api.github.com/',
            'headers'  => [
                'content-type'  => 'application/json',
                'Authorization' => 'token1 ' . getenv('GITHUB_TOKEN')
            ],
            'debug'      => false,
        ],

        'github' => [
            'secret' => getenv('GITHUB_SECRET')
        ]
    ],
];
