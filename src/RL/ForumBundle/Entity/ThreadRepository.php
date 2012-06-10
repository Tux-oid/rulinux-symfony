<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Entity;
use Doctrine\ORM\EntityRepository;
use RL\ForumBundle\Entity\Thread;
use RL\ForumBundle\Entity\Message;

class ThreadRepository extends EntityRepository
{
	public function getThreads($subsection, $limit, $offset)
	{
		$em = $this->_em;
		$q = $em->createQueryBuilder()
			->select('t, m')
			->from('RL\ForumBundle\Entity\Thread', 't')
			->innerJoin('t.messages', 'm')
			->where('m.id = (SELECT MIN(msg.id) AS msg_id FROM RL\ForumBundle\Entity\Message AS msg WHERE msg.thread = t.id)')
			->andWhere('t.subsection = (SELECT s.id FROM RL\ForumBundle\Entity\Subsection s WHERE s.rewrite = ?1)')
			->setParameter(1, $subsection)
			->orderBy('t.id')
			->setFirstResult($offset)
			->setMaxResults($limit)
			->getQuery();
		$threads = $q->getResult();
		return $threads;
	}
	public function getThreadStartMessageById($id)
	{
		$em = $this->_em;
//		$q = $em->createQuery("SELECT t,m FROM RL\ForumBundle\Entity\Thread AS t INNER JOIN t.messages AS m WHERE t.id = :id AND m.id = (SELECT MIN(msg.id) FROM RL\ForumBundle\Entity\Message AS msg WHERE msg.thread=:id)")
		$q = $em->createQuery("SELECT m FROM RL\ForumBundle\Entity\Message AS m INNER JOIN m.thread AS t WHERE t.id = :id AND m.id = (SELECT MIN(msg.id) FROM RL\ForumBundle\Entity\Message AS msg WHERE msg.thread=:id)")
			->setMaxResults(1)
			->setFirstResult(0)
			->setParameter('id', $id);
		$thread = $q->getSingleResult();
		return $thread;
	}
	public function getThreadCommentsById($id, $limit, $offset)
	{
		$em = $this->_em;
//		$q = $em->createQuery("SELECT t, m FROM RL\ForumBundle\Entity\Thread AS t INNER JOIN t.messages AS m WHERE t.id = :id ORDER BY m.id ASC")
		$q = $em->createQuery("SELECT m, t FROM RL\ForumBundle\Entity\Message AS m INNER JOIN m.thread AS t WHERE t.id = :id AND m.id NOT IN (SELECT MIN(msg.id) FROM RL\ForumBundle\Entity\Message AS msg WHERE msg.thread = :id ) ORDER BY m.id ASC")
			->setMaxResults($limit)
			->setFirstResult($offset)
			->setParameter('id', $id);
		$thread = $q->getResult();
		return $thread;
	}
	public function getClassName()
	{
		
	}
}
?>
