<?php
namespace WebhooksApi\Github\Factory;

use Psr\Container\ContainerInterface;
use WebhooksApi\Github\Exception\EventFactoryException;

/**
 * Exception when an HTTP error occurs (4xx or 5xx error)
 */
class EventFactory
{
    public static function create(array $event, ContainerInterface $container)
    {
    	reset($event);
    	if (next($event) !== false) {
    		$eventType = key($event);
    	}

    	$className = 'WebhooksApi\\Github\\Event\\' . str_replace('_', '', ucwords($eventType, " \t\r\n\f\v_"));
    	
    	if (!class_exists($className)) {
    		throw new EventFactoryException("Received unsupported event '{$eventType}'.");
    	}

    	return new $className($event, $container);
    }
}
