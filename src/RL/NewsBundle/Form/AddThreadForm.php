<?php
/**
 * @author Tux-oid
 */

namespace RL\NewsBundle\Form;
use Symfony\Component\Validator\Constraints as Assert;
use RL\ForumBundle\Form\AddThreadForm as ForumAddThreadForm;

class AddThreadForm extends ForumAddThreadForm
{
	/**
	 * @Assert\NotBlank()
	 * @Assert\Url
	 */
	protected $prooflink;
	public function __construct($user)
	{
		parent::__construct($user);
	}
	public function getProoflink()
	{
		return $this->prooflink;
	}
	public function setProoflink($prooflink)
	{
		$this->prooflink = $prooflink;
	}


}
?>
