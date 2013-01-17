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

namespace RL\MainBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use RL\MainBundle\Form\Type\PasswordChangingType;
use RL\MainBundle\Form\Model\PasswordChangingForm;
use RL\MainBundle\Form\Type\PersonalInformationType;
use RL\MainBundle\Form\Type\PersonalSettingsType;
use RL\MainBundle\Form\Type\AdministratorSettingsType;
use RL\MainBundle\Form\Type\ModeratorSettingsType;
use RL\MainBundle\Form\Type\FiltersSettingsType;
use RL\MainBundle\Form\Model\FiltersSettingsForm;
use LightOpenID;
use Gregwar\ImageBundle\Image;

/**
 * RL\MainBundle\Controller\SecurityController
 * Security controller
 *
 * @author Ax-xa-xa
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ProfileController extends Controller
{
    /**
     * @Route("/user/{name}/edit", name="user_edit")
     */
    public function editUserAction($name)
    {
        $theme = $this->get('rl_main.theme.provider');
        /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
        $user = $this->get('security.context')->getToken()->getUser();
        /** @var $userInProfile \RL\MainBundle\Security\User\RLUserInterface */
        if ($name == 'anonymous' && $user->isAnonymous()) {
            $userInProfile = $this->get('security.context')->getToken()->getUser();
        } else {
            $userRepository = $this->getDoctrine()->getRepository('RLMainBundle:User');
            $userInProfile = $userRepository->findOneByUsername($name);
        }
        if (!$this->get('security.context')->isGranted('ROLE_MODER')) {
            if ($userInProfile->getUsername() != $user->getUsername()) {
                throw new \Exception('You can\'t edit profile of user ' . $name);
            }
        }
        if (empty($userInProfile)) {
            throw new \Exception('User ' . $name . ' not found');
        }
        //show info
        $passwordChangingForm = new PasswordChangingForm();
        $passwordChanging = $this->createForm(new PasswordChangingType(), $passwordChangingForm);
        $filtersSettings = $this->createForm(new FiltersSettingsType(), new FiltersSettingsForm());
        $personalSettings = $this->createForm(new PersonalSettingsType(), $userInProfile);
        $personalInformation = $this->createForm(new PersonalInformationType(), $userInProfile);
        $moderatorSettings = $this->createForm(new ModeratorSettingsType(), $userInProfile);
        $administratorSettings = $this->createForm(new AdministratorSettingsType(), $userInProfile);
        //save settings in database
        $request = $this->getRequest();
        $sbm = $request->request->get('sbm');
        if (isset($sbm)) {
            $method = $request->getMethod();
            if ($method == 'POST') {
                //save password
                if ($request->request->get('action') == 'passwordChanging') {
                    $passwordChanging->bind($request);
                    if ($passwordChanging->isValid()) {
                        $encoder = new MessageDigestPasswordEncoder('md5', false, 1);
                        $oldPassword = $encoder->encodePassword(
                            $passwordChangingForm->getOldPassword(),
                            $userInProfile->getSalt()
                        );
                        if ($userInProfile->getPassword() !== $oldPassword) {
                            throw new \Exception('Your old password is invalid');
                        }
                        if ($passwordChangingForm->getNewPassword() !== $passwordChangingForm->getValidation()) {
                            throw new \Exception('Your new password does not match with validation');
                        }
                        $newPassword = $encoder->encodePassword(
                            $passwordChangingForm->getNewPassword(),
                            $userInProfile->getSalt()
                        );
                        $userInProfile->setPassword($newPassword);
                        $this->getDoctrine()->getManager()->flush();
                    } else {
                        throw new \Exception('Form is invalid');
                    }
                } //save personal information
                elseif ($request->request->get('action') == 'personalInformation') {
                    $personalInformation->bind($request);
                    if ($personalInformation->isValid()) {
                        $photo = $userInProfile->getPhoto();
                        if (!empty($photo)) {
                            $filename = $userInProfile->getUsername() . '_' . $photo->getClientOriginalName();
                            $userInProfile->getPhoto()->move(
                                __DIR__ . '/../../../../web/bundles/rlmain/images/avatars',
                                $filename
                            );
                            $absolutePath = __DIR__ . '/../../../../web/bundles/rlmain/images/avatars/' . $filename;
                            $imgCls = new Image();
                            $image = $imgCls->open($absolutePath);
                            $width = $image->width();
                            $height = $image->height();
                            if ($width > 150 || $height > 150) {
                                unlink($absolutePath);
                                throw new \Exception('Image size is very big.');
                            }
                            $userInProfile->setPhoto($filename);
                        }
                        $userInProfile->setAdditional(
                            $user->getMark()->render($userInProfile->getAdditionalRaw())
                        );
                        $this->getDoctrine()->getManager()->flush();
                    } else {
                        throw new \Exception('Form is invalid');
                    }
                } //save personal settings
                elseif ($request->request->get('action') == 'personalSettings') {
                    $personalSettings->bind($request);
                    if ($personalSettings->isValid()) {
                        $this->get('session')->set('_locale', $userInProfile->getLanguage());
                        $this->getDoctrine()->getManager()->flush();
                    } else {
                        throw new \Exception('Form is invalid');
                    }
                } //save moderator settings
                elseif ($request->request->get('action') == 'moderatorSettings') {
                    $moderatorSettings->bind($request);
                    if ($moderatorSettings->isValid()) {
                        //TODO: when inactive send message to user
                        $this->getDoctrine()->getManager()->flush();
                    } else {
                        throw new \Exception('Form is invalid');
                    }
                } //save administrator settings
                elseif ($request->request->get('action') == 'administratorSettings') {
                    $administratorSettings->bind($request);
                    if ($administratorSettings->isValid()) {
                        $this->getDoctrine()->getManager()->flush();
                    } else {
                        throw new \Exception('Form is invalid');
                    }
                }
            }
        }

        return $this->render(
            $theme->getPath('profileEdit.html.twig'),
            array(
                'userInfo' => $userInProfile,
                'personalInformation' => $personalInformation->createView(),
                'personalSettings' => $personalSettings->createView(),
                'passwordChanging' => $passwordChanging->createView(),
                'moderatorSettings' => $moderatorSettings->createView(),
                'administratorSettings' => $administratorSettings->createView(),
                'filtersSettings' => $filtersSettings->createView()
            )
        );
    }

    /**
     * @Route("/user/{name}", name="user")
     */
    public function userAction($name)
    {
        $theme = $this->get('rl_main.theme.provider');
        $userRepository = $this->getDoctrine()->getRepository('RLMainBundle:User');
        $userInProfile = $userRepository->findOneByUsername($name);
        if (empty($userInProfile)) {
            throw new \Exception('user ' . $name . ' not found');
        }
        $userComments = $userRepository->getUserCommentsInformation($userInProfile);

        return $this->render(
            $theme->getPath('profile.html.twig'),
            array('userInfo' => $userInProfile, 'commentsInfo' => $userComments,)
        );
    }

}