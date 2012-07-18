<?php
/**
 * @author Ax-xa-xa
 */
namespace RL\MainBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RL\MainBundle\Helper\Pages;

class MainController extends Controller
{
	/**
	 * @Route("/unconfirmed", name="unconfirmed")
	 */
	public function unconfirmedAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$theme = $this->get('rl_themes.theme.provider');
		$doctrine = $this->get('doctrine');
		$threadRepository = $doctrine->getRepository('RLNewsBundle:Thread');
		$unconfirmedThreads = $threadRepository->getUnconfirmed();
		return $this->render(
			$theme->getPath('RLMainBundle', 'unconfirmed.html.twig'), array('user' => $user, 'theme' => $theme, 'threads' => $unconfirmedThreads,)
		);
	}

	/**
	 * @Route("/rules", name="rules")
	 */
	public function rulesAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$theme = $this->get('rl_themes.theme.provider');
		$doctrine = $this->get('doctrine');
		$settingsRepository = $doctrine->getRepository('RLMainBundle:Settings');
		$rulesTitle = $settingsRepository->findOneByName('rulesTitle')->getValue();
		$rulesText = $settingsRepository->findOneByName('rulesText')->getValue();
		return $this->render(
			$theme->getPath('RLMainBundle', 'page.html.twig'), array('user' => $user, 'theme' => $theme, 'title' => $rulesTitle, 'text' => $rulesText,)
		);
	}

	/**
	 * @Route("/links", name="links")
	 */
	public function linksAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$theme = $this->get('rl_themes.theme.provider');
		$doctrine = $this->get('doctrine');
		$linksRepository = $doctrine->getRepository('RLMainBundle:Link');
		$links = $linksRepository->findAll();
		$title = 'Links';
		$text = '<ul>';
		foreach($links as $link)
		{
			$text = $text . '<li><a href="' . $link->getLink() . '">' . $link->getName() . '</a></li>';
		}
		$text = $text . '</ul>';
		return $this->render(
			$theme->getPath('RLMainBundle', 'page.html.twig'), array('user' => $user, 'theme' => $theme, 'title' => $title, 'text' => $text,)
		);
	}

	/**
	 * @Route("/{page}", name="index", defaults={"page" = 1}, requirements = {"page"="[0-9]*"})
	 * @Template()
	 */
	public function homepageAction($page)
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$theme = $this->get('rl_themes.theme.provider');
		$doctrine = $this->get('doctrine');
		$sectionRepository = $doctrine->getRepository('RLMainBundle:Section');
		$section = $sectionRepository->findOneByRewrite('news');
		$threadRepository = $doctrine->getRepository('RLNewsBundle:Thread');
		$itemsCount = $threadRepository->getNewsCount();
		$itemsOnPage = $user->getNewsOnPage();
		$pagesCount = ceil(($itemsCount) / $itemsOnPage);
		$pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
		$threads = $threadRepository->getNews($itemsOnPage, $offset);
		$pages = new Pages($this->get('router'), $itemsOnPage, $itemsCount, $page, 'index', array("page" => $page));
		$pagesStr = $pages->draw();
		return $this->render(
			$theme->getPath('RLMainBundle', 'index.html.twig'), array('user' => $user, 'theme' => $this->get('rl_themes.theme.provider'), 'threads' => $threads, 'pages' => $pagesStr, 'section' => $section,)
		);
	}

}
