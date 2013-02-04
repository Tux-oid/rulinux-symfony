<?php
/**
 * Copyright (c) 2009 - 2012, Peter Vasilevsky
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the RL nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL PETER VASILEVSKY BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

namespace RL\MainBundle\Security\Firewall;

use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\HttpFoundation\Cookie;
use RL\MainBundle\Security\User\AnonymousUserProvider;
use RL\MainBundle\Security\User\RLUserInterface;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Observe;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * RL\MainBundle\Security\Firewall\AnonymousAuthenticationListener
 * RL anonymous authentication listener
 *
 * @Service("rl_main.anonymous.authentication.listener")
 * @Tag("monolog.logger", attributes = {"channel" = "security"})
 *
 * @author Ax-xa-xa
 * @license BSDL
*/
class AnonymousAuthenticationListener implements ListenerInterface
{
    /**
     * @var \RL\MainBundle\Security\User\AnonymousUserProvider
     */
    protected  $userProvider;
    /**
     * @var array
     */
    protected $options;
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    protected $context;
    /**
     * @var \Symfony\Component\HttpKernel\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @InjectParams({
     * "context" = @Inject("security.context"),
     * "userProvider" = @Inject("rl_main.anonymous.user.provider"),
     * "options" = @Inject("%rl_main.anonymous.defaults%"),
     * "doctrine" = @Inject("doctrine"),
     * "logger" = @Inject("logger")
     * })
     *
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $context
     * @param \RL\MainBundle\Security\User\AnonymousUserProvider $userProvider
     * @param $options
     * @param $doctrine
     * @param \Symfony\Component\HttpKernel\Log\LoggerInterface $logger
     */
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
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event A GetResponseEvent instance
     */
    public function handle(GetResponseEvent $event)
    {
        if (null !== $this->context->getToken()) {
            return;
        }

        $request = $event->getRequest();
        $request->getSession()->set('options', $this->options);

        $value = $request->cookies->get($this->options['cookie']);
        if (isset($value)) {
            list($identity, $attributes) = unserialize(base64_decode($value));
        } else {
            $identity = $request->getSession()->getId();
            $attributes = array();
        }
        $user = $this->userProvider->loadUser($identity, $attributes);
        $this->context->setToken(new AnonymousToken($this->options['key'], $user, $user->getRoles()));
        if (null !== $this->logger) {
            $this->logger->info(sprintf('Populated SecurityContext with an anonymous Token'));
        }
    }

    /**
     * Save user
     *
     * @Observe("kernel.response")
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     */
    public function saveUser(FilterResponseEvent $event)
    {

        $response = $event->getResponse();
        $request = $event->getRequest();
        $options = $request->getSession()->get('options');
        $token = $this->context->getToken();
        if ($token instanceof AnonymousToken) {
            $user = $token->getUser();
            if ($user instanceof RLUserInterface) {
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
