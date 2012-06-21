<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RL\MainBundle\Helper\Pages;
use RL\ForumBundle\Form\AddCommentType;
use RL\ForumBundle\Form\AddCommentForm;
use RL\ForumBundle\Form\EditCommentType;
use RL\ForumBundle\Form\EditCommentForm;
use RL\ForumBundle\Entity\Message;
use RL\MainBundle\Entity\Section;

class DefaultController extends Controller
{
	/**
	 * @Route("/new_thread_in_{sectionRewrite}_{subsectionRewrite}", name="new_thread", requirements = {"subsectionRewrite"=".*"})
	 */
	public function newThreadAction($sectionRewrite, $subsectionRewrite)
	{
		//FIXME: add subsection selection in form
		$theme = $this->get('rl_themes.theme.provider');
		$user = $this->get('security.context')->getToken()->getUser();
		$doctrine = $this->get('doctrine');
		$request = $this->getRequest();
		$section = $doctrine->getRepository('RLMainBundle:Section')->findOneByRewrite($sectionRewrite);
		$subsectionRepository = $doctrine->getRepository($section->getBundle().':Subsection');
		$subsection = $subsectionRepository->getSubsectionByRewrite($subsectionRewrite, $section);
		//save thread in database
		$sbm = $request->request->get('submit');
		$hlpCls = $section->getBundleNamespace().'\Helper\ThreadHelper';
		$helper = new $hlpCls();
		if(isset($sbm))
		{
			$helper->saveThread($doctrine, $request, $section, $subsection, $user);
			return $this->redirect($this->generateUrl("subsection", array("sectionRewrite" => $sectionRewrite, "subsectionRewrite" => $subsectionRewrite)));
		}
		//preview
		$preview = false;
		$pr_val = $request->request->get('preview');
		$formCls = $section->getBundleNamespace().'\Form\AddThreadForm';
		if(isset($pr_val))
		{
			$preview = true;
			$newThread = new $formCls($user);
			$helper->preview($newThread, $request);
		}
		else
			$newThread = new $formCls($user);
		//show form
		$typeCls = $section->getBundleNamespace().'\Form\AddThreadType';
		$form = $this->createForm(new $typeCls(), $newThread);
		return $this->render($theme->getPath($section->getBundle(), 'newThread.html.twig'), array(
				'theme' => $theme,
				'user' => $user,
				'form' => $form->createView(),
				'subsection' => $subsectionRewrite,
				'preview' => $preview,
				'message' => $newThread,
				'section' => $section,
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
		$section = $threadRepository->findOneById($id)->getSubsection()->getSection();
		$itemsCount = count($threadRepository->findOneById($id)->getMessages());
		$threadRepository = $doctrine->getRepository($section->getBundle().':Thread');
		$itemsOnPage = $user->getCommentsOnPage();
		$pagesCount = ceil(($itemsCount - 1) / $itemsOnPage);
		$pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
		$startMessage = $threadRepository->getThreadStartMessageById($id);
		$threadComments = $threadRepository->getThreadCommentsById($id, $itemsOnPage, $offset);
		$pages = new Pages($this->get('router'), $itemsOnPage, $itemsCount - 1, $page, 'thread', array("id" => $id, "page" => $page));
		$pagesStr = $pages->draw();
		$neighborThreads = $threadRepository->getNeighborThreadsById($id);
		return $this->render($theme->getPath('RLForumBundle', 'thread.html.twig'), array(
				'theme' => $theme,
				'user' => $user,
				'startMessage' => $startMessage,
				'messages' => $threadComments,
				'pages' => $pagesStr,
				'neighborThreads' => $neighborThreads,
				'section'=>$section,
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
			return $this->redirect($this->generateUrl("thread", array("id" => $thread_id, "page" => 1))); //FIXME: set url for redirecting
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
				'message' => $message,
				'form' => $form->createView(),
			));
	}
	/**
	 * @Route("/message/{messageId}/edit", name="editMessage") 
	 */
	public function editMessage($messageId)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$securityContext = $this->get('security.context');
		$user = $securityContext->getToken()->getUser();
		$doctrine = $this->get('doctrine');
		$messageRepository = $doctrine->getRepository('RLForumBundle:Message');
		$message = $messageRepository->findOneById($messageId);
		//check access
		if($message->getUser() != $user || !$securityContext->isGranted('ROLE_MODER'))
		{
			$legend = 'Access denied';
			$title = 'Edit message';
			$text = 'You have not privelegies to edit this message';
			return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array(
					'theme' => $theme,
					'user' => $user,
					'legend' => $legend,
					'title' => $title,
					'text' => $text,
				));
		}
		if($user->isAnonymous())
		{
			if($message->getSessionId != \session_id())
			{
				$legend = 'Access denied';
				$title = 'Edit message';
				$text = 'You have not privelegies to edit this message';
				return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array(
						'theme' => $theme,
						'user' => $user,
						'legend' => $legend,
						'title' => $title,
						'text' => $text,
					));
			}
		}
		//save comment in database
		$request = $this->getRequest();
		$sbm = $request->request->get('submit');
		if(isset($sbm))
		{
			$em = $doctrine->getEntityManager();
			$cmnt = $request->request->get('editComment');
			$message->setSubject($cmnt['subject']);
			$message->setComment($cmnt['comment']);
			$message->setRawComment($cmnt['comment']);
			$message->setChangedBy($user);
			$message->setChangedFor($cmnt['editionReason']);
//			$em->persist($message);
			$em->flush();
			return $this->redirect($this->generateUrl("thread", array("id" => $message->getThread()->getId()))); //FIXME: set url for redirecting
		}
		//editing
		$comment = new EditCommentForm();
		$comment->setSubject($message->getSubject());
		$comment->setComment($message->getRawComment());
		$form = $this->createForm(new EditCommentType(), $comment);
		return $this->render($theme->getPath('RLForumBundle', 'editComment.html.twig'), array(
				'theme' => $theme,
				'user' => $user,
				'message' => $message,
				'form' => $form->createView(),
			));
	}
	/**
	 * @Route("/section_{sectionRewrite}_subsection_{subsectionRewrite}/{page}", name="subsection", defaults={"page" = 1}, requirements = {"subsectionRewrite"=".*"})
	 */
	public function subsectionAction($sectionRewrite, $subsectionRewrite, $page)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$user = $this->get('security.context')->getToken()->getUser();
		$subsectionRepository = $this->get('doctrine')->getRepository('RLForumBundle:Subsection');
		$sectionRepository = $this->get('doctrine')->getRepository('RLMainBundle:Section');
		$section = $sectionRepository->findOneByRewrite($sectionRewrite);
		$subsection = $subsectionRepository->getSubsectionByRewrite($subsectionRewrite, $section);
		if(empty($subsection))
		{
			$legend = 'subsection not found';
			$title = 'unknown subsection';
			$text = 'subsection '.$subsectionRewrite.' not found';
			return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array(
					'theme' => $theme,
					'user' => $user,
					'legend' => $legend,
					'title' => $title,
					'text' => $text,
				));
		}
		$threadRepository = $this->get('doctrine')->getRepository($section->getBundle().':Thread');
		$itemsCount = count($subsection->getThreads());
		$itemsOnPage = $user->getThreadsOnPage();
		$pagesCount = ceil(($itemsCount) / $itemsOnPage);
		$pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
		$threads = $threadRepository->getThreads($subsection, $user->getThreadsOnPage(), $offset);
		$commentsCount = $threadRepository->getCommentsCount($subsection, $user->getThreadsOnPage(), $offset);
		$pages = new Pages($this->get('router'), $itemsOnPage, $itemsCount, $page, 'subsection', array("sectionRewrite" => $sectionRewrite, "subsectionRewrite" => $subsectionRewrite, "page" => $page));
		$pagesStr = $pages->draw();
		return $this->render($theme->getPath($section->getBundle(), 'subsection.html.twig'), array('theme' => $theme,
				'user' => $user,
				'subsection' => $subsection,
				'subsections' => $section->getSubsections(),
				'threads' => $threads,
				'commentsCount' => $commentsCount,
				'pages' => $pagesStr,
				'section' => $section,
				)
		);
	}
	/**
	 * @Route("/section_{sectionRewrite}", name="section")
	 */
	public function sectionAction($sectionRewrite)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$doctrine = $this->get('doctrine');
		$sectionRepository = $doctrine->getRepository('RLMainBundle:Section');
		$section = $sectionRepository->findOneByRewrite($sectionRewrite);
		$subsections = $section->getSubsections();
		$threadsCount = $doctrine->getRepository($section->getBundle().':Thread')->getThreadsCount($section);
		return $this->render($theme->getPath($section->getBundle(), 'forum.html.twig'), array('theme' => $theme,
				'user' => $this->get('security.context')->getToken()->getUser(),
				'subsections' => $subsections,
				'threadsCount' => $threadsCount,
				'section' => $section,
				)
		);
	}
}
