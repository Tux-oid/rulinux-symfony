<?php
/**
 * @author Ax-xa-xa
 * @author Tux-oid
 */

namespace RL\SecurityBundle\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use RL\SecurityBundle\Form\RegistrationFirstForm;
use RL\SecurityBundle\Form\PasswordRestoringForm;
use RL\SecurityBundle\Entity\User;
use RL\SecurityBundle\Form\RegisterType;
use RL\SecurityBundle\Form\RegistrationFirstType;
use RL\SecurityBundle\Form\PasswordRestoringType;
use RL\SecurityBundle\Form\PersonalInformationType;
use RL\SecurityBundle\Form\PersonalInformationForm;
use RL\SecurityBundle\Form\PersonalSettingsType;
use RL\SecurityBundle\Form\PersonalSettingsForm;
use RL\SecurityBundle\Form\PasswordChangingType;
use RL\SecurityBundle\Form\PasswordChangingForm;
use RL\SecurityBundle\Form\AdministratorSettingsType;
use RL\SecurityBundle\Form\AdministratorSettingsForm;
use RL\SecurityBundle\Form\ModeratorSettingsType;
use RL\SecurityBundle\Form\ModeratorSettingsForm;
use RL\MainBundle\Helper\Pages;
use LightOpenID;
use Gregwar\ImageBundle\Image;

/**
 * Security controller
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
        $theme = $this->get('rl_themes.theme.provider');
        $request = $this->getRequest();
        $session = $request->getSession();
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            $theme->getPath('RLSecurityBundle', 'login.html.twig'), array('theme' => $theme, 'user' => $this->get('security.context')->getToken()->getUser(), 'last_username' => $session->get(SecurityContext::LAST_USERNAME), 'error' => $error,)
        );
    }

    /**
     * @Route("/register", name="register")
     */
    public function registrationAction()
    {
        $newUser = new RegistrationFirstForm;
        $theme = $this->get('rl_themes.theme.provider');
        $form = $this->createForm(new RegistrationFirstType(), $newUser);

        return $this->render(
            $theme->getPath('RLSecurityBundle', 'registrationFirstPage.html.twig'), array('theme' => $theme, 'user' => $this->get('security.context')->getToken()->getUser(), 'form' => $form->createView())
        );
    }

    /**
     * @Route("/register_send", name="register_send")
     */
    public function registrationSendAction(Request $request)
    {
        $theme = $this->get('rl_themes.theme.provider');
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
                    ->setTo($newUser->getEmail())->setContentType('text/html')->setBody($this->renderView($theme->getPath('RLSecurityBundle', 'registrationLetter.html.twig'), array('link' => $link, 'user' => $user, 'site' => $site)));
                $mailer->send($message);
                $legend = 'Registration mail is sended.';
                $text = 'Registration mail is sended. Please check your email.';
                $title = 'Mail sended';

                return $this->render(
                    $theme->getPath('RLMainBundle', 'fieldset.html.twig'), array('theme' => $theme, 'user' => $this->get('security.context')->getToken()->getUser(), 'legend' => $legend, 'text' => $text, 'title' => $title)
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
        $theme = $this->get('rl_themes.theme.provider');
        $secret = $this->container->getParameter('secret');
        if ($hash == md5(md5($username . $password . $email . $secret))) {
            $newUser = new User;
            $form = $this->createForm(new RegisterType(), $newUser);

            return $this->render(
                $theme->getPath('RLSecurityBundle', 'registrationSecondPage.html.twig'), array('theme' => $theme, 'user' => $this->get('security.context')->getToken()->getUser(), 'username' => $username, 'password' => $password, 'email' => $email, 'form' => $form->createView())
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
        $theme = $this->get('rl_themes.theme.provider');
        $method = $request->getMethod();
        if ($method == 'POST') {
            $newUser = new User;
            $form = $this->createForm(new RegisterType(), $newUser);
            $form->bind($request);
            if ($form->isValid()) {
                $settingsRepository = $this->getDoctrine()->getRepository('RLSecurityBundle:Settings');
                $groupsRepository = $this->getDoctrine()->getRepository('RLSecurityBundle:Group');
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
                    $theme->getPath('RLMainBundle', 'fieldset.html.twig'), array('theme' => $theme, 'user' => $this->get('security.context')->getToken()->getUser(), 'legend' => $legend, 'text' => $text, 'title' => $title)
                );
            } else {
                throw new \Exception('Registration form is invalid.');
            }
        } else {
            return $this->redirect($this->generateUrl('index'));
        }
    }

    /**
     * @Route("/openid_check", name="openid_check")
     */
    public function openidCheckAction(Request $request)
    {
        $theme = $this->get('rl_themes.theme.provider');
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
                $userRepository = $this->getDoctrine()->getRepository('RLSecurityBundle:User');
                if ($openid->validate()) {
                    $identity = $openid->identity;
                    $identity = preg_replace('#^http://(.*)#sim', '$1', $identity);
                    $identity = preg_replace('#^https://(.*)#sim', '$1', $identity);
                    $identity = preg_replace('#(.*)\/$#sim', '$1', $identity);
                    $user = $userRepository->findOneByOpenid($identity);
                    if (isset($user)) {
                        //FIXME: login user by openid
                        $legend = 'msg';
                        $text = 'login user';
                        $title = '';

                        return $this->render(
                            $theme->getPath('RLMainBundle', 'fieldset.html.twig'), array('theme' => $theme, 'user' => $this->get('security.context')->getToken()->getUser(), 'legend' => $legend, 'text' => $text, 'title' => $title)
                        );
                    } else {
                        $attr = $openid->getAttributes();
                        $email = '';
                        $newUser = new User;
                        $form = $this->createForm(new RegisterType(), $newUser);

                        return $this->render(
                            $theme->getPath('RLSecurityBundle', 'openIDRegistration.html.twig'), array('theme' => $theme, 'user' => $this->get('security.context')->getToken()->getUser(), 'openid' => $identity, 'password' => '', 'email' => $email, 'form' => $form->createView())
                        );
                    }
                } else {
                    throw new \Exception('OpenID is invalid');
                }
            }
        } catch (ErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @Route("/restore_password", name="restore_password")
     */
    public function restorePasswordAction()
    {
        $theme = $this->get('rl_themes.theme.provider');
        $resForm = new PasswordRestoringForm;
        $form = $this->createForm(new PasswordRestoringType(), $resForm);

        return $this->render(
            $theme->getPath('RLSecurityBundle', 'passwordRestoringForm.html.twig'), array('theme' => $theme, 'user' => $this->get('security.context')->getToken()->getUser(), 'form' => $form->createView())
        );
    }

    /**
     * @Route("/restore_password_check", name="restore_password_check")
     */
    public function restorePasswordCheckAction(Request $request)
    {
        $theme = $this->get('rl_themes.theme.provider');
        $method = $request->getMethod();
        if ($method == 'POST') {
            $resForm = new PasswordRestoringForm;
            $form = $this->createForm(new PasswordRestoringType(), $resForm);
            $form->bind($request);
            if ($form->isValid()) {
                $userRepository = $this->getDoctrine()->getRepository('RLSecurityBundle:User');
                try {
                    $user = $userRepository->findOneByUsername($resForm->getUsername());
                } catch (ErrorException $e) {
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
                    ->setTo($resForm->getEmail())->setContentType('text/html')->setBody($this->renderView($theme->getPath('RLSecurityBundle', 'passwordRestoringLetter.html.twig'), array('username' => $username, 'password' => $password)));
                $mailer->send($message);
                $legend = 'Mail with your new password is sended.';
                $text = 'Mail with your new password is sended. Please check your email.';
                $title = 'Mail sended';

                return $this->render(
                    $theme->getPath('RLMainBundle', 'fieldset.html.twig'), array('theme' => $theme, 'user' => $this->get('security.context')->getToken()->getUser(), 'legend' => $legend, 'text' => $text, 'title' => $title)
                );
            } else {
                throw new \Exception('Password restoring form is invalid');
            }
        } else {
            return $this->redirect($this->generateUrl('index'));
        }
    }

    /**
     * @Route("/user/{name}/edit", name="user_edit")
     */
    public function editUserAction($name)
    {
        $theme = $this->get('rl_themes.theme.provider');
        $user = $this->get('security.context')->getToken()->getUser();
        if ($name == 'anonymous' && $user->isAnonymous()) {
            $userInProfile = $this->get('security.context')->getToken()->getUser();
        } else {
            $userRepository = $this->getDoctrine()->getRepository('RLSecurityBundle:User');
            $userInProfile = $userRepository->findOneByUsername($name);
        }
        if (!$this->get('security.context')->isGranted('ROLE_MODER')) {
            if ($userInProfile->getUsername() != $user->getUsername()) {
                throw new \Exception('You can\'t edit profile of user '.$name);
            }
        }
        if (empty($userInProfile)) {
            throw new \Exception('User '.$name.' not found');
        }
        //save settings in database
        $request = $this->getRequest();
        $sbm = $request->request->get('sbm');
        if (isset($sbm)) {
            $method = $request->getMethod();
            if ($method == 'POST') {
                //save password
                if ($request->request->get('action') == 'passwordChanging') {
                    $passwordChangingForm = new PasswordChangingForm;
                    $form = $this->createForm(new PasswordChangingType(), $passwordChangingForm);
                    $form->bind($request);
                    if ($form->isValid()) {
                        $encoder = new MessageDigestPasswordEncoder('md5', false, 1);
                        $oldPassword = $encoder->encodePassword($passwordChangingForm->getOldPassword(), $userInProfile->getSalt());
                        if ($userInProfile->getPassword() !== $oldPassword) {
                            throw new \Exception('Your old password is invalid');
                        }
                        if ($passwordChangingForm->getNewPassword() !== $passwordChangingForm->getValidation()) {
                            throw new \Exception('Your new password does not meet the validation');
                        }
                        $newPassword = $encoder->encodePassword($passwordChangingForm->getNewPassword(), $userInProfile->getSalt());
                        $userInProfile->setPassword($newPassword);
                        $this->getDoctrine()->getManager()->flush();
                    } else
                        throw new \Exception('Form is invalid');
                }
                //save personal information
                elseif ($request->request->get('action') == 'personalInformation') {
                    $personalInformationForm = new PersonalInformationForm;
                    $form = $this->createForm(new PersonalInformationType(), $personalInformationForm);
                    $form->bind($request);
                    if ($form->isValid()) {
                        $photo = $personalInformationForm->getPhoto();
                        if (!empty($photo)) {
                            $filename = $userInProfile->getUsername().'_'.$photo->getClientOriginalName();
                            $personalInformationForm->getPhoto()->move(__DIR__.'/../../../../web/bundles/rlsecurity/images/avatars', $filename);
                            $absolutePath = __DIR__.'/../../../../web/bundles/rlsecurity/images/avatars/'.$filename;
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
                        $userInProfile->setName($personalInformationForm->getName());
                        $userInProfile->setOpenid($personalInformationForm->getOpenid());
                        $userInProfile->setLastname($personalInformationForm->getLastname());
                        $userInProfile->setGender($personalInformationForm->getGender());
                        $userInProfile->setBirthday($personalInformationForm->getBirthday());
                        $userInProfile->setEmail($personalInformationForm->getEmail());
                        $userInProfile->setShowEmail($personalInformationForm->getShowEmail());
                        $userInProfile->setIm($personalInformationForm->getIm());
                        $userInProfile->setShowIm($personalInformationForm->getShowIm());
                        $userInProfile->setCountry($personalInformationForm->getCountry());
                        $userInProfile->setCity($personalInformationForm->getCity());
                        $userInProfile->setAdditionalRaw($personalInformationForm->getAdditionalRaw());
                        $userInProfile->setAdditional($user->getMark()->render($personalInformationForm->getAdditionalRaw()));
                        $this->getDoctrine()->getManager()->flush();
                    } else
                        throw new \Exception('Form is invalid');
                }
                //save personal settings
                elseif ($request->request->get('action') == 'personalSettings') {
                    $personalSettingsForm = new PersonalSettingsForm;
                    $form = $this->createForm(new PersonalSettingsType(), $personalSettingsForm);
                    $form->bind($request);
                    if ($form->isValid()) {
                        $userInProfile->setMark($personalSettingsForm->getMark());
                        $userInProfile->setTheme($personalSettingsForm->getTheme());
                        $userInProfile->setGmt($personalSettingsForm->getGmt());
                        $userInProfile->setLanguage($personalSettingsForm->getLanguage());
                        $this->get('session')->set('_locale', $userInProfile->getLanguage());
                        $userInProfile->setNewsOnPage($personalSettingsForm->getNewsOnPage());
                        $userInProfile->setThreadsOnPage($personalSettingsForm->getThreadsOnPage());
                        $userInProfile->setCommentsOnPage($personalSettingsForm->getCommentsOnPage());
                        $userInProfile->setShowAvatars($personalSettingsForm->getShowAvatars());
                        $userInProfile->setShowUa($personalSettingsForm->getShowUa());
                        $userInProfile->setShowResp($personalSettingsForm->getShowResp());
                        $userInProfile->setSortingType($personalSettingsForm->getSortingType());
                        $this->getDoctrine()->getManager()->flush();
                    } else
                        throw new \Exception('Form is invalid');
                }
                //save moderator settings
                elseif ($request->request->get('action') == 'moderatorSettings') {
                    $moderatorSettingsForm = new ModeratorSettingsForm;
                    $form = $this->createForm(new ModeratorSettingsType(), $moderatorSettingsForm);
                    $form->bind($request);
                    if ($form->isValid()) {
                        $userInProfile->setActive($moderatorSettingsForm->getActive());//TODO: when inactive send message to user
                        $level = $moderatorSettingsForm->getCaptchaLevel();
                        $userInProfile->setCaptchaLevel((int) $level);
                        $this->getDoctrine()->getManager()->flush();
                    } else
                        throw new \Exception('Form is invalid');
                }
                //save administrator settings
                elseif ($request->request->get('action') == 'administratorSettings') {
                    $administratorSettingsForm = new AdministratorSettingsForm;
                    $form = $this->createForm(new AdministratorSettingsType(), $administratorSettingsForm);
                    $form->bind($request);
                    if ($form->isValid()) {
                        $userInProfile->setGroup($administratorSettingsForm->getGroup());
                        $this->getDoctrine()->getManager()->flush();
                    } else
                        throw new \Exception('Form is invalid');
                }
            }
        }
        //show info
        $passwordChangingForm = new PasswordChangingForm();
        $passwordChanging = $this->createForm(new PasswordChangingType(), $passwordChangingForm);
        $personalInformation = $this->createForm(new PersonalInformationType(), $userInProfile);
        $personalSettings = $this->createForm(new PersonalSettingsType(), $userInProfile);
        $moderatorSettings = $this->createForm(new ModeratorSettingsType(), $userInProfile);
        $administratorSettings = $this->createForm(new AdministratorSettingsType(), $userInProfile);

        return $this->render(
            $theme->getPath('RLSecurityBundle', 'profileEdit.html.twig'), array('theme' => $theme, 'user' => $user, 'userInfo'=> $userInProfile, 'personalInformation' => $personalInformation->createView(), 'personalSettings'=>$personalSettings->createView(), 'passwordChanging'=>$passwordChanging->createView(), 'moderatorSettings'=>$moderatorSettings->createView(), 'administratorSettings'=>$administratorSettings->createView(), )
        );
    }

    /**
     * @Route("/user/{name}", name="user")
     */
    public function userAction($name)
    {
        $theme = $this->get('rl_themes.theme.provider');
        $user = $this->get('security.context')->getToken()->getUser();
        $userRepository = $this->getDoctrine()->getRepository('RLSecurityBundle:User');
        $userInProfile = $userRepository->findOneByUsername($name);
        if (empty($userInProfile)) {
            throw new \Exception('user '.$name.' not found');
        }
        $userComments = $userRepository->getUserCommentsInformation($userInProfile);

        return $this->render(
            $theme->getPath('RLSecurityBundle', 'profile.html.twig'), array('theme' => $theme, 'user' => $user, 'userInfo'=> $userInProfile, 'commentsInfo' => $userComments,)
        );
    }

    /**
     * @Route("/users/{page}", name="users", defaults={"page" = 1})
     */
    public function usersAction($page)
    {
        $theme = $this->get('rl_themes.theme.provider');
        $user = $this->get('security.context')->getToken()->getUser();
        $userRepository = $this->getDoctrine()->getRepository('RLSecurityBundle:User');

        $itemsCount = count($userRepository->findAll());
        $itemsOnPage = $user->getCommentsOnPage();
        $pagesCount = ceil(($itemsCount) / $itemsOnPage);
        $pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
        $users = $userRepository->findBy(array(), null, $user->getCommentsOnPage(), $offset);
        $pages = new Pages($this->get('router'), $itemsOnPage, $itemsCount, $page, 'users', array("page" => $page));
        $pagesStr = $pages->draw();

        return $this->render($theme->getPath('RLMainBundle', 'users.html.twig'), array('theme' => $theme, 'user' => $user, 'users' => $users, 'pagesStr' => $pagesStr));
    }

}
