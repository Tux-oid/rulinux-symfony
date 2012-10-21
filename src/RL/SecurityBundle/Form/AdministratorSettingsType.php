<?php
/**
 * @author Tux-oid
 */
namespace RL\SecurityBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdministratorSettingsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('group', 'entity', array('class' => 'RLSecurityBundle:Group', 'property'=>'name', /*'multiple'=>true,*/ 'required' => true));
	}
	/**
	 * Returns the name of this type.
	 *
	 * @return string The name of this type
	 */
	function getName()
	{
		return 'administratorSettings';
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
