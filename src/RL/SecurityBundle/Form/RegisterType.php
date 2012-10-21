<?php
/**
 * @author Tux-oid 
 */

namespace RL\SecurityBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('username', 'text', array('required' => true))
			->add('password', 'password', array('required' => true))
			->add('name', 'text', array('required' => false))
			->add('lastname', 'text', array('required' => false))
			->add('country', 'country', array('required' => true))
			->add('city', 'text', array('required' => false))
			->add('photo', 'file', array('required' => false))
			->add('birthday', 'date', array('input'  => 'datetime', 'widget' => 'choice', 'required' => true))
			->add('gender', 'choice', array('choices' => array('1' => 'Male', '0' => 'Female'), 'required' => true,))
			->add('additional', 'textarea', array('required' => false))
			->add('email', 'email', array('required' => true))
			->add('im', 'email', array('required' => false))
			->add('openid', 'text', array('required' => false))
			->add('language', 'language', array('required' => true))
			->add('gmt', 'timezone', array('required' => true))
			->add('question', 'text', array('required' => true))
			->add('answer', 'text', array('required' => true));
	}
	public function getName()
	{
		return 'register';
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
