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

use RL\MainBundle\Security\User\RLUserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;
use RL\MainBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use RL\MainBundle\Form\Type\PasswordChangingType;
use RL\MainBundle\Form\Model\PasswordChangingForm;
use RL\MainBundle\Form\Type\PersonalInformationType;
use RL\MainBundle\Form\Type\PersonalSettingsType;
use RL\MainBundle\Form\Type\AdministratorSettingsType;
use RL\MainBundle\Form\Type\FiltersSettingsType;
use RL\MainBundle\Form\Type\MainPageSettingsType;
use LightOpenID;
use Gregwar\ImageBundle\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * RL\MainBundle\Controller\SecurityController
 * Security controller
 *
 * @Route("/user")
 *
 * @author Ax-xa-xa
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/{name}/edit", name="user_edit")
     */
    public function editUserAction($name)
    {
        $user = $this->getCurrentUser();
        $editedUser = $this->getEditedUser($name);
        if (!$this->getSecurityContext()->isGranted('ROLE_MODER')) {
            if ($editedUser->getUsername() != $user->getUsername()) {
                throw new \Exception('You can\'t edit profile of user ' . $name);
            }
        }

        return $this->render(
            'RLMainBundle:Profile:profileEdit.html.twig',
            array(
                'userInfo' => $editedUser,
                'personalInformation' => $this->getPersonalInformationForm($editedUser)->createView(),
                'personalSettings' => $this->getPersonalSettingsForm($editedUser)->createView(),
                'passwordChanging' => $this->getPasswordChangingForm()->createView(),
                'moderatorSettings' => $this->getModeratorSettingsForm($editedUser)->createView(),
                'administratorSettings' => $this->getAdministratorSettingsForm($editedUser)->createView(),
                'filtersSettings' => $this->getFiltersSettingsForm($editedUser)->createView(),
                'mainPageSettings' => $this->getMainPageSettingsForm($editedUser)->createView()
            )
        );
    }

    /**
     * @Route("/{name}/moderator_settings/save", name="save_moderator_settings")
     * @Method("POST")
     */
    public function moderatorSettingsSaveAction($name)
    {
        $editedUser = $this->getEditedUser($name);
        $moderatorSettings = $this->getModeratorSettingsForm($editedUser);
        $moderatorSettings->submit($this->getRequest());
        if ($moderatorSettings->isValid()) {
            if (!$editedUser->isActive()) {
                $mailer = $this->getMailer();
                $mailer->send(
                    $editedUser->getEmail(),
                    $this->getDoctrine()->getRepository('RLMainBundle:Settings')->findOneBy(
                        array("name" => 'site_email')
                    )->getValue(),
                    'Account block',
                    $this->renderView(
                        'RLMainBundle:Security:accountBlockingLetter.html.twig',
                        array('username' => $editedUser->getUsername())
                    )
                );
            }
            $this->getDoctrine()->getManager()->flush();
        } else {
            throw new \Exception('Form is invalid');
        }

        return new RedirectResponse($this->generateUrl('user_edit', array('name' => $name)));
    }

    /**
     * @Route("/{name}/personal_settings/save", name="save_personal_settings")
     * @Method("POST")
     */
    public function personalSettingsSaveAction($name)
    {
        $editedUser = $this->getEditedUser($name);
        $personalSettings = $this->getPersonalSettingsForm($editedUser);
        $personalSettings->submit($this->getRequest());
        if ($personalSettings->isValid()) {
            $this->get('session')->set('_locale', $editedUser->getLanguage());
            $this->getDoctrine()->getManager()->flush();
        } else {
            throw new \Exception('Form is invalid');
        }

        return new RedirectResponse($this->generateUrl('user_edit', array('name' => $name)));
    }

    /**
     * @Route("/{name}/filters_settings/save", name="save_filters_settings")
     * @Method("POST")
     */
    public function filtersSettingsSaveAction($name)
    {
        $editedUser = $this->getEditedUser($name);
        $filtersSettings = $this->getFiltersSettingsForm($editedUser);
        $filtersSettings->submit($this->getRequest());
        if ($filtersSettings->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        } else {
            throw new \Exception('Form is invalid');
        }

        return new RedirectResponse($this->generateUrl('user_edit', array('name' => $name)));
    }

    /**
     * @Route("/{name}/administrator_settings/save", name="save_administrator_settings")
     * @Method("POST")
     */
    public function administratorSettingsSaveAction($name)
    {
        $editedUser = $this->getEditedUser($name);
        $administratorSettings = $this->getAdministratorSettingsForm($editedUser);
        $administratorSettings->submit($this->getRequest());
        if ($administratorSettings->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        } else {
            throw new \Exception('Form is invalid');
        }

        return new RedirectResponse($this->generateUrl('user_edit', array('name' => $name)));
    }

    /**
     * @Route("/{name}/main_page_settings/save", name="save_main_page_settings")
     * @Method("POST")
     */
    public function mainPageSettingsSaveAction($name)
    {
        $editedUser = $this->getEditedUser($name);
        $mainPageSettings = $this->getMainPageSettingsForm($editedUser);
        $mainPageSettings->submit($this->getRequest());
        if ($mainPageSettings->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        } else {
            throw new \Exception('Form is invalid');
        }

        return new RedirectResponse($this->generateUrl('user_edit', array('name' => $name)));
    }

    /**
     * @Route("/{name}/personal_information/save", name="save_personal_information")
     * @Method("POST")
     */
    public function personalInformationSaveAction($name)
    {
        $editedUser = $this->getEditedUser($name);
        $personalInformation = $this->getPersonalInformationForm($editedUser);
        $personalInformation->submit($this->getRequest());
        if ($personalInformation->isValid()) {
            /** @var $photo UploadedFile */
            $photo = $editedUser->getPhoto();
            if (!empty($photo)) {
                $filename = $editedUser->getUsername() . '_' . $photo->getClientOriginalName();
                $photo->move(
                    __DIR__ . '/../../../../web/bundles/rlmain/images/avatars',
                    $filename
                );
                $imgCls = new Image();
                $image = $imgCls->open($photo->getRealPath());
                $width = $image->width();
                $height = $image->height();
                if ($width > 150 || $height > 150) {
                    unlink($photo->getRealPath());
                    throw new \Exception('Image size is very big.');
                }
                $editedUser->setPhoto($filename);
            }
            $editedUser->setAdditional(
                $editedUser->getMark()->render($editedUser->getAdditionalRaw())
            );
            $this->getDoctrine()->getManager()->flush();
        } else {
            throw new \Exception('Form is invalid');
        }

        return new RedirectResponse($this->generateUrl('user_edit', array('name' => $name)));
    }

    /**
     * @Route("/{name}/password_edit/save", name="save_edited_password")
     * @Method("POST")
     */
    public function passwordEditSaveAction($name)
    {
        $editedUser = $this->getEditedUser($name);
        $passwordChanging = $this->getPasswordChangingForm();
        $passwordChanging->submit($this->getRequest());
        if ($passwordChanging->isValid()) {
            $encoder = new MessageDigestPasswordEncoder('md5', false, 1);
            $oldPassword = $encoder->encodePassword(
                $passwordChanging->getData()->getOldPassword(),
                $editedUser->getSalt()
            );
            if ($editedUser->getPassword() !== $oldPassword) {
                throw new \Exception('Your old password is invalid');
            }
            $newPassword = $encoder->encodePassword(
                $passwordChanging->getData()->getNewPassword(),
                $editedUser->getSalt()
            );
            $editedUser->setPassword($newPassword);
            $this->getDoctrine()->getManager()->flush();
        } else {
            throw new \Exception('Form is invalid');
        }

        return new RedirectResponse($this->generateUrl('user_edit', array('name' => $name)));
    }

    /**
     * @Route("/{name}", name="user")
     */
    public function userAction($name)
    {
        /** @var $userRepository \RL\MainBundle\Entity\Repository\UserRepository */
        $userRepository = $this->getDoctrine()->getRepository('RLMainBundle:User');
        $editedUser = $this->getEditedUser($name);
        $userComments = $userRepository->getUserCommentsInformation($editedUser);

        return $this->render(
            'RLMainBundle:Profile:profile.html.twig',
            array('userInfo' => $editedUser, 'commentsInfo' => $userComments,)
        );
    }

    /**
     * @param $name
     * @return \RL\MainBundle\Security\User\RLUserInterface
     * @throws \Exception
     */
    public function getEditedUser($name)
    {
        if ($name == 'anonymous' && $this->getCurrentUser()->isAnonymous()) {
            $editedUser = $this->getCurrentUser();
        } else {
            $userRepository = $this->getDoctrine()->getRepository('RLMainBundle:User');
            $editedUser = $userRepository->findOneBy(array("username" => $name));
            if (empty($editedUser)) {
                throw new \Exception('User ' . $name . ' not found');
            }
        }

        return $editedUser;
    }

    /**
     * @param RLUserInterface $editedUser
     * @return \Symfony\Component\Form\Form
     */
    public function getModeratorSettingsForm(RLUserInterface $editedUser)
    {
        return $this->createForm($this->get("rl_main.form.moderator_settings"), $editedUser);
    }

    /**
     * @param RLUserInterface $editedUser
     * @return \Symfony\Component\Form\Form
     */
    public function getPersonalSettingsForm(RLUserInterface $editedUser)
    {
        return $this->createForm(new PersonalSettingsType(), $editedUser);
    }

    /**
     * @param RLUserInterface $editedUser
     * @return \Symfony\Component\Form\Form
     */
    public function getFiltersSettingsForm(RLUserInterface $editedUser)
    {
        return $this->createForm(new FiltersSettingsType(), $editedUser);
    }

    /**
     * @param RLUserInterface $editedUser
     * @return \Symfony\Component\Form\Form
     */
    public function getAdministratorSettingsForm(RLUserInterface $editedUser)
    {
        return $this->createForm(new AdministratorSettingsType(), $editedUser);
    }

    /**
     * @param RLUserInterface $editedUser
     * @return \Symfony\Component\Form\Form
     */
    public function getMainPageSettingsForm(RLUserInterface $editedUser)
    {
        return $this->createForm(new MainPageSettingsType(), $editedUser);
    }

    /**
     * @param RLUserInterface $editedUser
     * @return \Symfony\Component\Form\Form
     */
    public function getPersonalInformationForm(RLUserInterface $editedUser)
    {
        return $this->createForm(new PersonalInformationType(), $editedUser);
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    public function getPasswordChangingForm()
    {
        return $this->createForm(new PasswordChangingType(), new PasswordChangingForm());
    }
}
