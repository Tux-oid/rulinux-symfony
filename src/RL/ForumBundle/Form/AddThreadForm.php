<?php
/**
 * @author Tux-oid
 */

namespace RL\ForumBundle\Form;
use Symfony\Component\Validator\Constraints as Assert;
//use Symfony\Component\Form\FormView;

class AddThreadForm //extends FormView
{
	/**
	 * @Assert\NotBlank()
	 */
	protected $subject;
	/**
	 * @Assert\NotBlank()
	 */
	protected $comment;
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


}
?>
