<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RL\MainBundle\Helper\Pages;
use RL\ForumBundle\Entity\Subsection;
use RL\ForumBundle\Form\AddThreadType;
use RL\ForumBundle\Form\AddThreadForm;
use RL\ForumBundle\Form\AddCommentType;
use RL\ForumBundle\Form\AddCommentForm;
use RL\ForumBundle\Entity\Thread;
use RL\ForumBundle\Entity\Message;

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
		$threads = $threadRepository->getThreads($name, '10', '0');
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
	 * @Route("/new_thread_in_forum_{subsectionRewrite}", name="new_thread_in_forum")
	 */
	public function newThreadAction($subsectionRewrite)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$user = $this->get('security.context')->getToken()->getUser();
		$doctrine = $this->get('doctrine');
		$request = $this->getRequest();
		//save thread in database
		$sbm = $request->request->get('submit');
		if(isset($sbm))
		{
			$em = $doctrine->getEntityManager();
			$thr = $request->request->get('addThread');
			$subsectionRepository = $doctrine->getRepository('RLForumBundle:Subsection');
			$subsection = $subsectionRepository->findOneByRewrite($subsectionRewrite);
			$thread = new Thread();
			$thread->setSubsection($subsection);
			$em->persist($thread);
			$message = new Message();
			$message->setUser($user);
			$message->setReferer(0);
			$message->setSubject($thr['subject']);
			$message->setComment($thr['comment']);
			$message->setRawComment($thr['comment']);
			$message->setThread($thread);
			$em->persist($message);
			$em->flush();
			return $this->redirect($this->generateUrl("subsection", array("name" => $subsectionRewrite))); //FIXME: set url for redirecting
		}
		//preview
		$preview = false;
		$pr_val = $request->request->get('preview');
		if(isset($pr_val))
		{
			$preview = true;
			$newThread = new AddThreadForm($user);
			$prv_thr = $request->request->get('addThread');
			$newThread->setSubject($prv_thr['subject']);
			$newThread->setComment($prv_thr['comment']);
		}
		else
			$newThread = new AddThreadForm($user);
		//show form
		$form = $this->createForm(new AddThreadType(), $newThread);
		return $this->render($theme->getPath('RLForumBundle', 'newThreadInForum.html.twig'), array(
				'theme' => $theme,
				'user' => $user,
				'form' => $form->createView(),
				'subsection' => $subsectionRewrite,
				'preview' => $preview,
				'message' => $newThread,
			));
	}
	/**
	 * @Route("/thread/{id}/{page}", name="thread", defaults={"page" = 1})
	 */
	public function threadAction($id, $page)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$user = $this->get('security.context')->getToken()->getUser();
		$doctrine = $this->get('doctrine');
		$threadRepository = $doctrine->getRepository('RLForumBundle:Thread');
		$itemsCount = count($threadRepository->findOneById($id)->getMessages());
		$itemsOnPage = $user->getCommentsOnPage();
		$pagesCount = ceil(($itemsCount - 1) / $itemsOnPage);
		$pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
		$startMessage = $threadRepository->getThreadStartMessageById($id);
		$threadComments = $threadRepository->getThreadCommentsById($id, $itemsOnPage, $offset);
		$pages = new Pages($this->get('router'), $itemsOnPage, $itemsCount, $page, 'thread', array("id"=>$id, "page"=>$page));
		$pagesStr = $pages->draw();
		return $this->render($theme->getPath('RLForumBundle', 'thread.html.twig'), array(
				'theme' => $theme,
				'user' => $user,
				'startMessage' => $startMessage,
				'messages' => $threadComments,
				'pages'=> $pagesStr,
			));
	}
	/**
	 * @Route("/comment_into_{thread_id}_on_{comment_id}", name="comment") 
	 */
	public function commentAction($thread_id, $comment_id)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$user = $this->get('security.context')->getToken()->getUser();
		$doctrine = $this->get('doctrine');
		$request = $this->getRequest();
		//save comment in database
		$sbm = $request->request->get('submit');
		if(isset($sbm))
		{
			$em = $doctrine->getEntityManager();
			$thr = $request->request->get('addThread');
			$threadRepository = $doctrine->getRepository('RLForumBundle:Thread');
			$thread = $threadRepository->findOneById($thread_id);
			$message = new Message();
			$message->setUser($user);
			$message->setReferer(0);
			$message->setSubject($thr['subject']);
			$message->setComment($thr['comment']);
			$message->setRawComment($thr['comment']);
			$message->setThread($thread);
			$message->setReferer($comment_id);
			$em->persist($message);
			$em->flush();
			return $this->redirect($this->generateUrl("thread", array("id" => $thread_id, "page"=>1))); //FIXME: set url for redirecting
		}
		//preview
		$pr_val = $request->request->get('preview');
		$message = '';
		$newComment = new AddCommentForm($user);
		if(isset($pr_val))
		{
			$prv_thr = $request->request->get('addThread');
			$newComment->setSubject($prv_thr['subject']);
			$newComment->setComment($prv_thr['comment']);
			$message = $newComment;
		}
		else
		{
			$messageRepository = $doctrine->getRepository('RLForumBundle:Message');
			$message = $messageRepository->findOneById($comment_id);
			$re = '';
			if(substr($message->getSubject(), 0, 3) != 'Re:')
				$re = 'Re:';
			$newComment->setSubject($re.$message->getSubject());
		}
		$form = $this->createForm(new AddThreadType(), $newComment);
		return $this->render($theme->getPath('RLForumBundle', 'addComment.html.twig'), array(
				'theme' => $theme,
				'user' => $user,
				'message'=>$message,
				'form'=>$form->createView(),
			));
	}
}
