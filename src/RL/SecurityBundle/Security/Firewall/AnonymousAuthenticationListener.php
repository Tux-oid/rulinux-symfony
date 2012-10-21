<?php
/**
 *@author Ax-xa-xa 
 */

namespace RL\SecurityBundle\Security\Firewall;

use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Cookie;
use RL\SecurityBundle\Security\User\AnonymousUserProvider;
use RL\SecurityBundle\Security\User\RLUserInterface;

/**
 * RL anonymous authentication listener
 */
class AnonymousAuthenticationListener implements ListenerInterface
{
	private $userProvider;
	private $options;
	private $context;
	private $logger;
	public function __construct(SecurityContextInterface $context, AnonymousUserProvider $userProvider, $options, $doctrine, LoggerInterface $logger = null)
	{
		$this->userProvider = $userProvider;
		$this->context = $context;
		$this->logger = $logger;
		$this->options = $options;
		$this->doctrine = &$doctrine;
	}
	/**
	 * Handles anonymous authentication.
	 *
	 * @param GetResponseEvent $event A GetResponseEvent instance
	 */
	public function handle(GetResponseEvent $event)
	{
		if(null !== $this->context->getToken())
		{
			return;
		}

		$request = $event->getRequest();
		$request->getSession()->set('options', $this->options);

		$value = $request->cookies->get($this->options['cookie']);
		if(isset($value))
		{
			list($identity, $attributes) = unserialize(base64_decode($value));
		}
		else
		{
			$identity = $request->getSession()->getId();
			$attributes = array();
		}
		$user = $this->userProvider->loadUser($identity, $attributes);
		$this->context->setToken(new AnonymousToken($this->options['key'], $user, $user->getRoles()));
		if(null !== $this->logger)
		{
			$this->logger->info(sprintf('Populated SecurityContext with an anonymous Token'));
		}
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
