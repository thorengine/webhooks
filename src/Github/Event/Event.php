<?php
namespace WebhooksApi\Github\Event;

use Psr\Container\ContainerInterface;
use WebhooksApi\Github\Entity\Organization as OrganizationEntity;
use WebhooksApi\Github\Entity\Repository as RepositoryEntity;


class Event
{
    private $container;

	private $action;

	private $type;

	private $repository;

	private $organization;

    public function __construct(array $event, ContainerInterface $container)
    {
        if (isset($event['action'])) {
        	$this->action = $event['action'];
        }

        if (isset($event['repository'])) {
        	$this->repository = new RepositoryEntity($event['repository']);
        }

        if (isset($event['organization'])) {
        	$this->organization = new OrganizationEntity($event['organization']);
        }

        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getAction()
    {
    	return $this->action;
    }

    public function isCreated()
    {
    	return ($this->action == 'created');
    }

    public function getRepository()
    {
    	return $this->repository;
    }

    public function getOrganization()
    {
    	return $this->organization;
    }
}
