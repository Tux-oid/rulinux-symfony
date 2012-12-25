<?php
/**
 * @author Tux-oid
 */

namespace RL\SecurityBundle\Entity;
use Doctrine\ORM\EntityRepository;

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
