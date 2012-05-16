<?php

namespace RL\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use RL\SecurityBundle\Entity\RegistrationFirstForm;
use RL\SecurityBundle\Entity\PasswordRestoringForm;
use RL\SecurityBundle\Entity\User;
use RL\SecurityBundle\Entity\Group;
use RL\SecurityBundle\Entity\SettingsRepository;
use LightOpenID\openid;

class SecurityController extends Controller
{
	public function loginAction()
	{
		$request = $this->getRequest();
		$session = $request->getSession();
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
		{
			$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		}
		else
		{
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
		return $this->render('RLSecurityBundle:Security:login.html.twig', array(
		'last_username' => $session->get(SecurityContext::LAST_USERNAME),
		'error'         => $error,
		));
	}
	public function registrationAction()
	{
		$newUser = new RegistrationFirstForm;
		$form = $this->createFormBuilder($newUser)
		->add('name', 'text')
		->add('password', 'password')
		->add('validation', 'password')
		->add('email', 'email')
		->getForm();
		return $this->render('RLSecurityBundle:Security:registrationFirstPage.html.twig', array('form' => $form->createView()));
	}
	public function registrationSendAction(Request $request)
	{
		$method = $request->getMethod();
		if($method == 'POST')
		{
			$newUser = new RegistrationFirstForm;
			$form = $this->createFormBuilder($newUser)
			->add('name', 'text')
			->add('password', 'password')
			->add('validation', 'password')
			->add('email', 'email')
			->getForm();
			$form->bindRequest($request);
			if($form->isValid())
			{
				$mailer = $this->get('mailer');
				$secret = $this->container->getParameter('secret');
				$link = $this->generateUrl('register_confirm', array('username'=>$newUser->getName(), 'password'=>$newUser->getPassword(), 'email'=>$newUser->getEmail(), 'hash'=>md5(md5($newUser->getName().$newUser->getPassword().$newUser->getEmail().$secret))), true);
				$user=$newUser->getName();
				$site=$request->getHttpHost();
				$message = \Swift_Message::newInstance()
				->setSubject('Registration letter')//FIXME:load email from settings
				->setFrom('noemail@rulinux.net')
				->setTo($newUser->getEmail())
				->setContentType('text/html')
				->setBody($this->renderView('RLSecurityBundle:Security:registrationLetter.html.twig', array('link' =>$link, 'user'=>$user, 'site'=>$site)));
				$mailer->send($message);
				$legend = 'Registration mail is sended.';
				$text = 'Registration mail is sended. Please check your email.';
				$title='Mail sended';
				return $this->render('RLSecurityBundle:Default:fieldset.html.twig', array('legend'=>$legend, 'text'=>$text, 'title'=>$title));
			}
			else
				throw new \Exception('Registration form is invalid.');
		}
		else
			return $this->redirect($this->generateUrl('index'));
		
	}
	public function registrationConfirmAction($username, $password, $email, $hash)
	{
		$secret = $this->container->getParameter('secret');
		if($hash == md5(md5($username.$password.$email.$secret)))
		{
			$newUser = new User;
			$form = $this->createFormBuilder($newUser)
			->add('username', 'text', array('required' => true))
			->add('password', 'password', array('required' => true))
			->add('name', 'text', array('required' => false))
			->add('lastname', 'text', array('required' => false))
			->add('country', 'country', array('required' => true))
			->add('city', 'text', array('required' => false))
			->add('photo', 'file', array('required' => false))
			->add('birthday', 'birthday', array('required' => true))
			->add('gender', 'checkbox', array('required' => false))
			->add('additional', 'textarea', array('required' => false))
			->add('email', 'email', array('required' => true))
			->add('im', 'email', array('required' => false))
			->add('openid', 'text', array('required' => false))
			->add('language', 'language', array('required' => true))
			->add('gmt', 'timezone', array('required' => true))
			->add('question', 'text', array('required' => true))
			->add('answer', 'text', array('required' => true))
			->getForm();
			return $this->render('RLSecurityBundle:Security:registrationSecondPage.html.twig',array('username'=>$username, 'password'=>$password, 'email'=>$email, 'form'=>$form->createView()));
		}
		else
			throw new \Exception('Hash is invalid');
	}
	public function registrationSaveAction(Request $request)
	{
		$method = $request->getMethod();
		if($method == 'POST')
		{
			$newUser = new User;
			$form = $this->createFormBuilder($newUser)
			->add('username', 'text', array('required' => true))
			->add('password', 'password', array('required' => true))
			->add('name', 'text', array('required' => false))
			->add('lastname', 'text', array('required' => false))
			->add('country', 'country', array('required' => true))
			->add('city', 'text', array('required' => false))
			->add('photo', 'file', array('required' => false))
			->add('birthday', 'birthday', array('required' => true))
			->add('gender', 'checkbox', array('required' => false))
			->add('additional', 'textarea', array('required' => false))
			->add('email', 'email', array('required' => true))
			->add('im', 'email', array('required' => false))
			->add('openid', 'text', array('required' => false))
			->add('language', 'language', array('required' => true))
			->add('gmt', 'timezone', array('required' => true))
			->add('question', 'text', array('required' => true))
			->add('answer', 'text', array('required' => true))
			->getForm();
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
				$title='';
				return $this->render('RLSecurityBundle:Default:fieldset.html.twig', array('legend'=>$legend, 'text'=>$text, 'title'=>$title));
			}
			else
				throw new \Exception('Registration form is invalid.');
		}
		else
			return $this->redirect($this->generateUrl('index'));
	}
	public function openidCheckAction(Request $request)
	{
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
						$title='';
						return $this->render('RLSecurityBundle:Default:fieldset.html.twig', array('legend'=>$legend, 'text'=>$text, 'title'=>$title));
					}
					else
					{
						$attr = $openid->getAttributes();
						$email = '';
						$newUser = new User;
						$form = $this->createFormBuilder($newUser)
						->add('username', 'text', array('required' => true))
						->add('password', 'password', array('required' => true))
						->add('name', 'text', array('required' => false))
						->add('lastname', 'text', array('required' => false))
						->add('country', 'country', array('required' => true))
						->add('city', 'text', array('required' => false))
						->add('photo', 'file', array('required' => false))
						->add('birthday', 'birthday', array('required' => true))
						->add('gender', 'checkbox', array('required' => false))
						->add('additional', 'textarea', array('required' => false))
						->add('email', 'email', array('required' => true))
						->add('im', 'email', array('required' => false))
						->add('openid', 'text', array('required' => false))
						->add('language', 'language', array('required' => true))
						->add('gmt', 'timezone', array('required' => true))
						->add('question', 'text', array('required' => true))
						->add('answer', 'text', array('required' => true))
						->getForm();
						return $this->render('RLSecurityBundle:Security:openIDRegistration.html.twig',array('openid'=>$identity, 'password'=>'', 'email'=>$email, 'form'=>$form->createView()));
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
	public function restorePasswordAction()
	{
		$resForm = new PasswordRestoringForm;
		$form = $this->createFormBuilder($resForm)
		->add('username', 'text')
		->add('email', 'email')
		->add('question', 'text')
		->add('answer', 'text')
		->getForm();
		return $this->render('RLSecurityBundle:Security:passwordRestoringForm.html.twig', array('form' => $form->createView()));
	}
	public function restorePasswordCheckAction(Request $request)
	{
		$method = $request->getMethod();
		if($method == 'POST')
		{
			$resForm = new PasswordRestoringForm;
			$form = $this->createFormBuilder($resForm)
			->add('username', 'text')
			->add('email', 'email')
			->add('question', 'text')
			->add('answer', 'text')
			->getForm();
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
				$password = md5(uniqid(rand(),true));
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
				->setBody($this->renderView('RLSecurityBundle:Security:passwordRestoringLetter.html.twig', array('username'=>$username, 'password'=>$password)));
				$mailer->send($message);
				$legend = 'Mail with your new password is sended.';
				$text = 'Mail with your new password is sended. Please check your email.';
				$title='Mail sended';
				return $this->render('RLSecurityBundle:Default:fieldset.html.twig', array('legend'=>$legend, 'text'=>$text, 'title'=>$title));
			}
			else
				throw new \Exception('Password restoring form is invalid');
		}
		else
			return $this->redirect($this->generateUrl('index'));
	}
}
?>