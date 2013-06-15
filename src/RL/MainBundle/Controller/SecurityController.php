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
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use RL\MainBundle\Form\Type\RegistrationFirstType;
use RL\MainBundle\Form\Model\RegistrationFirstForm;
use RL\MainBundle\Form\Type\PasswordRestoringType;
use RL\MainBundle\Form\Model\PasswordRestoringForm;
use RL\MainBundle\Entity\User;
use RL\MainBundle\Form\Type\RegisterType;
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
class SecurityController extends AbstractController
{
    /**
     * Declares Symfony Security routes.
     *
     * @Route("/logout", name="logout")
     * @Route("/login_check", name="login_check")
     */
    public function fakeAction()
    {

    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'RLMainBundle:Security:login.html.twig',
            array('last_username' => $session->get(SecurityContext::LAST_USERNAME), 'error' => $error,)
        );
    }

    /**
     * @Route("/register", name="register")
     */
    public function registrationAction()
    {
        $newUser = new RegistrationFirstForm;
        $form = $this->createForm(new RegistrationFirstType(), $newUser);

        return $this->render(
            'RLMainBundle:Security:registrationFirstPage.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/register_send", name="register_send")
     */
    public function registrationSendAction(Request $request)
    {
        $method = $request->getMethod();
        if ($method == 'POST') {
            $newUser = new RegistrationFirstForm;
            $form = $this->createForm(new RegistrationFirstType(), $newUser);
            $form->submit($request);
            if ($form->isValid()) {
                $mailer = $this->getMailer();
                $mailer->send(
                    $newUser->getEmail(),
                    $this->getDoctrine()->getRepository('RLMainBundle:Settings')->findOneBy(
                        array('name' => 'site_email')
                    )->getValue(),
                    'Registration letter',
                    $this->renderView(
                        'RLMainBundle:Security:registrationLetter.html.twig',
                        array(
                            'link' => $this->generateUrl(
                                'register_confirm',
                                array(
                                    'username' => $newUser->getName(),
                                    'password' => $newUser->getPassword(),
                                    'email' => $newUser->getEmail(),
                                    'hash' => md5(
                                        md5(
                                            $newUser->getName() . $newUser->getPassword() . $newUser->getEmail(
                                            ) . $this->container->getParameter('secret')
                                        )
                                    )
                                ),
                                true
                            ),
                            'user' => $newUser->getName(),
                            'site' => $request->getHttpHost()
                        )
                    )
                );

                return $this->renderMessage(
                    'Registration mail was sent.',
                    'Registration mail was sent. Please check your email.'
                );
            } else {
                throw new \Exception('Registration form is invalid.');
            }
        } else {
            return $this->redirect($this->generateUrl('index'));
        }
    }

    /**
     * @Route("/register_confirm/{username}/{password}/{email}/{hash}", name="register_confirm")
     */
    public function registrationConfirmAction($username, $password, $email, $hash)
    {
        $secret = $this->container->getParameter('secret');
        if ($hash == md5(md5($username . $password . $email . $secret))) {
            $newUser = new User;
            $form = $this->createForm(new RegisterType(), $newUser);

            return $this->render(
                'RLMainBundle:Security:registrationSecondPage.html.twig',
                array(
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'form' => $form->createView()
                )
            );
        } else {
            throw new \Exception('Hash is invalid');
        }
    }

    /**
     * @Route("/registration_save", name="registration_save")
     */
    public function registrationSaveAction(Request $request)
    {
        $method = $request->getMethod();
        if ($method == 'POST') {
            $newUser = new User;
            $form = $this->createForm(new RegisterType(), $newUser);
            $form->submit($request);
            if ($form->isValid()) {
                $settingsRepository = $this->getDoctrine()->getRepository('RLMainBundle:Settings');
                $groupsRepository = $this->getDoctrine()->getRepository('RLMainBundle:Group');
                /** @var $userRole \RL\MainBundle\Entity\Group */
                $userRole = $groupsRepository->findOneBy(array('name' => 'ROLE_USER'));
                //TODO: set additional marked
                //TODO: save image file
                $newUser->setAdditionalRaw($newUser->getAdditional());
                $newUser->setRegistrationDate(new \DateTime('now'));
                $newUser->setLastVisitDate(new \DateTime('now'));
                $newUser->setCaptchaLevel($settingsRepository->findOneBy(array('name' => 'captchaLevel'))->getValue());
                $newUser->setTheme($settingsRepository->findOneBy(array('name' => 'theme'))->getValue());
                $newUser->setSortingType($settingsRepository->findOneBy(array('name' => 'sortingType'))->getValue());
                $newUser->setNewsOnPage($settingsRepository->findOneBy(array('name' => 'newsOnPage'))->getValue());
                $newUser->setThreadsOnPage(
                    $settingsRepository->findOneBy(array('name' => 'threadsOnPage'))->getValue()
                );
                $newUser->setCommentsOnPage(
                    $settingsRepository->findOneBy(array('name' => 'commentsOnPage'))->getValue()
                );
                $newUser->setShowEmail($settingsRepository->findOneBy(array('name' => 'showEmail'))->getValue());
                $newUser->setShowIm($settingsRepository->findOneBy(array('name' => 'showIm'))->getValue());
                $newUser->setShowAvatars($settingsRepository->findOneBy(array('name' => 'showAvatars'))->getValue());
                $newUser->setShowUa($settingsRepository->findOneBy(array('name' => 'showUa'))->getValue());
                $newUser->setShowResp($settingsRepository->findOneBy(array('name' => 'showResp'))->getValue());
                $encoder = new MessageDigestPasswordEncoder('md5', false, 1);
                $password = $encoder->encodePassword($newUser->getPassword(), $newUser->getSalt());
                $newUser->setPassword($password);
                $newUser->setGroup($userRole);
                $em = $this->getDoctrine()->getManager();
                $em->persist($newUser);
                $em->flush();

                return $this->renderMessage(
                    'Registration complete.',
                        'Registration complete. Now you can <a href="' . $this->generateUrl(
                            'login'
                        ) . '">login</a> on this site.'
                );
            } else {
                throw new \Exception('Registration form is invalid.');
            }
        } else {
            return $this->redirect($this->generateUrl('index'));
        }
    }

    /**
     * @Route("/restore_password", name="restore_password")
     */
    public function restorePasswordAction()
    {
        $resForm = new PasswordRestoringForm;
        $form = $this->createForm(new PasswordRestoringType(), $resForm);

        return $this->render(
            'RLMainBundle:Security:passwordRestoringForm.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/restore_password_check", name="restore_password_check")
     */
    public function restorePasswordCheckAction(Request $request)
    {
        $method = $request->getMethod();
        if ($method == 'POST') {
            $resForm = new PasswordRestoringForm;
            $form = $this->createForm(new PasswordRestoringType(), $resForm);
            $form->submit($request);
            if ($form->isValid()) {
                /** @var $userRepository \Doctrine\ORM\EntityRepository */
                $userRepository = $this->getDoctrine()->getRepository('RLMainBundle:User');
                /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
                try {
                    $user = $userRepository->findOneBy(array('username' => $resForm->getUsername()));
                } catch(\ErrorException $e) {
                    throw new \Exception($e->getMessage());
                }
                if ($user->getEmail() != $resForm->getEmail()) {
                    return $this->renderMessage(
                        'Email is wrong',
                        'Please make sure that you entered correct address in email field'
                    );
                }
                if ($user->getQuestion() != $resForm->getQuestion()) {
                    return $this->renderMessage(
                        'Question is wrong',
                        'Please make sure that you entered correct question'
                    );
                }
                if ($user->getAnswer() != $resForm->getAnswer()) {
                    return $this->renderMessage('Answer is wrong', 'Please make sure that you entered correct answer');
                }
                $password = md5(uniqid(rand(), true));
                $password = substr($password, 1, 9);
                $encoder = new MessageDigestPasswordEncoder('md5', false, 1);
                $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
                $user->setPassword($encodedPassword);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $mailer = $this->getMailer();
                $mailer->send(
                    $resForm->getEmail(),
                    $this->getDoctrine()->getRepository('RLMainBundle:Settings')->findOneBy(
                        array('name' => 'site_email')
                    )->getValue(),
                    'Password restoring',
                    $this->renderView(
                        'RLMainBundle:Security:passwordRestoringLetter.html.twig',
                        array('username' => $user->getUsername(), 'password' => $password)
                    )
                );

                return $this->renderMessage(
                    'Mail with your new password was sent.',
                    'Mail with your new password was sent. Please check your email.'
                );
            } else {
                throw new \Exception('Password restoring form is invalid');
            }
        } else {
            return $this->redirect($this->generateUrl('index'));
        }
    }

    /**
     * @Route("/users/{page}", name="users", defaults={"page" = 1})
     */
    public function usersAction($page)
    {
        $user = $this->getCurrentUser();
        $userRepository = $this->getDoctrine()->getRepository('RLMainBundle:User');
        $itemsCount = count($userRepository->findAll());
        $itemsOnPage = $user->getCommentsOnPage();
        $pagesCount = ceil(($itemsCount) / $itemsOnPage);
        $pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
        $users = $userRepository->findBy(array(), null, $user->getCommentsOnPage(), $offset);
        $pagesStr = $this->getPaginator()->draw(
            $itemsOnPage,
            $itemsCount,
            $page,
            'users',
            array("page" => $page)
        );

        return $this->render(
            'RLMainBundle:Security:users.html.twig',
            array('users' => $users, 'pagesStr' => $pagesStr)
        );
    }

}
