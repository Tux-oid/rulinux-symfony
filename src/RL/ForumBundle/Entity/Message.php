<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use RL\SecurityBundle\Entity\User;
use RL\ForumBundle\Entity\Thread;

/**
 * @ORM\Entity()
 * @ORM\Table(name="comments")
 * @ORM\HasLifecycleCallbacks()
 */
class Message
{
	/**
	 * @ORM\Id
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\ManyToOne(targetEntity="RL\ForumBundle\Entity\Thread", inversedBy="messages")
	 */
	protected $thread;
	/**
	 * @ORM\ManyToOne(targetEntity="RL\SecurityBundle\Entity\User")
	 */
	protected $user;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $referer;
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $postingTime;
	/**
	 * @ORM\Column(type="text")
	 */
	protected $subject;
	/**
	 * @ORM\Column(type="text")
	 */
	protected $comment;
	/**
	 * @ORM\Column(type="text", name="raw_comment")
	 */
	protected $rawComment;
	/**
	 * @ORM\Column(type="string", length="512", nullable="true")
	 */
	protected $useragent;
	/**
	 * @ORM\Column(type="datetime", name="changing_timest")
	 */
	protected $changingTime;
	/**
	 * @ORM\OneToOne(targetEntity="RL\SecurityBundle\Entity\User")
	 */
	protected $changedBy;
	/**
	 * @ORM\Column(type="string", length="512", name="changed_for", nullable="true")
	 */
	protected $changedFor;
	//ManyToOne on filters Entity
	protected $filters;
	/**
	 * @ORM\Column(type="boolean", name="show_ua")
	 */
	protected $showUa = TRUE;
	/**
	 * @ORM\Column(type="string", length="128", name="session_id", nullable="true")
	 */
	protected $sessionId;
	
	public function __construct()
	{
		$this->postingTime = $this->changingTime = new \DateTime('now');
	}
	/**
	 * @ORM\prePersist 
	 */
	public function setDefaultValues()
	{
		$this->postingTime = $this->changingTime = new \DateTime('now');
		$this->sessionId = \session_id();
		$this->useragent = $_SERVER['HTTP_USER_AGENT'];
		if($this->user->getShowUA())
			$this->showUa = TRUE;
		else
			$this->showUa = FALSE;
		
	}
	/**
	 * @ORM\preUpdate 
	 */
	public function updateChangingTime()
	{
		$this->changingTime = new \DateTime('now');
		$this->getThread()->setChangingTime($this->changingTime);
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
	 * Set referer
	 *
	 * @param integer $referer
	 */
	public function setReferer($referer)
	{
		$this->referer = $referer;
	}
	/**
	 * Get referer
	 *
	 * @return integer 
	 */
	public function getReferer()
	{
		return $this->referer;
	}
	/**
	 * Set postingTime
	 *
	 * @param datetime $postingTime
	 */
	public function setPostingTime($postingTime)
	{
		$this->postingTime = $postingTime;
	}
	/**
	 * Get postingTime
	 *
	 * @return datetime 
	 */
	public function getPostingTime()
	{
		return $this->postingTime;
	}
	/**
	 * Set subject
	 *
	 * @param text $subject
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}
	/**
	 * Get subject
	 *
	 * @return text 
	 */
	public function getSubject()
	{
		return $this->subject;
	}
	/**
	 * Set comment
	 *
	 * @param text $comment
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;
	}
	/**
	 * Get comment
	 *
	 * @return text 
	 */
	public function getComment()
	{
		return $this->comment;
	}
	/**
	 * Set rawComment
	 *
	 * @param text $rawComment
	 */
	public function setRawComment($rawComment)
	{
		$this->rawComment = $rawComment;
	}
	/**
	 * Get rawComment
	 *
	 * @return text 
	 */
	public function getRawComment()
	{
		return $this->rawComment;
	}
	/**
	 * Set useragent
	 *
	 * @param string $useragent
	 */
	public function setUseragent($useragent)
	{
		$this->useragent = $useragent;
	}
	/**
	 * Get useragent
	 *
	 * @return string 
	 */
	public function getUseragent()
	{
		return $this->useragent;
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
	 * Set changedFor
	 *
	 * @param string $changedFor
	 */
	public function setChangedFor($changedFor)
	{
		$this->changedFor = $changedFor;
	}
	/**
	 * Get changedFor
	 *
	 * @return string 
	 */
	public function getChangedFor()
	{
		return $this->changedFor;
	}
	/**
	 * Set showUa
	 *
	 * @param boolean $showUa
	 */
	public function setShowUa($showUa)
	{
		$this->showUa = $showUa;
	}
	/**
	 * Get showUa
	 *
	 * @return boolean 
	 */
	public function getShowUa()
	{
		return $this->showUa;
	}
	/**
	 * Set changedBy
	 *
	 * @param \RL\SecurityBundle\Entity\User $changedBy
	 */
	public function setChangedBy(\RL\SecurityBundle\Entity\User $changedBy)
	{
		$this->changedBy = $changedBy;
	}
	/**
	 * Get changedBy
	 *
	 * @return \RL\SecurityBundle\Entity\User 
	 */
	public function getChangedBy()
	{
		return $this->changedBy;
	}
	/**
	 * Set user
	 *
	 * @param RL\SecurityBundle\Entity\User $user
	 */
	public function setUser(\RL\SecurityBundle\Entity\User $user)
	{
		$this->user = $user;
	}
	/**
	 * Get user
	 *
	 * @return RL\SecurityBundle\Entity\User 
	 */
	public function getUser()
	{
		return $this->user;
	}
	/**
	 * Set sessionId
	 *
	 * @param string $sessionId
	 */
	public function setSessionId($sessionId)
	{
		$this->sessionId = $sessionId;
	}
	/**
	 * Get sessionId
	 *
	 * @return string 
	 */
	public function getSessionId()
	{
		return $this->sessionId;
	}
	/**
	 * Set thread
	 *
	 * @param RL\ForumBundle\Entity\Thread $thread
	 */
	public function setThread(\RL\ForumBundle\Entity\Thread $thread)
	{
		$this->thread = $thread;
	}
	/**
	 * Get thread
	 *
	 * @return RL\ForumBundle\Entity\Thread 
	 */
	public function getThread()
	{
		return $this->thread;
	}
}