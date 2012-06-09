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
	public function getThreadById($id, $limit, $offset)
	{
		$em = $this->_em;
		$q = $em->createQueryBuilder()
			->select('t, m')
			->from('RL\ForumBundle\Entity\Thread', 't')
			->innerJoin('t.messages', 'm')
			->where('t.id = :id')
			->setParameter('id', $id)
			->orderBy('m.id')
			->setFirstResult($offset)
			->setMaxResults($limit)
			->getQuery();
		$thread = $q->getSingleResult();
		return $thread;
	}
}
?>
