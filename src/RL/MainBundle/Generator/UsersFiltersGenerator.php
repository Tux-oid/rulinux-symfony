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

namespace RL\MainBundle\Generator;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use RL\MainBundle\Entity\Repository\FilterRepository;
use RL\MainBundle\Entity\Repository\UserRepository;
use RL\MainBundle\Entity\Repository\UsersFilterRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * RL\MainBundle\Generator\UsersFiltersGenerator
 *
 * @Service("rl.main.users_filters_generator")
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class UsersFiltersGenerator
{
    /**
     * @var FilterRepository
     */
    protected $filterRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UsersFilterRepository
     */
    protected $userFilterRepository;

    /**
     * Constructor
     *
     * @InjectParams({
     * "doctrine" = @Inject("doctrine"),
     * })
     *
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->filterRepository = $doctrine->getRepository('RLMainBundle:Filter');
        $this->userRepository = $doctrine->getRepository('RLMainBundle:User');
        $this->userFilterRepository = $doctrine->getRepository('RLMainBundle:UsersFilter');
    }

    /**
     * Generates users filters
     */
    public function generate()
    {
        $users = $this->userRepository->findAll();
        $filters = $this->filterRepository->findAll();
        /** @var $user \RL\MainBundle\Entity\User */
        foreach ($users as $user) {
            /** @var $filter \RL\MainBundle\Entity\Filter */
            foreach ($filters as $filter) {
                $userFilter = $this->userFilterRepository->getUsersFilterByUserAndFilter($user, $filter);
                if ($userFilter === array()) {
                    /** @var $newUserFilter \RL\MainBundle\Entity\UsersFilter */
                    $newUserFilter = $this->userFilterRepository->createDefaultEntity();
                    $newUserFilter->setUser($user);
                    $user->addFilter($newUserFilter);
                    $newUserFilter->setFilter($filter);
                    $filter->addUser($newUserFilter);
                    $newUserFilter->setWeight(0);
                }
            }
        }
        $this->userFilterRepository->flush();
    }
}
