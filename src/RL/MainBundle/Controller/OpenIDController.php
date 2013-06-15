<?php
/**
 * Copyright (c) 2009 - 2012, Peter Vasilevsky
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

namespace RL\MainBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use RL\MainBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use RL\MainBundle\Entity\User;
use RL\MainBundle\Form\Type\RegisterType;
use LightOpenID;
use Gregwar\ImageBundle\Image;

/**
 * RL\MainBundle\Controller\OpenIDController
 * Security controller
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class OpenIDController extends AbstractController
{
    /**
     * @Route("/openid_check", name="openid_check")
     */
    public function openidCheckAction(Request $request)
    {
        try {
            $openid = new \LightOpenID($request->getHttpHost());
            if (!$openid->mode) {
                $identifier = $request->request->get('openid_identifier');
                if (isset($identifier)) {
                    $openid->identity = $identifier;
                    $openid->required = array('contact/email');
                    $openid->optional = array('namePerson', 'namePerson/friendly');

                    return $this->redirect($openid->authUrl());
                } else {
                    throw new \Exception('OpenID identifier is empty');
                }
            } elseif ($openid->mode == 'cancel') {
                return $this->redirect($this->generateUrl('login'));
            } else {
                $userRepository = $this->getDoctrine()->getRepository('RLMainBundle:User');
                if ($openid->validate()) {
                    $identity = $openid->identity;
                    $identity = preg_replace('#^http://(.*)#sim', '$1', $identity);
                    $identity = preg_replace('#^https://(.*)#sim', '$1', $identity);
                    $identity = preg_replace('#(.*)\/$#sim', '$1', $identity);
                    $user = $userRepository->findOneBy(array("openid" => $identity));
                    if (isset($user)) {
                        //FIXME: login user by openid
                        return $this->renderMessage('login user', 'login user');
                    } else {
                        $attr = $openid->getAttributes();
                        $email = '';
                        $newUser = new User;
                        $form = $this->createForm(new RegisterType(), $newUser);

                        return $this->render(
                            'RLMainBundle:OpenID:openIDRegistration.html.twig',
                            array(
                                'openid' => $identity,
                                'password' => '',
                                'email' => $email,
                                'form' => $form->createView()
                            )
                        );
                    }
                } else {
                    throw new \Exception('OpenID is invalid');
                }
            }
        } catch(\ErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
