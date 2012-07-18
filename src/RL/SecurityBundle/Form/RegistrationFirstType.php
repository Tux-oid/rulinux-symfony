<?php
/**
 * @author Tux-oid 
 */

namespace RL\SecurityBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class RegisterFirstType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->add('name', 'text')
			->add('password', 'password')
			->add('validation', 'password')
			->add('email', 'email');
	}
	public function getName()
	{
		return 'registerFirst';
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
