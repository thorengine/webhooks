<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use WebhooksApi\Github\Event\Repository as RepositoryEvent;
use WebhooksApi\Github\Factory\EventFactory;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->post('/payload', function (Request $request, Response $response, array $args) use ($container) {

    	$httpClient = $container->get('httpClient');
		$requestBody = $request->getParsedBody();

		$event = EventFactory::create($requestBody, $container);		
		$container->get('logger')->info("Triggered payload! '{$event->getAction()}' {$event->getRepository()->getFullName()}");
    	
    	try {
    	
    		$event->process($container);	
	     
	    } catch (GuzzleHttp\Exception\ClientException $e) {
            if ($e->hasResponse()) {
                $container->get('logger')->info(GuzzleHttp\Exception\ClientException::getResponseBodySummary($e->getResponse()));
                $code = $e->getResponse()->getStatusCode();
                $container->get('logger')->info("CODE: {$code}");
            }
            error_log("EXCEPTION: " . print_r($e->getMessage(),1));
        } catch (\Exception $e) {
            error_log("EXCEPTION: " . print_r($e,1));
        }
	});
};
