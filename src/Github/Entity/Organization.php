<?php
namespace WebhooksApi\Github\Entity;

class Organization
{
	private $login;

	public function __construct(array $organization)
    {
        if (isset($organization['login'])) {
        	$this->login = $organization['login'];
        }
    }

    public function getLogin()
    {
    	return $this->login;
    }
}
