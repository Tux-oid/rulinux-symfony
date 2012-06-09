<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use RL\ForumBundle\Entity\Message;
use RL\ForumBundle\Entity\Subsection;

/**
 * @ORM\Entity(repositoryClass="RL\ForumBundle\Entity\ThreadRepository")
 * @ORM\Table(name="threads")
 * @ORM\HasLifecycleCallbacks()
 */
class Thread
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\OneToMany(targetEntity="RL\ForumBundle\Entity\Message", mappedBy="thread")
	 */
	protected $messages;
	/**
	 * @ORM\ManyToOne(targetEntity="RL\ForumBundle\Entity\Subsection", inversedBy="threads")
	 */
	protected $subsection;
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $attached = false;
	/**
	 * @ORM\Column(type="datetime", name="changing_timest")
	 */
	protected $changingTime;
	public function __construct()
	{
		$this->messages = new \Doctrine\Common\Collections\ArrayCollection();
	}
	/**
	 * @ORM\prePersist 
	 */
	public function setDefaultValues()
	{
		$this->changingTime = new \DateTime('now');
	}
	/**
	 * @ORM\preUpdate 
	 */
	public function updateChangingTime()
	{
		$this->changingTime = new \DateTime('now');
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
	 * Set attached
	 *
	 * @param boolean $attached
	 */
	public function setAttached($attached)
	{
		$this->attached = $attached;
	}
	/**
	 * Get attached
	 *
	 * @return boolean 
	 */
	public function getAttached()
	{
		return $this->attached;
	}
	/**
	 * Set changingTime
	 *
	 * @param datetime $changingTime
	 */
	public function setChangingTime($changingTime)
	{
		$this->changingTime = $changingTime;
	}
	/**
	 * Get changingTime
	 *
	 * @return datetime 
	 */
	public function getChangingTime()
	{
		return $this->changingTime;
	}
	/**
	 * Add messages
	 *
	 * @param RL\ForumBundle\Entity\Message $messages
	 */
	public function addMessage(\RL\ForumBundle\Entity\Message $messages)
	{
		$this->messages[] = $messages;
	}
	/**
	 * Get messages
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getMessages()
	{
		return $this->messages;
	}
	/**
	 * Set subsection
	 *
	 * @param RL\ForumBundle\Entity\Section $subsection
	 */
	public function setSubsection(\RL\ForumBundle\Entity\Subsection $subsection)
	{
		$this->subsection = $subsection;
	}
	/**
	 * Get subsection
	 *
	 * @return RL\ForumBundle\Entity\Section 
	 */
	public function getSubsection()
	{
		return $this->subsection;
	}
}