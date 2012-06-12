<?php
/**
 * @author Tux-oid 
 */

namespace RL\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sections") 
 */
class Section
{
	/**
	 * @ORM\Id
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\Column(type="string", length="512")
	 */
	protected $name;
	/**
	 * @ORM\Column(type="string", length="1024", nullable="true")
	 */
	protected $description;
	/**
	 * @ORM\Column(type="string", length="256", nullable="false")
	 */
	protected $rewrite;
	/**
	 * @ORM\Column(type="string", length="1024", nullable="false")
	 */
	protected $bundle;
	/**
	 * ORM\OneToMany(targetEntity="RL\ForumBundle\Entity\Subsection", mappedBy="section")
	 */
	protected $subsections;
	function __construct()
	{
		$this->subsections = new ArrayCollection();
	}
		public function getId()
	{
		return $this->id;
	}
	public function setId($id)
	{
		$this->id = $id;
	}
	public function getName()
	{
		return $this->name;
	}
	public function setName($name)
	{
		$this->name = $name;
	}
	public function getDescription()
	{
		return $this->description;
	}
	public function setDescription($description)
	{
		$this->description = $description;
	}
	public function getRewrite()
	{
		return $this->rewrite;
	}
	public function setRewrite($rewrite)
	{
		$this->rewrite = $rewrite;
	}
	public function getBundle()
	{
		return $this->bundle;
	}
	public function setBundle($bundle)
	{
		$this->bundle = $bundle;
	}
	public function getSubsections()
	{
		return $this->subsections;
	}
	public function setSubsections($subsections)
	{
		$this->subsections = $subsections;
	}
}
?>
