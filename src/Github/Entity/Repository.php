<?php
namespace WebhooksApi\Github\Entity;

class Repository
{
	private $name;

    private $fullName;

	public function __construct(array $repository)
    {
        if (isset($repository['name'])) {
            $this->name = $repository['name'];
        }

        if (isset($repository['full_name'])) {
        	$this->fullName = $repository['full_name'];
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFullName()
    {
    	return $this->fullName;
    }
}
