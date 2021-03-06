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

namespace RL\MainBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use RL\MainBundle\AuthenticationEvents;
use RL\MainBundle\Event\AnonymousLoginEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * RL\MainBundle\Security\User\AnonymousUserProvider
 *
 * @Service("rl_main.anonymous.user.provider")
 *
 * @author Ax-xa-xa
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class AnonymousUserProvider implements UserProviderInterface
{
    /**
     * @var array
     */
    protected $defaults;
    /**
     * @var \RL\MainBundle\Security\User\RLUserInterface
     */
    protected $userClass;
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var \Symfony\Component\DependencyInjection\Container;
     */
    protected $container;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * Constructor
     *
     * @InjectParams({
     * "userClass" = @Inject("%rl_main.anonymous.class%"),
     * "defaults" = @Inject("%rl_main.anonymous.defaults%"),
     * "doctrine" = @Inject("doctrine"),
     * "eventDispatcher" = @Inject("event_dispatcher"),
     * "container" = @Inject("service_container")
     * })
     *
     * @param $userClass
     * @param array $defaults
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct($userClass, array $defaults, Registry $doctrine, EventDispatcher $eventDispatcher, Container $container)
    {
        $this->defaults = $defaults;
        $this->userClass = $userClass;
        $this->doctrine = $doctrine;
        $this->eventDispatcher = $eventDispatcher;
        $this->container = $container;
        $this->session = $container->get('session');
    }

    /**
     * Load user
     *
     * @param $identity
     * @param $attributes
     * @return mixed
     */
    public function loadUser($identity, $attributes)
    {
        $attributes['username'] = $this->defaults['username'];
        $attributes['enabled'] = $this->defaults['enabled'];
        foreach ($this->defaults as $key => $value) {
            if ($key == 'username' || $key == 'enabled' || (!array_key_exists($key, $attributes))) {
                $attributes[$key] = $this->defaults[$key];
            }
        }
        $user = new $this->userClass($identity, $attributes, $this->doctrine);
        $this->eventDispatcher->dispatch(AuthenticationEvents::onAnonymousLogin, new AnonymousLoginEvent($user, $this->container->get('request', ContainerInterface::NULL_ON_INVALID_REFERENCE)));

        return $user;
    }

    /**
     * Loads the user for the given username.
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param  string        $username The username
     * @return UserInterface
     * @see UsernameNotFoundException
     * @throws UsernameNotFoundException if the user is not found

     */
    public function loadUserByUsername($username)
    {
        if ($username == 'anonymous') {
            return new $this->userClass($this->session->getId(), $this->defaults, $this->doctrine);
        }
        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
    }

    /**
     * Refreshes the user for the account interface.
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param  UserInterface            $user
     * @return UserInterface
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof AnonymousUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param  string  $class
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return $class === 'RL\MainBundle\Security\User\AnonymousUser';
    }
}
