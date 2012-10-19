<?php
/**
 * @author Ax-xa-xa 
 */

namespace RL\SecurityBundle\Security\User;

class AnonymousUserProvider implements AnonymousUserProviderInterface
{
	protected $context;
	protected $defaults;
	protected $userclass;
	public function __construct($userclass, $defaults)
	{
		$this->defaults = $defaults;
		$this->userclass = $userclass;
	}
	public function loadUser($identity, $attributes, &$doctrine)
	{
		$attributes['username'] = $this->defaults['username'];
		$attributes['enabled'] = $this->defaults['enabled'];
		foreach($this->defaults as $key => $value)
		{
			if($key == 'username' || $key == 'enabled' || (!array_key_exists($key, $attributes)))
			{
				$attributes[$key] = $this->defaults[$key];
			}
		}
		return new $this->userclass($identity, $attributes, $doctrine);
	}
}
