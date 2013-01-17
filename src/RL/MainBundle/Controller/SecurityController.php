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
use RL\MainBundle\Form\Type\RegistrationFirstType;
use RL\MainBundle\Form\Model\RegistrationFirstForm;
use RL\MainBundle\Form\Type\PasswordRestoringType;
use RL\MainBundle\Form\Model\PasswordRestoringForm;
use RL\MainBundle\Entity\User;
use RL\MainBundle\Form\Type\RegisterType;
use RL\MainBundle\Helper\Pages;
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
class SecurityController extends Controller
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
        $theme = $this->get('rl_main.theme.provider');
        $request = $this->getRequest();
        $session = $request->getSession();
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            $theme->getPath('login.html.twig'), array('last_username' => $session->get(SecurityContext::LAST_USERNAME), 'error' => $error,)
        );
    }

    /**
     * @Route("/register", name="register")
     */
    public function registrationAction()
    {
        $newUser = new RegistrationFirstForm;
        $theme = $this->get('rl_main.theme.provider');
        $form = $this->createForm(new RegistrationFirstType(), $newUser);

        return $this->render(
            $theme->getPath('registrationFirstPage.html.twig'), array('form' => $form->createView())
        );
    }

    /**
     * @Route("/register_send", name="register_send")
     */
    public function registrationSendAction(Request $request)
    {
        $theme = $this->get('rl_main.theme.provider');
        $method = $request->getMethod();
        if ($method == 'POST') {
            $newUser = new RegistrationFirstForm;
            $form = $this->createForm(new RegistrationFirstType(), $newUser);
            $form->bind($request);
            if ($form->isValid()) {
                $mailer = $this->get('mailer');
                $secret = $this->container->getParameter('secret');
                $link = $this->generateUrl('register_confirm', array('username' => $newUser->getName(), 'password' => $newUser->getPassword(), 'email' => $newUser->getEmail(), 'hash' => md5(md5($newUser->getName() . $newUser->getPassword() . $newUser->getEmail() . $secret))), true);
                $user = $newUser->getName();
                $site = $request->getHttpHost();
                $message = \Swift_Message::newInstance()->setSubject('Registration letter')->setFrom('noemail@rulinux.net') //FIXME:load email from settings
                    ->setTo($newUser->getEmail())->setContentType('text/html')->setBody($this->renderView($theme->getPath('registrationLetter.html.twig'), array('link' => $link, 'user' => $user, 'site' => $site)));
                $mailer->send($message);
                $legend = 'Registration mail is sent.';
                $text = 'Registration mail is sent. Please check your email.';
                $title = 'Mail sent';

                return $this->render(
                    $theme->getPath('fieldset.html.twig'), array('legend' => $legend, 'text' => $text, 'title' => $title)
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
        $theme = $this->get('rl_main.theme.provider');
        $secret = $this->container->getParameter('secret');
        if ($hash == md5(md5($username . $password . $email . $secret))) {
            $newUser = new User;
            $form = $this->createForm(new RegisterType(), $newUser);

            return $this->render(
                $theme->getPath('registrationSecondPage.html.twig'), array('username' => $username, 'password' => $password, 'email' => $email, 'form' => $form->createView())
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
        $theme = $this->get('rl_main.theme.provider');
        $method = $request->getMethod();
        if ($method == 'POST') {
            $newUser = new User;
            $form = $this->createForm(new RegisterType(), $newUser);
            $form->bind($request);
            if ($form->isValid()) {
                $settingsRepository = $this->getDoctrine()->getRepository('RLMainBundle:Settings');
                $groupsRepository = $this->getDoctrine()->getRepository('RLMainBundle:Group');
                $userRole = $groupsRepository->findOneByName('ROLE_USER');
                //TODO: set additional marked
                //TODO: save image file
                $newUser->setAdditionalRaw($newUser->getAdditional());
                $newUser->setRegistrationDate(new \DateTime('now'));
                $newUser->setLastVisitDate(new \DateTime('now'));
                $newUser->setCaptchaLevel($settingsRepository->findOneByName('captchaLevel')->getValue());
                $newUser->setTheme($settingsRepository->findOneByName('theme')->getValue());
                $newUser->setSortingType($settingsRepository->findOneByName('sortingType')->getValue());
                $newUser->setNewsOnPage($settingsRepository->findOneByName('newsOnPage')->getValue());
                $newUser->setThreadsOnPage($settingsRepository->findOneByName('threadsOnPage')->getValue());
                $newUser->setCommentsOnPage($settingsRepository->findOneByName('commentsOnPage')->getValue());
                $newUser->setShowEmail($settingsRepository->findOneByName('showEmail')->getValue());
                $newUser->setShowIm($settingsRepository->findOneByName('showIm')->getValue());
                $newUser->setShowAvatars($settingsRepository->findOneByName('showAvatars')->getValue());
                $newUser->setShowUa($settingsRepository->findOneByName('showUa')->getValue());
                $newUser->setShowResp($settingsRepository->findOneByName('showResp')->getValue());
                $encoder = new MessageDigestPasswordEncoder('md5', false, 1);
                $password = $encoder->encodePassword($newUser->getPassword(), $newUser->getSalt());
                $newUser->setPassword($password);
                $newUser->getGroups()->add($userRole);
                $em = $this->getDoctrine()->getManager();
                $em->persist($newUser);
                $em->flush();
                $legend = 'Registration completed.';
                $text = 'Registration completed. Now you can <a href="' . $this->generateUrl('login') . '">login</a> on this site.';
                $title = '';

                return $this->render(
                    $theme->getPath('fieldset.html.twig'), array('legend' => $legend, 'text' => $text, 'title' => $title)
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
        $theme = $this->get('rl_main.theme.provider');
        $resForm = new PasswordRestoringForm;
        $form = $this->createForm(new PasswordRestoringType(), $resForm);

        return $this->render(
            $theme->getPath('passwordRestoringForm.html.twig'), array('form' => $form->createView())
        );
    }

    /**
     * @Route("/restore_password_check", name="restore_password_check")
     */
    public function restorePasswordCheckAction(Request $request)
    {
        $theme = $this->get('rl_main.theme.provider');
        $method = $request->getMethod();
        if ($method == 'POST') {
            $resForm = new PasswordRestoringForm;
            $form = $this->createForm(new PasswordRestoringType(), $resForm);
            $form->bind($request);
            if ($form->isValid()) {
                /** @var $userRepository \Doctrine\ORM\EntityRepository */
                $userRepository = $this->getDoctrine()->getRepository('RLMainBundle:User');
                /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
                try {
                    $user = $userRepository->findOneByUsername($resForm->getUsername());
                } catch (\ErrorException $e) {
                    throw new \Exception($e->getMessage());
                }
                if ($user->getEmail() != $resForm->getEmail()) {
                    throw new \Exception('Email is wrong');
                }
                if ($user->getQuestion() != $resForm->getQuestion()) {
                    throw new \Exception('Question is wrong');
                }
                if ($user->getAnswer() != $resForm->getAnswer()) {
                    throw new \Exception('Answer is wrong');
                }
                $password = md5(uniqid(rand(), true));
                $password = substr($password, 1, 9);
                $encoder = new MessageDigestPasswordEncoder('md5', false, 1);
                $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
                $user->setPassword($encodedPassword);
                $username = $user->getUsername();
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $mailer = $this->get('mailer');
                $message = \Swift_Message::newInstance()->setSubject('Password restoring')->setFrom('noemail@rulinux.net') //FIXME:load email from settings
                    ->setTo($resForm->getEmail())->setContentType('text/html')->setBody($this->renderView($theme->getPath('RLMainBundle', 'passwordRestoringLetter.html.twig'), array('username' => $username, 'password' => $password)));
                $mailer->send($message);
                $legend = 'Mail with your new password is sended.';
                $text = 'Mail with your new password is sended. Please check your email.';
                $title = 'Mail sended';

                return $this->render(
                    $theme->getPath('fieldset.html.twig'), array('legend' => $legend, 'text' => $text, 'title' => $title)
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
        $theme = $this->get('rl_main.theme.provider');
        $user = $this->get('security.context')->getToken()->getUser();
        $userRepository = $this->getDoctrine()->getRepository('RLMainBundle:User');

        $itemsCount = count($userRepository->findAll());
        $itemsOnPage = $user->getCommentsOnPage();
        $pagesCount = ceil(($itemsCount) / $itemsOnPage);
        $pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
        $users = $userRepository->findBy(array(), null, $user->getCommentsOnPage(), $offset);
        $pages = new Pages($this->get('router'), $itemsOnPage, $itemsCount, $page, 'users', array("page" => $page));
        $pagesStr = $pages->draw();

        return $this->render($theme->getPath('users.html.twig'), array('users' => $users, 'pagesStr' => $pagesStr));
    }

}