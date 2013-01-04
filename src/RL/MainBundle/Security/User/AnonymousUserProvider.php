<?php
/**
 * @author Ax-xa-xa
 * @author Tux-oid
 */

namespace RL\MainBundle\Security\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class AnonymousUserProvider implements UserProviderInterface
{
    protected $defaults;
    protected $userclass;
    protected $doctrine;

    public function __construct($userclass, $defaults, $doctrine)
    {
        $this->defaults = $defaults;
        $this->userclass = $userclass;
        $this->doctrine = $doctrine;
    }

    public function loadUser($identity, $attributes)
    {
        $attributes['username'] = $this->defaults['username'];
        $attributes['enabled'] = $this->defaults['enabled'];
        foreach ($this->defaults as $key => $value) {
            if ($key == 'username' || $key == 'enabled' || (!array_key_exists($key, $attributes))) {
                $attributes[$key] = $this->defaults[$key];
            }
        }

        return new $this->userclass($identity, $attributes, $this->doctrine);
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
            return new $this->userclass(\session_id(), $this->defaults, $this->doctrine);//FIXME: fix this shit
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
