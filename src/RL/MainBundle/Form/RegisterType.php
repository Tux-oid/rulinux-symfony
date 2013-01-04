<?php
/**
 * Copyright (c) 2008 - 2012, Peter Vasilevsky
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the RL nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL PETER VASILEVSKY BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

namespace RL\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * RL\MainBundle\Form\RegisterType
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
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
