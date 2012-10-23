<?php
/**
 * @author Tux-oid
 */
namespace RL\ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{
	/**
	 * @param integer $lastTime
	 */
	public function getMessagesForLastHours($lastTime)
	{
		$lastTime = new \DateTime('-'.$lastTime);
		$q = $this->_em->createQuery('SELECT m FROM RL\ForumBundle\Entity\Message AS m WHERE m.postingTime < :lastTime')
			->setParameter('lastTime', $lastTime);
		$subsection = $q->getResult();
		return $subsection;
	}

}
