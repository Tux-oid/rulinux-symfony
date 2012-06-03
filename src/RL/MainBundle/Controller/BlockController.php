<?php
/**
 *@author Ax-xa-xa 
 */
namespace RL\MainBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use RL\SecurityBundle\Form\LoginType;

class BlockController extends Controller
{
	/**
	 * Отрисовывает блок аутентификации.
	 */
	public function renderAuthenticationAction()
	{
		$theme = $this->get('rl_themes.theme.provider');
		$security_context = $this->get('security.context');
		if($security_context->isGranted('IS_AUTHENTICATED_FULLY')||$security_context->isGranted('IS_AUTHENTICATED_REMEMBERED'))
		{
			return $this->render($theme->getPath('welcome.html.twig'), array('user' => $security_context->getToken()->getUser()));
		}
		else
		{
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
			$defaults = array('_username' => $session->get(SecurityContext::LAST_USERNAME));
			$form = $this->createForm(new LoginType(), $defaults);
			return $this->render($theme->getPath('authenticationBlock.html.twig'), array('error' => $error, 'form' => $form->createView()));
		}
	}
}
