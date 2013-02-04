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

namespace RL\MainBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use RL\MainBundle\Entity\Thread;
use RL\MainBundle\Entity\Message;

/**
 * RL\MainBundle\Entity\ThreadRepository
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ThreadRepository extends EntityRepository
{
    public function getThreads($subsection, $limit, $offset)
    {
        $em = $this->_em;
        $q = $em->createQuery('SELECT t, m FROM RL\MainBundle\Entity\Thread AS t INNER JOIN t.messages AS m WHERE m.id = (SELECT MIN(msg.id) AS msg_id FROM RL\MainBundle\Entity\Message AS msg WHERE msg.thread = t.id) AND t.subsection = (SELECT s.id FROM RL\MainBundle\Entity\Subsection s WHERE s.id = ?1) ORDER BY t.id DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameter(1, $subsection->getId());
        $threads = $q->getResult();

        return $threads;
    }
    public function getThreadStartMessageById($id)
    {
        $em = $this->_em;
        $q = $em->createQuery("SELECT m FROM RL\MainBundle\Entity\Message AS m INNER JOIN m.thread AS t WHERE t.id = :id AND m.id = (SELECT MIN(msg.id) FROM RL\MainBundle\Entity\Message AS msg WHERE msg.thread=:id)")
            ->setMaxResults(1)
            ->setFirstResult(0)
            ->setParameter('id', $id);
        $thread = $q->getSingleResult();

        return $thread;
    }
    public function getThreadCommentsById($id, $limit, $offset)
    {
        $em = $this->_em;
        $q = $em->createQuery("SELECT m, t FROM RL\MainBundle\Entity\Message AS m INNER JOIN m.thread AS t WHERE t.id = :id AND m.id NOT IN (SELECT MIN(msg.id) FROM RL\MainBundle\Entity\Message AS msg WHERE msg.thread = :id ) ORDER BY m.postingTime ASC")
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setParameter('id', $id);
        $thread = $q->getResult();

        return $thread;
    }
    public function getThreadsCount($section)
    {
        $ret = array();
        $all = $this->_em->createQuery('SELECT s.id, COUNT(t.id) AS allCnt FROM RL\MainBundle\Entity\Thread AS t INNER JOIN t.subsection AS s WHERE s.section = :section GROUP BY s.id ORDER BY s.id')
            ->setParameter('section', $section)
            ->getResult();
        foreach ($all as $value) {
            $ret['all'][$value['id']] = $value['allCnt'];
        }
        $yesterday = new \DateTime('-1 day');
        $day = $this->_em->createQuery('SELECT s.id, COUNT(t.id) AS dayCnt FROM RL\MainBundle\Entity\Thread AS t INNER JOIN t.subsection AS s WHERE t.postingTime > :postingTime AND s.section = :section GROUP BY s.id ORDER BY s.id')
            ->setParameter('postingTime', $yesterday->format('Y.m.d H:i:s'))
            ->setParameter('section', $section)
            ->getResult();
        foreach ($day as $value) {
            $ret['day'][$value['id']] = $value['dayCnt'];
        }
        $lastHour = new \DateTime('-1 hour');
        $hour = $this->_em->createQuery('SELECT s.id, COUNT(t.id) AS hourCnt FROM RL\MainBundle\Entity\Thread AS t INNER JOIN t.subsection AS s WHERE t.postingTime > :postingTime AND s.section = :section GROUP BY s.id ORDER BY s.id')
            ->setParameter('postingTime', $lastHour->format('Y.m.d H:i:s'))
            ->setParameter('section', $section)
            ->getResult();
        foreach ($hour as $value) {
            $ret['hour'][$value['id']] = $value['hourCnt'];
        }

        return $ret;
    }
    public function getCommentsCount($subsection, $limit, $offset)
    {
        $ret = array();

        return $ret;
    }
    public function getNeighborThreadsById($id)
    {
        $em = $this->_em;
        $ret = array();
        $previous_thread = $em->createQuery("SELECT thr, s FROM RL\MainBundle\Entity\Thread AS thr INNER JOIN thr.subsection AS s WHERE thr.id=(SELECT MAX(t.id) FROM RL\MainBundle\Entity\Thread AS t WHERE t.id <:id AND t.subsection = (SELECT sub FROM RL\MainBundle\Entity\Thread AS th INNER JOIN th.subsection AS sub WHERE th.id = :id))")
            ->setMaxResults(1)
            ->setFirstResult(0)
            ->setParameter('id', $id)
            ->getOneOrNullResult();
        if (isset($previous_thread)) {
            $previous_id = $em->createQuery("SELECT m, t FROM RL\MainBundle\Entity\Message AS m INNER JOIN m.thread AS t WHERE t=:thread AND m.id = (SELECT MIN(msg.id) FROM RL\MainBundle\Entity\Message AS msg WHERE msg.thread=:thread)")
                ->setMaxResults(1)
                ->setFirstResult(0)
                ->setParameter('thread', $previous_thread)
                ->getSingleResult();
            $ret['previous'] = $previous_id;
        }
        $next_thread = $em->createQuery("SELECT thr, s FROM RL\MainBundle\Entity\Thread AS thr INNER JOIN thr.subsection AS s WHERE thr.id=(SELECT MIN(t.id) FROM RL\MainBundle\Entity\Thread AS t WHERE t.id >:id AND t.subsection = (SELECT sub FROM RL\MainBundle\Entity\Thread AS th INNER JOIN th.subsection AS sub WHERE th.id = :id))")
            ->setMaxResults(1)
            ->setFirstResult(0)
            ->setParameter('id', $id)
            ->getOneOrNullResult();
        if (isset($next_thread)) {
            $next_id = $em->createQuery("SELECT m, t FROM RL\MainBundle\Entity\Message AS m INNER JOIN m.thread AS t WHERE t=:thread AND m.id = (SELECT MIN(msg.id) FROM RL\MainBundle\Entity\Message AS msg WHERE msg.thread=:thread)")
                ->setMaxResults(1)
                ->setFirstResult(0)
                ->setParameter('thread', $next_thread)
                ->getSingleResult();
            $ret['next'] = $next_id;
        }

        return $ret;
    }
}
