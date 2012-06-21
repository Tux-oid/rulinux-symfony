<?php
/**
 * @author Tux-oid 
 */

namespace RL\GalleryBundle\Entity;
use RL\ForumBundle\Entity\Subsection as ForumSubsection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="RL\GalleryBundle\Entity\SubsectionRepository")
 * @ORM\Table(name="gallery_subsection") 
 */
class Subsection extends ForumSubsection
{
	
}
?>
