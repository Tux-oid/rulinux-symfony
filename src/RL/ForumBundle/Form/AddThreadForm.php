<?php
/**
 * @author Tux-oid
 */

namespace RL\ForumBundle\Form;
use Symfony\Component\Validator\Constraints as Assert;

class AddThreadForm
{
	/**
	 * @Assert\NotBlank()
	 */
	protected $subject;
	/**
	 * @Assert\NotBlank()
	 */
	protected $comment;
	protected $useragent;
	protected $postingTime;
	protected $author;
	public function __construct($user)
	{
		 $this->useragent = $_SERVER['HTTP_USER_AGENT'];
		 $this->postingTime = new \DateTime('now');
		 $this->author = $user;
	}
	public function getSubject()
	{
		return $this->subject;
	}
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}
	public function getComment()
	{
		return $this->comment;
	}
	public function setComment($comment)
	{
		$this->comment = $comment;
	}
	public function getUseragent()
	{
		return $this->useragent;
	}
	public function setUseragent($useragent)
	{
		$this->useragent = $useragent;
	}
	public function getPostingTime()
	{
		return $this->postingTime;
	}
	public function setPostingTime($postingTime)
	{
		$this->postingTime = $postingTime;
	}
	public function getAuthor()
	{
		return $this->author;
	}
	public function setAuthor($author)
	{
		$this->author = $author;
	}



}
?>
