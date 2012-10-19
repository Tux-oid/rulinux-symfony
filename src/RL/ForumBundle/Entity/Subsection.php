<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use RL\MainBundle\Entity\Section;

/**
 * @ORM\Entity(repositoryClass="RL\ForumBundle\Entity\SubsectionRepository")
 * @ORM\Table(name="subsections")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="content_type", type="string", length=20)
 * @ORM\DiscriminatorMap({"subsection" = "Subsection"})
 */
class Subsection
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;
	/**
	 * @ORM\Column(type="string", length=512)
	 */
	protected $description;
	/**
	 * @ORM\Column(type="text")
	 */
	protected $shortfaq;
	/**
	 * @ORM\Column(type="string", length=512, nullable=true)
	 */
	protected $rewrite;
	/**
	 * @ORM\OneToMany(targetEntity="Thread", mappedBy="subsection")
	 */
	protected $threads;
	/**
	 * @ORM\ManyToOne(targetEntity="RL\MainBundle\Entity\Section", inversedBy="subsections")
	 * @ORM\OrderBy({"id" = "ASC"})
	 */
	protected $section;
	public function __construct()
	{
		$this->threads = new \Doctrine\Common\Collections\ArrayCollection();
	}
	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId()
	{
		return $this->id;
	}
	/**
	 * Set name
	 *
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	/**
	 * Get name
	 *
	 * @return string 
	 */
	public function getName()
	{
		return $this->name;
	}
	/**
	 * Set description
	 *
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}
	/**
	 * Get description
	 *
	 * @return string 
	 */
	public function getDescription()
	{
		return $this->description;
	}
	/**
	 * Set shortfaq
	 *
	 * @param text $shortfaq
	 */
	public function setShortfaq($shortfaq)
	{
		$this->shortfaq = $shortfaq;
	}
	/**
	 * Get shortfaq
	 *
	 * @return text 
	 */
	public function getShortfaq()
	{
		return $this->shortfaq;
	}
	/**
	 * Set rewrite
	 *
	 * @param string $rewrite
	 */
	public function setRewrite($rewrite)
	{
		$this->rewrite = $rewrite;
	}
	/**
	 * Get rewrite
	 *
	 * @return string 
	 */
	public function getRewrite()
	{
		return $this->rewrite;
	}
	/**
	 * Add threads
	 *
	 * @param RL\ForumBundle\Entity\Thread $threads
	 */
	public function addThread(\RL\ForumBundle\Entity\Thread $threads)
	{
		$this->threads[] = $threads;
	}
	/**
	 * Get threads
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getThreads()
	{
		return $this->threads;
	}
	public function getSection()
	{
		return $this->section;
	}
	public function setSection($section)
	{
		$this->section = $section;
	}


}
