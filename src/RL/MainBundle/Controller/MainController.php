<?php
/**
 * @author Ax-xa-xa 
 */
namespace RL\MainBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MainController extends Controller
{
	/**
	 * @Route("/", name="index")
	 * @Route("/login")
	 * @Template()
	 */
	public function homepageAction()
	{
		$theme = $this->get('rl_themes.theme.provider');
		return $this->render($theme->getPath('index.html.twig'), array(
				'user' => $this->get('security.context')->getToken()->getUser(),
				'theme' => $this->get('rl_themes.theme.provider')
			));
	}
}
