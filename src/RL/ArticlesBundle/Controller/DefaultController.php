<?php

namespace RL\ArticlesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RL\MainBundle\Helper\Pages;
use RL\ForumBundle\Form\AddThreadForm;
use RL\ForumBundle\Form\AddThreadType;
use RL\MainBundle\Entity\Section;
use RL\ForumBundle\Entity\Subsection;
use RL\ArticlesBundle\Entity\Article;
use RL\ForumBundle\Entity\Message;

class DefaultController extends Controller
{
    /**
	 * @Route("/articles", name="articles")
	 */
	public function articlesAction()
	{
		$theme = $this->get('rl_themes.theme.provider');
		$doctrine = $this->get('doctrine');
		$sectionRepository = $doctrine->getRepository('RLMainBundle:Section');
		$section = $sectionRepository->findOneByRewrite('articles');
		$subsections = $section->getSubsections();
		$threadsCount = $doctrine->getRepository('RLForumBundle:Thread')->getThreadsCount($section);
		return $this->render($theme->getPath($section->getBundle(), 'articles.html.twig'), array('theme' => $theme,
				'user' => $this->get('security.context')->getToken()->getUser(),
				'subsections' => $subsections,
				'threadsCount' => $threadsCount,
				'section' => $section,
				)
		);
	}
	/**
	 * @Route("/new_thread_in_articles_{subsectionRewrite}", name="new_thread_in_articles")
	 */
	public function newArticleAction($subsectionRewrite)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$user = $this->get('security.context')->getToken()->getUser();
		$doctrine = $this->get('doctrine');
		$request = $this->getRequest();
		$section = $doctrine->getRepository('RLMainBundle:Section')->findOneByRewrite('articles');
		$subsectionRepository = $doctrine->getRepository('RLForumBundle:Subsection');
		$subsection = $subsectionRepository->getSubsectionByRewrite($subsectionRewrite, $section);
		//save thread in database
		$sbm = $request->request->get('submit');
		if(isset($sbm))
		{
			$em = $doctrine->getEntityManager();
			$thr = $request->request->get('addThread');
			$thread = new Article();
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
	 * @Route("/articles_{name}/{page}", name="art_subsection", defaults={"page" = 1}, requirements = {"name"=".*"})
	 */
	public function subsectionAction($name, $page)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$user = $this->get('security.context')->getToken()->getUser();
		$subsectionRepository = $this->get('doctrine')->getRepository('RLForumBundle:Subsection');
		$sectionRepository = $this->get('doctrine')->getRepository('RLMainBundle:Section');
		$section = $sectionRepository->findOneByRewrite('articles');
		$subsection = $subsectionRepository->getSubsectionByRewrite($name, $section);
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
		$threadRepository = $this->get('doctrine')->getRepository('RLArticlesBundle:Article');
		$itemsCount = count($subsection->getThreads());
		$itemsOnPage = $user->getThreadsOnPage();
		$pagesCount = ceil(($itemsCount) / $itemsOnPage);
		$pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
		$threads = $threadRepository->getArticles($subsection, $user->getThreadsOnPage(), $offset);
		$commentsCount = $threadRepository->getCommentsCount($subsection, $user->getThreadsOnPage(), $offset);
		$pages = new Pages($this->get('router'), $itemsOnPage, $itemsCount, $page, 'subsection', array("name" => $name, "page" => $page));
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
}
