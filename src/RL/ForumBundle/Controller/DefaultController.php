<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
	/**
	 * @Route("/forum", name="forum")
	 */
	public function forumAction()
	{
		$theme = $this->get('rl_themes.theme.provider');
		//'RLForumBundle:Default:index.html.twig'
		return $this->render($theme->getPath('RLForumBundle', 'forum.html.twig'),
		array('theme' => $theme, 
		'user' => $this->get('security.context')->getToken()->getUser(),
		)
		);
	}
}
