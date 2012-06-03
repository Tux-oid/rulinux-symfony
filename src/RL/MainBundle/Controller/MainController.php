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
	 * @Template()
	 */
	public function homepageAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$user->setTheme('Default');
		$theme = $this->get('rl_themes.theme.provider');
		return $this->render($theme->getPath('RLMainBundle', 'index.html.twig'), array(
				'user' => $this->get('security.context')->getToken()->getUser(),
				'theme' => $this->get('rl_themes.theme.provider')
			));
	}
}
