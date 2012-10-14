<?php
/**
 * @author Tux-oid 
 */

namespace RL\SecurityBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PasswordPestoringType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->add('username', 'text')
			->add('email', 'email')
			->add('question', 'text')
			->add('answer', 'text');
	}
	public function getName()
	{
		return 'restorePassword';
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
