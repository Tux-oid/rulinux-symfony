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

namespace RL\ArticlesBundle\Entity;

use RL\ForumBundle\Entity\Message;
use RL\ForumBundle\Entity\ThreadRepository as ForumThreadRepository;

/**
 * RL\ArticlesBundle\Entity\ThreadRepository
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ThreadRepository extends ForumThreadRepository
{
    public function getThreads($subsection, $limit, $offset)
    {
        $em = $this->_em;
        $q = $em->createQuery('SELECT t, m FROM RL\ArticlesBundle\Entity\Thread AS t INNER JOIN t.messages AS m WHERE m.id = (SELECT MIN(msg.id) AS msg_id FROM RL\ForumBundle\Entity\Message AS msg WHERE msg.thread = t.id) AND t.subsection = (SELECT s.id FROM RL\ForumBundle\Entity\Subsection s WHERE s.id = ?1) AND t.approved = true ORDER BY t.id DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameter(1, $subsection->getId());
        $threads = $q->getResult();

        return $threads;
    }
    public function getUnconfirmed()
    {
        $em = $this->_em;
        $q = $em->createQuery('SELECT t, m FROM RL\ArticlesBundle\Entity\Thread AS t INNER JOIN t.messages AS m WHERE m.id = (SELECT MIN(msg.id) AS msg_id FROM RL\ForumBundle\Entity\Message AS msg WHERE msg.thread = t.id) AND t.approved = false ORDER BY t.id DESC');
        $threads = $q->getResult();

        return $threads;
    }
}
