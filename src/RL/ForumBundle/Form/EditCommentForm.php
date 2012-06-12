<?php
/**
 * @author Tux-oid
 */

namespace RL\ForumBundle\Form;
use Symfony\Component\Validator\Constraints as Assert;

class EditCommentForm
{
	/**
	 * @Assert\NotBlank()
	 */
	protected $subject;
	/**
	 * @Assert\NotBlank()
	 */
	protected $comment;
	protected $editionReason;
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
	public function getEditionReason()
	{
		return $this->editionReason;
	}
	public function setEditionReason($editionReason)
	{
		$this->editionReason = $editionReason;
	}

}
?>
