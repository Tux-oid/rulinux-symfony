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
	public function getThreads($limit, $user)
	{
		$em = $this->_em;
//		$query = $em->createQuery("SELECT t, m FROM RL\ForumBundle\Entity\Thread t INNER JOIN t.messages m  ORDER BY t.id");
//		$threads = $query->getResult();
		$offset = '0';
		$limit = '10';
		$q = $em->createQueryBuilder()
			->select('t, m')
			->from('RL\ForumBundle\Entity\Thread', 't')
			->innerJoin('t.messages', 'm')
			->orderBy('t.id')
			->setFirstResult($offset)
			->setMaxResults($limit)
			->getQuery();
		$threads = $q->getResult();

		return $threads;
	}
}
?>
