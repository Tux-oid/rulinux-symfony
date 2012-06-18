<?php

namespace RL\NewsBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RL\MainBundle\Helper\Pages;

class DefaultController extends Controller
{
	/**
	 * @Route("/news", name="news")
	 */
	public function newsAction()
	{
		$theme = $this->get('rl_themes.theme.provider');
		$doctrine = $this->get('doctrine');
		$sectionRepository = $doctrine->getRepository('RLMainBundle:Section');
		$section = $sectionRepository->findOneByRewrite('news');
		$subsections = $section->getSubsections();
		$threadsCount = $doctrine->getRepository('RLForumBundle:Thread')->getThreadsCount($section);
		return $this->render($theme->getPath($section->getBundle(), 'news.html.twig'), array('theme' => $theme,
				'user' => $this->get('security.context')->getToken()->getUser(),
				'subsections' => $subsections,
				'threadsCount' => $threadsCount,
				'section' => $section,
				)
		);
	}
	/**
	 * @Route("/news_{name}/{page}", name="news_subsection", defaults={"page" = 1}, requirements = {"name"=".*"})
	 */
	public function subsectionAction($name, $page)
	{
		$theme = $this->get('rl_themes.theme.provider');
		$user = $this->get('security.context')->getToken()->getUser();
		$subsectionRepository = $this->get('doctrine')->getRepository('RLNewsBundle:NewsSubsection');
		$sectionRepository = $this->get('doctrine')->getRepository('RLMainBundle:Section');
		$section = $sectionRepository->findOneByRewrite('news');
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
		$threadRepository = $this->get('doctrine')->getRepository('RLNewsBundle:News');
		$itemsCount = count($subsection->getThreads());
		$itemsOnPage = $user->getThreadsOnPage();
		$pagesCount = ceil(($itemsCount) / $itemsOnPage);
		$pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
		$threads = $threadRepository->getThreads($subsection, $user->getThreadsOnPage(), $offset);
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
