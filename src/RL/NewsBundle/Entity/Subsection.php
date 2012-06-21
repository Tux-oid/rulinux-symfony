<?php
/**
 * @author Tux-oid 
 */

namespace RL\NewsBundle\Entity;
use RL\ForumBundle\Entity\Subsection as ForumSubsection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="RL\NewsBundle\Entity\SubsectionRepository")
 * @ORM\Table(name="news_subsection") 
 */
class Subsection extends ForumSubsection
{
	/**
	 * @ORM\Column(type="string", length="2048", nullable="true")
	 */
	protected $image;
	public function getImage()
	{
		return $this->image;
	}
	public function setImage($image)
	{
		$this->image = $image;
	}
}
?>
