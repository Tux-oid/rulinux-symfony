<?php
/**
 * @author Tux-oid
 */

namespace RL\SecurityBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class PersonalInformationType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->add('name', 'text', array('required' => false))
			->add('openid', 'text', array('required' => false))
			->add('lastname', 'text', array('required' => false))
			->add('photo', 'file', array('required' => false))
			->add('gender', 'choice', array('choices' => array('1' => 'Male', '0' => 'Female'), 'required' => true,))
			->add('birthday', 'date', array('input'  => 'datetime', 'widget' => 'choice', 'required' => false,))
			->add('email', 'email', array('required' => false))
			->add('im', 'email', array('required' => false))
			->add('showEmail', 'checkbox', array('required' => false))
			->add('showIm', 'checkbox', array('required' => false))
			->add('country', 'country', array('required' => true))
			->add('city', 'text', array('required' => false))
			->add('additional', 'textarea', array('required' => false));
	}

	public function getName()
	{
		return 'personalInformation';
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
