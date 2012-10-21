<?php
/**
 * @author Tux-oid
 */
namespace RL\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ModeratorSettingsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('active', 'checkbox', array('required' => false))
		->add('captchaLevel', 'text', array('required' => true));//TODO:set choice type
	}
	/**
	 * Returns the name of this type.
	 *
	 * @return string The name of this type
	 */
	function getName()
	{
		return 'moderatorSettings';
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
