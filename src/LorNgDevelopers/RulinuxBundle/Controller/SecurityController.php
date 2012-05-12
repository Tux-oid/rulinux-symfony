<?php

namespace LorNgDevelopers\RulinuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use LorNgDevelopers\RulinuxBundle\Entity\RegistrationFirstForm;
use LorNgDevelopers\RulinuxBundle\Entity\User;

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
	public function registerAction()
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
	public function registerSendAction(Request $request)
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
		
	}
	public function registerConfirmAction($username, $password, $email, $hash)
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
}
?>