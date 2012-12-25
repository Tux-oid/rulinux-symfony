<?php
/**
 *@author Tux-oid
 */

namespace RL\ArticlesBundle\Entity;
use RL\ForumBundle\Entity\Subsection as ForumSubsection;
use Doctrine\ORM\Mapping as ORM;

/**
 *@ORM\Entity(repositoryClass="RL\ArticlesBundle\Entity\SubsectionRepository")
 * @ORM\Table(name="articles_subsection")
 */
class Subsection extends ForumSubsection
{

}
