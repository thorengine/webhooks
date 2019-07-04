<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use WebhooksApi\Github\Event\Repository as RepositoryEvent;
use WebhooksApi\Github\Factory\EventFactory;

return function (App $app) {
    $container = $app->getContainer();

    $app->post('/payload', function (Request $request, Response $response, array $args) use ($container) {

        $xHubSignature = current($request->getHeader('X-Hub-Signature'));
        $githubSecret = $container->get('settings')['github']['secret'];
        $body = (string)$request->getBody();

        if ('sha1=' . hash_hmac('sha1', $body, $githubSecret) !== $xHubSignature) {
            return new Response(401);
        }

    	$httpClient = $container->get('httpClient');
	    $requestBody = $request->getParsedBody();	

		$event = EventFactory::create($requestBody, $container);		
    	
    	try {
    	
    		$event->process($container);	
	     
        } catch (\Exception $e) {

            return new Response(500);
        }
	});
};
