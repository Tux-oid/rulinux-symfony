<?php
/**
 * @author Ax-xa-xa 
 */

namespace RL\SecurityBundle\Security\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class AnonymousUserProvider implements AnonymousUserProviderInterface
{
	protected $context;
	protected $defaults;
	protected $userclass;
	public function __construct(SecurityContextInterface $context, $userclass, $defaults)
	{
		//echo "userclass='$userclass', $defaults="; print_r($defaults); echo "\n";
		$this->defaults = $defaults;
		$this->context = $context;
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
	public function saveUser(FilterResponseEvent $event)
	{

		$response = $event->getResponse();
		$request = $event->getRequest();
		$options = $request->getSession()->get('options');
		$token = $this->context->getToken();
		if($token instanceof AnonymousToken)
		{
			$user = $token->getUser();
			if($user instanceof RLUserInterface)
			{
				$value = serialize(array($user->getIdentity(), $user->getAttributes()));
				$cookie = new Cookie(
						$options['cookie'],
						base64_encode($value),
						time() + $options['lifetime'],
						$options['path'],
						$options['domain'],
						$options['secure'],
						$options['httponly']);
				$response->headers->setCookie($cookie);
			}
		}
	}
}
