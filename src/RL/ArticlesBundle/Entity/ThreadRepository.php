<?php
/**
 * @author Tux-oid 
 */

namespace RL\ArticlesBundle\Entity;
use Doctrine\ORM\EntityRepository;
use RL\ArticlesBundle\Entity\Article;
use RL\ForumBundle\Entity\Message;
use RL\ForumBundle\Entity\ThreadRepository as ForumThreadRepository;

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
?>
