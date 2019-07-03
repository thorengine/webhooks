<?php

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use Slim\App;
use WebhooksApi\Github\Client\GithubClient;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    // guzzle
    $container['httpClient'] = function ($c) {
        return new Client($c->get('settings')['githubClient_options']);
    };
};
