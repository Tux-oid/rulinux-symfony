<?php

namespace LorNgDevelopers\RulinuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use LorNgDevelopers\RulinuxBundle\Entity\RegistrationFirstForm;
use LorNgDevelopers\RulinuxBundle\Entity\User;
use LorNgDevelopers\RulinuxBundle\Entity\Group;
use LorNgDevelopers\RulinuxBundle\Entity\SettingsRepository;

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
		return $this->render('LorNgDevelopersRulinuxBundle:Security:login.html.twig', array(
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
		->add('email', 'text')
		->getForm();
		return $this->render('LorNgDevelopersRulinuxBundle:Security:registrationFirstPage.html.twig', array('form' => $form->createView()));
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
				->setSubject('Registration letter')
				->setFrom('noemail@rulinux.net')
				->setTo($newUser->getEmail())
				->setContentType('text/html')
				->setBody($this->renderView('LorNgDevelopersRulinuxBundle:Security:registrationLetter.html.twig', array('link' =>$link, 'user'=>$user, 'site'=>$site)));
				$mailer->send($message);
				$legend = 'Registration mail is sended.';
				$text = 'Registration mail is sended. Please check your email.';
				$title='';
				return $this->render('LorNgDevelopersRulinuxBundle:Default:fieldset.html.twig', array('legend'=>$legend, 'text'=>$text, 'title'=>$title));
			}
			else
				throw new \Exception('Registration form is invalid.');
		}
		else
			$this->redirect($this->generateUrl('index'));
		
	}
	public function registrationConfirmAction($username, $password, $email, $hash)
	{
		$secret = $this->container->getParameter('secret');
		if($hash == md5(md5($username.$password.$email.$secret)))
		{
			$newUser = new User;
			$form = $this->createFormBuilder($newUser)
			->add('username', 'text')
			->add('password', 'password')
			->add('name', 'text')
			->add('lastname', 'text')
			->add('country', 'country')
			->add('city', 'text')
			->add('photo', 'file')
			->add('birthday', 'birthday')
			->add('gender', 'checkbox')
			->add('additional', 'textarea')
			->add('email', 'email')
			->add('im', 'email')
			->add('openid', 'text')
			->add('language', 'language')
			->add('gmt', 'timezone')
			->getForm();
			return $this->render('LorNgDevelopersRulinuxBundle:Security:registrationSecondPage.html.twig',array('username'=>$username, 'password'=>$password, 'email'=>$email, 'form'=>$form->createView()));
		}
		else
			throw new Exception('Hash is invalid');
	}

	public function registrationSaveAction(Request $request)
	{
		$method = $request->getMethod();
		if($method == 'POST')
		{
			$newUser = new User;
			$form = $this->createFormBuilder($newUser)
			->add('username', 'text')
			->add('password', 'password')
			->add('name', 'text')
			->add('lastname', 'text')
			->add('country', 'country')
			->add('city', 'text')
			->add('photo', 'file')
			->add('birthday', 'birthday')
			->add('gender', 'checkbox')
			->add('additional', 'textarea')
			->add('email', 'email')
			->add('im', 'email')
			->add('openid', 'text')
			->add('language', 'language')
			->add('gmt', 'timezone')
			->getForm();
			$form->bindRequest($request);
			if($form->isValid())
			{
				$settingsRepository = $this->getDoctrine()->getRepository('LorNgDevelopersRulinuxBundle:Settings');
				$groupsRepository = $this->getDoctrine()->getRepository('LorNgDevelopersRulinuxBundle:Group');
				$userRole = $groupsRepository->findOneByName('ROLE_USER');
				//TODO: set additional marked
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
				return $this->render('LorNgDevelopersRulinuxBundle:Default:fieldset.html.twig', array('legend'=>$legend, 'text'=>$text, 'title'=>$title));
			}
			else
				throw new \Exception('Registration form is invalid.');
		}
		else
			$this->redirect($this->generateUrl('index'));
	}
}
?>