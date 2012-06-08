<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RL\ForumBundle\Entity\Subsection;
use RL\ForumBundle\Form\AddThreadType;
use RL\ForumBundle\Form\AddThreadForm;

class DefaultController extends Controller
{
	/**
	 * @Route("/forum_{name}", name="subsection")
	 */
	public function subsectionAction($name)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$user = $this->get('security.context')->getToken()->getUser();
		$subsectionRepository = $this->get('doctrine')->getRepository('RLForumBundle:Subsection');
		$subsection = $subsectionRepository->findOneByRewrite($name);
		if(empty($subsection))
		{
			$legend = 'subsection not found';
			$title = 'unknown subsection';
			$text = 'subsection '.$name.' not found';
			return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array(
					'theme' => $theme,
					'user' => $user,
					'legend' => $legend,
					'title' => $title,
					'text' => $text,
				));
		}
		$threadRepository = $this->get('doctrine')->getRepository('RLForumBundle:Thread');
		$threads = $threadRepository->getThreads('10', $user);
		return $this->render($theme->getPath('RLForumBundle', 'subsection.html.twig'), array('theme' => $theme,
				'user' => $user,
				'subsection' => $subsection,
				'subsections' => $subsectionRepository->findAll(),
				'threads' => $threads,
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
		return $this->render($theme->getPath('RLForumBundle', 'forum.html.twig'), array('theme' => $theme,
				'user' => $this->get('security.context')->getToken()->getUser(),
				'subsections' => $subsectionRepository->findAll(),
				)
		);
	}
	/**
	 * @Route("/new_thread_in_forum_{subsection}", name="new_thread_in_forum")
	 */
	public function newThreadAction($subsection)
	{
		$newThread = new AddThreadForm;
		$form = $this->createForm(new AddThreadType(), $newThread);
		
//		$resForm = new PasswordRestoringForm;
//		$form = $this->createForm(new RestorePasswordType(), $resForm);
		
		$theme = $this->get('rl_themes.theme.provider');
		$user = $this->get('security.context')->getToken()->getUser();
		return $this->render($theme->getPath('RLForumBundle', 'new_thread_in_forum.html.twig'), array(
				'theme' => $theme,
				'user' => $user,
				'form' => $form->createView(),
			));
	}
}
