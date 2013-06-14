<?php
/**
 * Copyright (c) 2009 - 2013, Peter Vasilevsky
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

namespace RL\MainBundle\Entity\Repository;

use RL\MainBundle\Entity\Thread;
use RL\MainBundle\Security\User\RLUserInterface;

/**
 * RL\MainBundle\Entity\Repository\ReaderRepository
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ReaderRepository extends AbstractRepository
{

    /**
     * Get expired readers
     *
     * @param Thread $thread
     * @param RLUserInterface $user
     * @param $sessionId
     * @return array
     */
    public function getExpiredReaders(Thread $thread, RLUserInterface $user, $sessionId)
    {
        $time = new \DateTime('-5 minutes');
        $qb = $this->_em->createQueryBuilder()
            ->addSelect('r')
            ->from('RL\MainBundle\Entity\Reader', 'r')
            ->where('r.timestamp < :timest')
            ->andWhere('r.thread = :thread')
            ->andWhere('r.sessionId = :sessionId')
            ->setParameter('timest', $time)
            ->setParameter('thread', $thread)
            ->setParameter('sessionId', $sessionId);
        if (!$user->isAnonymous()) {
            $qb->orWhere('r.user = :user')
                ->setParameter('user', $user);
        }

        $sqb = $this->_em->createQueryBuilder()
            ->addSelect('r')
            ->from('RL\MainBundle\Entity\Reader', 'r')
            ->where('r.sessionId = :sessionId')
            ->setParameter('sessionId', $sessionId);

        return array_merge($qb->getQuery()->getResult(), $sqb->getQuery()->getResult());
    }

    /**
     * Get readers
     *
     * @param Thread $thread
     * @return array
     */
    public function getReaders(Thread $thread)
    {
        $time = new \DateTime('-5 minutes');
        $qb = $this->_em->createQueryBuilder()
            ->addSelect('r')
            ->from('RL\MainBundle\Entity\Reader', 'r')
            ->where('r.timestamp > :timest')
            ->andWhere('r.thread = :thread')
            ->setParameter('timest', $time)
            ->setParameter('thread', $thread);

        return $qb->getQuery()->getResult();
    }
}
