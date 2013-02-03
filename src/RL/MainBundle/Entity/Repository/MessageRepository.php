<?php
/**
 * Copyright (c) 2008 - 2012, Peter Vasilevsky
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

use Doctrine\ORM\EntityRepository;

/**
 * RL\MainBundle\Entity\MessageRepository
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class MessageRepository extends EntityRepository
{
    /**
     * @param int $lastTime
     * @return array
     */
    public function getMessagesForLastHours($lastTime)
    {
        $lastTime = new \DateTime('-'.$lastTime.' hours');
        $q = $this->_em->createQuery('SELECT m FROM RL\MainBundle\Entity\Message AS m WHERE m.postingTime > :lastTime ORDER BY m.postingTime DESC')
            ->setParameter('lastTime', $lastTime);

        return $q->getResult();
    }

    /**
     * Search
     *
     * @param array $params
     * @return array
     */
    public function search(array $params)
    {
        $where = 'm.thread IN (SELECT t FROM RL\MainBundle\Entity\Thread AS t WHERE t.subsection IN (SELECT sub FROM RL\MainBundle\Entity\Subsection AS sub WHERE sub.section = :section)) ';
        $where .= 'AND m.postingTime <= :period ';
        if (!empty($params['user'])) {
            $where .= 'AND m.user = (SELECT u FROM RL\MainBundle\Entity\User AS u WHERE u.username = :user) ';
        }
        switch ($params['fields']) {
            case 'both':
                $where .= 'AND (LOWER(m.subject) LIKE LOWER(:query) OR LOWER(m.comment) LIKE LOWER(:query)) ';
                break;
            case 'subjects':
                $where .= 'AND LOWER(m.subject) LIKE LOWER(:query) ';
                break;
            case 'messages':
            default:
                $where .= 'AND LOWER(m.comment) LIKE LOWER(:query) ';
                break;
        }

        $qb = $this->_em->createQueryBuilder()
                ->add('select', 'm')
                ->add('from', 'RL\MainBundle\Entity\Message m')
                ->add('where', $where)
                ->add('orderBy', 'm.postingTime' . ' DESC')
                ->setParameter('section', $params['section'])
                ->setParameter('query', '%'.$params['query'].'%');

        switch ($params['period']) {
            case 'month':
                $qb->setParameter('period', new \DateTime('-1 month'));
                break;
            case 'year':
                $qb->setParameter('period', new \DateTime('-1 year'));
                break;
            case 'all':
            default:
                $qb->setParameter('period', new \DateTime());
                break;
        }
        if (!empty($params['user'])) {
            $qb->setParameter('user', $params['user']);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Get unfiltered messages
     *
     * @return array
     */
    public function getUnfilteredMessages()
    {
        $q = $this->_em->createQuery('SELECT m FROM RL\MainBundle\Entity\Message AS m');//TODO: filter this messages

        return $q->getResult();
    }

}
