<?php
/**
 * This file is part of ONP.
 *
 * Copyright (c) 2013 Opensoft (http://opensoftdev.com)
 *
 * The unauthorized use of this code outside the boundaries of
 * Opensoft is prohibited.
 */

namespace RL\MainBundle\Entity\Repository;

use RL\MainBundle\Entity\User;
use RL\MainBundle\Entity\Filter;

/**
 * RL\MainBundle\Entity\Repository\UserFilterRepository
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class UsersFilterRepository extends AbstractRepository
{
    public function getUsersFilterByUserAndFilter(User $user, Filter $filter)
    {
        $q = $this->_em->createQuery(
            'SELECT uf FROM RLMainBundle:UsersFilter AS uf WHERE uf.user = :user AND uf.filter = :filter'//INNER JOIN uf. user AS u INNER JOIN uf.filter AS f
        )
            ->setParameter('user', $user)
            ->setParameter('filter', $filter);

        return $q->getResult();
    }
}
