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
        $lastTime = new \DateTime('-'.$lastTime.' hours');
        $q = $this->_em->createQuery('SELECT m FROM RL\ForumBundle\Entity\Message AS m WHERE m.postingTime > :lastTime ORDER BY m.postingTime DESC')
            ->setParameter('lastTime', $lastTime);
        $subsection = $q->getResult();

        return $subsection;
    }

}
