<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class EditCommentType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('subject', 'text', array('required' => true))
			->add('comment', 'textarea', array('required' => true))
			->add('editionReason', 'text', array('required' => false));
	}
	public function getName()
	{
		return 'editComment';
	}
	public function getDefaultOptions(array $options)
	{
		return array(
			'csrf_protection' => true,
			'csrf_field_name' => '_csrf_token',
			'intention' => 'authenticate'
		);
	}
}
?>
