<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RL\ForumBundle\Entity\Subsection;

class DefaultController extends Controller
{
	/**
	 * @Route("/forum_{name}", name="subsection")
	 */
	public function subsectionAction($name)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$subsectionRepository = $this->get('doctrine')->getRepository('RLForumBundle:Subsection');
		$subsection = $subsectionRepository->findOneByRewrite($name);
		if(empty($subsection))
		{
			$legend = 'subsection not found';
			$title = 'unknown subsection';
			$text = 'subsection '.$name.' not found';
			return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array(
				'theme' => $theme, 
				'user' => $this->get('security.context')->getToken()->getUser(),
				'legend'=>$legend,
				'title'=>$title,
				'text'=>$text,
			));
		}
		
		return $this->render($theme->getPath('RLForumBundle', 'subsection.html.twig'),
		array('theme' => $theme, 
		'user' => $this->get('security.context')->getToken()->getUser(),
		'subsection'=>$subsection,
		'threads'=>$subsection->getThreads(),
		)
		);
	}
	/**
	 * @Route("/forum", name="forum")
	 */
	public function forumAction()
	{
		$theme = $this->get('rl_themes.theme.provider');
		$subsectionRepository = $this->get('doctrine')->getRepository('RLForumBundle:Subsection');
		return $this->render($theme->getPath('RLForumBundle', 'forum.html.twig'),
		array('theme' => $theme, 
		'user' => $this->get('security.context')->getToken()->getUser(),
		'subsections'=>$subsectionRepository->findAll(),
		)
		);
	}
}
