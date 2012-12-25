<?php
/**
 * @author Tux-oid
 */

namespace RL\NewsBundle\Entity;
use RL\ForumBundle\Entity\SubsectionRepository as ForumSubsectionRepository;

class SubsectionRepository extends ForumSubsectionRepository
{
    public function getSubsectionByRewrite($rewrite, $section)
    {
        $em = $this->_em;
        $q = $em->createQuery('SELECT s FROM RL\NewsBundle\Entity\Subsection AS s WHERE s.rewrite = :rewrite AND s.section = :section')
        ->setFirstResult(0)
        ->setMaxResults(1)
        ->setParameter('rewrite', $rewrite)
        ->setParameter('section', $section);
        $subsection = $q->getSingleResult();

        return $subsection;
    }
}
