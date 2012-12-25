<?php
/**
 * @author Tux-oid
 */

namespace RL\ForumBundle\Entity;
use Doctrine\ORM\EntityRepository;

class SubsectionRepository extends EntityRepository
{
    public function getSubsectionByRewrite($rewrite, $section)
    {
        $em = $this->_em;
        $q = $em->createQuery('SELECT s FROM RL\ForumBundle\Entity\Subsection AS s WHERE s.rewrite = :rewrite AND s.section = :section')
        ->setFirstResult(0)
        ->setMaxResults(1)
        ->setParameter('rewrite', $rewrite)
        ->setParameter('section', $section);
        $subsection = $q->getSingleResult();

        return $subsection;
    }
}
