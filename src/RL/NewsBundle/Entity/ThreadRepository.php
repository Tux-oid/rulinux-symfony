<?php
/**
 * @author Tux-oid
 */

namespace RL\NewsBundle\Entity;
use RL\ForumBundle\Entity\Message;
use RL\ArticlesBundle\Entity\ThreadRepository as ArticlesThreadRepository;

class ThreadRepository extends ArticlesThreadRepository
{
    public function getNews($limit, $offset)
    {
        $em = $this->_em;
        $q = $em->createQuery('SELECT t, m FROM RL\NewsBundle\Entity\Thread AS t INNER JOIN t.messages AS m WHERE m.id = (SELECT MIN(msg.id) AS msg_id FROM RL\ForumBundle\Entity\Message AS msg WHERE msg.thread = t.id) AND t.approved = true ORDER BY t.id DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        $threads = $q->getResult();

        return $threads;
    }
    public function getNewsCount()
    {
        $em = $this->_em;
        $q = $em->createQuery('SELECT COUNT(t) AS cnt FROM RL\NewsBundle\Entity\Thread AS t')
        ->setFirstResult(0)
        ->setMaxResults(1);
        $count = $q->getSingleResult();

        return (integer) $count['cnt'];
    }
}
