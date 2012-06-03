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
		return $this->render('RLForumBundle:Default:index.html.twig', array('name' => 'name'));
	}
}
