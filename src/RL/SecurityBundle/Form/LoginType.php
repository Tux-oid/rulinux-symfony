<?php
/**
 *@author Ax-xa-xa 
 */
namespace RL\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class LoginType  extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('_username', 'text')
            ->add('_password', 'password')
            ->add('_remember_me', 'checkbox', array('required'=>false))
        ;
    }

    public function getName()
    {
        return 'login';
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
