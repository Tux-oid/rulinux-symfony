<?php
/**
 *@author Tux-oid 
 */

namespace RL\NewsBundle\Entity;
use RL\ForumBundle\Entity\Subsection;
use Doctrine\ORM\Mapping as ORM;

/**
 *@ORM\Entity(repositoryClass="RL\ForumBundle\Entity\SubsectionRepository")
 * @ORM\Table(name="news_subsection") 
 */
class NewsSubsection extends Subsection
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
