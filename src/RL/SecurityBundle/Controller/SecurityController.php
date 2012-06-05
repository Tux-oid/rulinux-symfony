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
use RL\SecurityBundle\Entity\Group;
use RL\SecurityBundle\Entity\SettingsRepository;
use RL\SecurityBundle\Form\RegisterType;
use RL\SecurityBundle\Form\RegisterFirstType;
use RL\SecurityBundle\Form\RestorePasswordType;
use LightOpenID\openid;

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
		$this->get('session')->setLocale('ru_RU'); //FIXME: loale in user class
		$theme = $this->get('rl_themes.theme.provider');
		$request = $this->getRequest();
		$session = $request->getSession();
		if($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
		{
			$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		}
		else
		{
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
		return $this->render($theme->getPath('RLSecurityBundle', 'login.html.twig'), array('theme' => $theme,
				'user' => $this->get('security.context')->getToken()->getUser(),
				'last_username' => $session->get(SecurityContext::LAST_USERNAME),
				'error' => $error,
			));
	}
	/**
	 * @Route("/register", name="register")
	 */
	public function registrationAction()
	{
		$newUser = new RegistrationFirstForm;
		$theme = $this->get('rl_themes.theme.provider');
		$form = $this->createForm(new RegisterFirstType(), $newUser);
		return $this->render($theme->getPath('RLSecurityBundle', 'registrationFirstPage.html.twig'), array('theme' => $theme,
				'user' => $this->get('security.context')->getToken()->getUser(),
				'form' => $form->createView()));
	}
	/**
	 * @Route("/register_send", name="register_send")
	 */
	public function registrationSendAction(Request $request)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$method = $request->getMethod();
		if($method == 'POST')
		{
			$newUser = new RegistrationFirstForm;
			$form = $this->createForm(new RegisterFirstType(), $newUser);
			$form->bindRequest($request);
			if($form->isValid())
			{
				$mailer = $this->get('mailer');
				$secret = $this->container->getParameter('secret');
				$link = $this->generateUrl('register_confirm', array('username' => $newUser->getName(), 'password' => $newUser->getPassword(), 'email' => $newUser->getEmail(), 'hash' => md5(md5($newUser->getName().$newUser->getPassword().$newUser->getEmail().$secret))), true);
				$user = $newUser->getName();
				$site = $request->getHttpHost();
				$message = \Swift_Message::newInstance()
					->setSubject('Registration letter')
					->setFrom('noemail@rulinux.net')//FIXME:load email from settings
					->setTo($newUser->getEmail())
					->setContentType('text/html')
					->setBody($this->renderView($theme->getPath('RLSecurityBundle', 'registrationLetter.html.twig'), array('link' => $link, 'user' => $user, 'site' => $site)));
				$mailer->send($message);
				$legend = 'Registration mail is sended.';
				$text = 'Registration mail is sended. Please check your email.';
				$title = 'Mail sended';
				return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array('theme' => $theme,
						'user' => $this->get('security.context')->getToken()->getUser(),
						'legend' => $legend,
						'text' => $text,
						'title' => $title));
			}
			else
				throw new \Exception('Registration form is invalid.');
		}
		else
			return $this->redirect($this->generateUrl('index'));
	}
	/**
	 * @Route("/register_confirm/{username}/{password}/{email}/{hash}", name="register_confirm")
	 */
	public function registrationConfirmAction($username, $password, $email, $hash)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$secret = $this->container->getParameter('secret');
		if($hash == md5(md5($username.$password.$email.$secret)))
		{
			$newUser = new User;
			$form = $this->createForm(new RegisterType(), $newUser);
			return $this->render($theme->getPath('RLSecurityBundle', 'registrationSecondPage.html.twig'), array(
					'theme' => $theme,
					'user' => $this->get('security.context')->getToken()->getUser(),
					'username' => $username,
					'password' => $password,
					'email' => $email,
					'form' => $form->createView()));
		}
		else
			throw new \Exception('Hash is invalid');
	}
	/**
	 * @Route("/registration_save", name="registration_save")
	 */
	public function registrationSaveAction(Request $request)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$method = $request->getMethod();
		if($method == 'POST')
		{
			$newUser = new User;
			$form = $this->createForm(new RegisterType(), $newUser);
			$form->bindRequest($request);
			if($form->isValid())
			{
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
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($newUser);
				$em->flush();
				$legend = 'Registration completed.';
				$text = 'Registration completed. Now you can <a href="'.$this->generateUrl('login').'">login</a> on this site.';
				$title = '';
				return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array('theme' => $theme,
						'user' => $this->get('security.context')->getToken()->getUser(),
						'legend' => $legend,
						'text' => $text,
						'title' => $title));
			}
			else
				throw new \Exception('Registration form is invalid.');
		}
		else
			return $this->redirect($this->generateUrl('index'));
	}
	/**
	 * @Route("/openid_check", name="openid_check")
	 */
	public function openidCheckAction(Request $request)
	{
		$theme = $this->get('rl_themes.theme.provider');
		try
		{
			$openid = new \LightOpenID($request->getHttpHost());
			if(!$openid->mode)
			{
				$identifier = $request->request->get('openid_identifier');
				if(isset($identifier))
				{
					$openid->identity = $identifier;
					$openid->required = array('contact/email');
					$openid->optional = array('namePerson', 'namePerson/friendly');
					return $this->redirect($openid->authUrl());
				}
				else
					throw new \Exception('OpenID identifier is empty');
			}
			elseif($openid->mode == 'cancel')
			{
				return $this->redirect($this->generateUrl('login'));
			}
			else
			{
				$userRepository = $this->getDoctrine()->getRepository('RLSecurityBundle:User');
				if($openid->validate())
				{
					$identity = $openid->identity;
					$identity = preg_replace('#^http://(.*)#sim', '$1', $identity);
					$identity = preg_replace('#^https://(.*)#sim', '$1', $identity);
					$identity = preg_replace('#(.*)\/$#sim', '$1', $identity);
					$user = $userRepository->findOneByOpenid($identity);
					if(isset($user))
					{
						//FIXME: login user by openid
						$legend = 'msg';
						$text = 'login user';
						$title = '';
						return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array('theme' => $theme,
								'user' => $this->get('security.context')->getToken()->getUser(),
								'legend' => $legend,
								'text' => $text,
								'title' => $title));
					}
					else
					{
						$attr = $openid->getAttributes();
						$email = '';
						$newUser = new User;
						$form = $this->createForm(new RegisterType(), $newUser);
						return $this->render($theme->getPath('RLSecurityBundle', 'openIDRegistration.html.twig'), array('theme' => $theme,
								'user' => $this->get('security.context')->getToken()->getUser(),
								'openid' => $identity,
								'password' => '',
								'email' => $email,
								'form' => $form->createView()));
					}
				}
				else
					throw new \Exception('OpenID is invalid');
			}
		}
		catch(ErrorException $e)
		{
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
		$form = $this->createForm(new RestorePasswordType(), $resForm);
		return $this->render($theme->getPath('RLSecurityBundle', 'passwordRestoringForm.html.twig'), array('theme' => $theme,
				'user' => $this->get('security.context')->getToken()->getUser(),
				'form' => $form->createView()));
	}
	/**
	 * @Route("/restore_password_check", name="restore_password_check")
	 */
	public function restorePasswordCheckAction(Request $request)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$method = $request->getMethod();
		if($method == 'POST')
		{
			$resForm = new PasswordRestoringForm;
			$form = $this->createForm(new RestorePasswordType(), $resForm);
			$form->bindRequest($request);
			if($form->isValid())
			{
				$userRepository = $this->getDoctrine()->getRepository('RLSecurityBundle:User');
				try
				{
					$user = $userRepository->findOneByUsername($resForm->getUsername());
				}
				catch(ErrorException $e)
				{
					throw new \Exception($e->getMessage());
				}
				if($user->getEmail() != $resForm->getEmail())
				{
					throw new \Exception('Email is wrong');
				}
				if($user->getQuestion() != $resForm->getQuestion())
				{
					throw new \Exception('Question is wrong');
				}
				if($user->getAnswer() != $resForm->getAnswer())
				{
					throw new \Exception('Answer is wrong');
				}
				$password = md5(uniqid(rand(), true));
				$password = substr($password, 1, 9);
				$encoder = new MessageDigestPasswordEncoder('md5', false, 1);
				$encodedPassword = $encoder->encodePassword($password, $user->getSalt());
				$user->setPassword($encodedPassword);
				$username = $user->getUsername();
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($user);
				$em->flush();
				$mailer = $this->get('mailer');
				$message = \Swift_Message::newInstance()
					->setSubject('Password restoring')
					->setFrom('noemail@rulinux.net')//FIXME:load email from settings
					->setTo($resForm->getEmail())
					->setContentType('text/html')
					->setBody($this->renderView($theme->getPath('RLSecurityBundle', 'passwordRestoringLetter.html.twig'), array('username' => $username, 'password' => $password)));
				$mailer->send($message);
				$legend = 'Mail with your new password is sended.';
				$text = 'Mail with your new password is sended. Please check your email.';
				$title = 'Mail sended';
				return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array('theme' => $theme,
						'user' => $this->get('security.context')->getToken()->getUser(),
						'legend' => $legend,
						'text' => $text,
						'title' => $title));
			}
			else
				throw new \Exception('Password restoring form is invalid');
		}
		else
			return $this->redirect($this->generateUrl('index'));
	}
}
