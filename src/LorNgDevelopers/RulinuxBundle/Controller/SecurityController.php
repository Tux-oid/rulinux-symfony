<?php

namespace LorNgDevelopers\RulinuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use LorNgDevelopers\RulinuxBundle\Entity\RegistrationFirstForm;
use LorNgDevelopers\RulinuxBundle\Entity\User;
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
		$regForm = new RegistrationFirstForm;
		$form = $this->createFormBuilder($regForm)
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
			$regForm = new RegistrationFirstForm;
			$form = $this->createFormBuilder($regForm)
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
				$link = $this->generateUrl('register_confirm', array('username'=>$regForm->getName(), 'password'=>$regForm->getPassword(), 'email'=>$regForm->getEmail(), 'hash'=>md5(md5($regForm->getName().$regForm->getPassword().$regForm->getEmail().$secret))), true);
				$user=$regForm->getName();
				$site=$request->getHttpHost();
				$message = \Swift_Message::newInstance()
				->setSubject('Registration letter')
				->setFrom('noemail@rulinux.net')
				->setTo($regForm->getEmail())
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
			$regForm = new User;
			$form = $this->createFormBuilder($regForm)
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
			$regForm = new User;
			$form = $this->createFormBuilder($regForm)
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
				$settings = $this->getDoctrine()->getRepository('LorNgDevelopersRulinuxBundle:Settings');
				$regForm->setPassword(md5($regForm->getPassword()));
				//TODO: set additional marked
				$regForm->setAdditionalRaw($regForm->getAdditional());
				$regForm->setRegistrationDate(new \DateTime('now'));
				$regForm->setLastVisitDate(new \DateTime('now'));
				$regForm->setCaptchaLevel($settings->findOneByName('captcha_level')->getValue());
				$regForm->setTheme($settings->findOneByName('theme')->getValue());
				$regForm->setSortingType($settings->findOneByName('sortingType')->getValue());
				$regForm->setNewsOnPage($settings->findOneByName('news_on_page')->getValue());
				$regForm->setThreadsOnPage($settings->findOneByName('threads_on_page')->getValue());
				$regForm->setCommentsOnPage($settings->findOneByName('comments_on_page')->getValue());
				$regForm->setShowEmail($settings->findOneByName('showEmail')->getValue());
				$regForm->setShowIm($settings->findOneByName('showIm')->getValue());
				$regForm->setShowAvatars($settings->findOneByName('showAvatars')->getValue());
				$regForm->setShowUa($settings->findOneByName('showUa')->getValue());
				$regForm->setShowResp($settings->findOneByName('showResp')->getValue());
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($regForm);
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