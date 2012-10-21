<?php
/**
 * @author Tux-oid
 */

namespace RL\SecurityBundle\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class PersonalSettingsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('theme', 'entity', array('class' => 'RLThemesBundle:Theme', 'property'=>'name', 'required' => true))
			->add('mark', 'entity', array('class' => 'RLMainBundle:Mark', 'property'=>'name', 'required' => true))
			->add('gmt', 'timezone', array('required' => true))
			->add('language', 'locale', array('required'=>true))
			->add('newsOnPage', 'text', array('required' => true))
			->add('commentsOnPage', 'text', array('required' => true))
			->add('threadsOnPage', 'text', array('required' => true))
			->add('showAvatars', 'checkbox', array('required' => false))
			->add('showUa', 'checkbox', array('required' => false))
			->add('sortingType', 'choice', array('choices' => array('0' => 'Posting time', '1' => 'Changing time'), 'required' => true,))
			->add('showResp', 'checkbox', array('required' => false));
	}

	public function getName()
	{
		return 'personalSettings';
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
