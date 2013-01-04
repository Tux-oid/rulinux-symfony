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

namespace RL\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RL\MainBundle\Entity\UserRepository
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class UserRepository extends EntityRepository
{
    public function getUserCommentsInformation($user)
    {
        $ret = array();
        $em = $this->_em;
        $q = $em->createQuery('SELECT COUNT(m) AS cnt FROM RL\ForumBundle\Entity\Message AS m WHERE m.user = :user')
            ->setParameter('user', $user);
        $userComments = $q->getSingleResult();
        $ret['allComments'] = $userComments['cnt'];

        $q = $em->createQuery('SELECT COUNT(t) AS thrcnt FROM RL\ForumBundle\Entity\Thread AS t INNER JOIN t.messages AS m WHERE m.id = (SELECT MIN(msg.id) AS msg_id FROM RL\ForumBundle\Entity\Message AS msg WHERE msg.thread = t.id) AND m.user = :user')
            ->setParameter('user', $user);
        $userComments = $q->getSingleResult();
        $ret['allThreads'] = $userComments['thrcnt'];

        $q = $em->createQuery('SELECT MIN(m.postingTime) AS minimum, MAX(m.postingTime) AS maximum FROM RL\ForumBundle\Entity\Message AS m WHERE m.id IN (SELECT min(msg.id) FROM RL\ForumBundle\Entity\Message AS msg INNER JOIN msg.thread AS t WHERE msg.user = :user GROUP BY t.id ORDER BY t.id)')
            ->setParameter('user', $user);
        $userComments = $q->getSingleResult();
        $ret['firstThreadDate'] = $userComments['minimum'];
        $ret['lastThreadDate'] = $userComments['maximum'];

        $q = $em->createQuery('SELECT MIN(m.postingTime) AS minimum, MAX(m.postingTime) AS maximum FROM RL\ForumBundle\Entity\Message AS m WHERE m.user = :user AND m.id NOT IN (SELECT min(msg.id) FROM RL\ForumBundle\Entity\Message AS msg INNER JOIN msg.thread AS t WHERE msg.user = :user GROUP BY t.id ORDER BY t.id)')
            ->setParameter('user', $user);
        $userComments = $q->getSingleResult();
        $ret['firstCommentDate'] = $userComments['minimum'];
        $ret['lastCommentDate'] = $userComments['maximum'];

        return $ret;
    }
}
