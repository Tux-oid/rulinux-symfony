<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class AddCommentType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->add('subject', 'text', array('required' => true))
			->add('comment', 'textarea', array('required' => true));
	}
	public function getName()
	{
		return 'addComment';
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
