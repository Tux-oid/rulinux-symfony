<?php
/**
 * @author Tux-oid
 */
namespace RL\SecurityBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PasswordChangingType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->add('oldPassword', 'password')
			->add('newPassword', 'password')
			->add('validation', 'password');
	}
	/**
	 * Returns the name of this type.
	 *
	 * @return string The name of this type
	 */
	function getName()
	{
		return 'passwordChanging';
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
