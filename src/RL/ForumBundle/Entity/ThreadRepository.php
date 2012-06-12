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
		$q = $em->createQuery("SELECT m, t FROM RL\ForumBundle\Entity\Message AS m INNER JOIN m.thread AS t WHERE t.id = :id AND m.id NOT IN (SELECT MIN(msg.id) FROM RL\ForumBundle\Entity\Message AS msg WHERE msg.thread = :id ) ORDER BY m.postingTime ASC")
			->setMaxResults($limit)
			->setFirstResult($offset)
			->setParameter('id', $id);
		$thread = $q->getResult();
		return $thread;
	}
	public function getThreadsCount()
	{
		$ret = array();
		$all = $this->_em->createQuery('SELECT s.id, COUNT(t.id) AS allCnt FROM RL\ForumBundle\Entity\Thread AS t INNER JOIN t.subsection AS s GROUP BY s.id ORDER BY s.id')
			->getResult();
		foreach($all as $value)
		{
			$ret['all'][$value['id']] = $value['allCnt'];
		}
		$yesterday = new \DateTime('-1 day');
		$day = $this->_em->createQuery('SELECT s.id, COUNT(t.id) AS dayCnt FROM RL\ForumBundle\Entity\Thread AS t INNER JOIN t.subsection AS s WHERE t.postingTime > :postingTime GROUP BY s.id ORDER BY s.id')
			->setParameter('postingTime', $yesterday->format('Y.m.d H:i:s'))
			->getResult();
		foreach($day as $value)
		{
			$ret['day'][$value['id']] = $value['dayCnt'];
		}
		$lastHour = new \DateTime('-1 hour');
		$hour = $this->_em->createQuery('SELECT s.id, COUNT(t.id) AS hourCnt FROM RL\ForumBundle\Entity\Thread AS t INNER JOIN t.subsection AS s WHERE t.postingTime > :postingTime GROUP BY s.id ORDER BY s.id')
			->setParameter('postingTime', $lastHour->format('Y.m.d H:i:s'))
			->getResult();
		foreach($hour as $value)
		{
			$ret['hour'][$value['id']] = $value['hourCnt'];
		}
		return $ret;
	}
	public function getCommentsCount($limit, $offset)
	{
		$ret = array();
		$all = $this->_em->createQuery('SELECT t.id, COUNT(m.id) AS allCnt FROM RL\ForumBundle\Entity\Message AS m INNER JOIN m.thread AS t GROUP BY t.id ORDER BY t.id')
			->setMaxResults($limit)
			->setFirstResult($offset)
			->getResult();
		foreach($all as $value)
		{
			$ret['all'][$value['id']] = $value['allCnt'];
		}
		$yesterday = new \DateTime('-1 day');
		$day = $this->_em->createQuery('SELECT t.id, COUNT(m.id) AS dayCnt FROM RL\ForumBundle\Entity\Message AS m INNER JOIN m.thread AS t WHERE m.postingTime > :postingTime GROUP BY t.id ORDER BY t.id')
			->setParameter('postingTime', $yesterday->format('Y.m.d H:i:s'))
			->setMaxResults($limit)
			->setFirstResult($offset)
			->getResult();
		foreach($day as $value)
		{
			$ret['day'][$value['id']] = $value['dayCnt'];
		}
		$lastHour = new \DateTime('-1 hour');
		$hour = $this->_em->createQuery('SELECT t.id, COUNT(m.id) AS hourCnt FROM RL\ForumBundle\Entity\Message AS m INNER JOIN m.thread AS t WHERE m.postingTime > :postingTime GROUP BY t.id ORDER BY t.id')
			->setParameter('postingTime', $lastHour->format('Y.m.d H:i:s'))
			->setMaxResults($limit)
			->setFirstResult($offset)
			->getResult();
		foreach($hour as $value)
		{
			$ret['hour'][$value['id']] = $value['hourCnt'];
		}
		return $ret;
	}
	public function getClassName()
	{
		
	}
}
?>
