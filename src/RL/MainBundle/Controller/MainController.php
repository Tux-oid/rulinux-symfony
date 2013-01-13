<?php
/**
 * Copyright (c) 2008 - 2012, Peter Vasilevsky
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the RL nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL PETER VASILEVSKY BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

namespace RL\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RL\MainBundle\Form\Model\TrackerForm;
use RL\MainBundle\Form\Type\TrackerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RL\MainBundle\Helper\Pages;

/**
 * RL\MainBundle\Controller\MainController
 *
 * @author Ax-xa-xa
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class MainController extends Controller
{
    /**
     * @Route("/unconfirmed", name="unconfirmed")
     */
    public function unconfirmedAction()
    {
        $theme = $this->get('rl_main.theme.provider');
        $doctrine = $this->get('doctrine');
        /** @var $threadRepository \RL\ArticlesBundle\Entity\Repository\ThreadRepository */
        $threadRepository = $doctrine->getRepository('RLNewsBundle:Thread');
        $unconfirmedThreads = $threadRepository->getUnconfirmed();

        return $this->render(
            $theme->getPath('unconfirmed.html.twig'), array('threads' => $unconfirmedThreads,)
        );
    }

    /**
     * @Route("/tracker/{hours}", name="tracker", defaults={"hours" = 3}, requirements = {"hours"="[0-9]*"})
     */
    public function trackerAction($hours)
    {
        $theme = $this->get('rl_main.theme.provider');
        $doctrine = $this->get('doctrine');
        $request = $this->getRequest();
        $sbm = $request->request->get('submit');
        if (isset($sbm)) {
            $tracker = $request->request->get('tracker');
            $hours = (int) $tracker['hours'];
        }
        /** @var $messageRepository \RL\MainBundle\Entity\Repository\MessageRepository */
        $messageRepository = $doctrine->getRepository('RLMainBundle:Message');
        $messages = $messageRepository->getMessagesForLastHours($hours);
        $form = $this->createForm(new TrackerType(), new TrackerForm($hours));

        return $this->render(
            $theme->getPath('tracker.html.twig'), array('form'=>$form->createView(), 'messages'=>$messages, 'hours' => $hours )
        );
    }

    /**
     * @Route("/rules", name="rules")
     */
    public function rulesAction()
    {
        $theme = $this->get('rl_main.theme.provider');
        $doctrine = $this->get('doctrine');
        $settingsRepository = $doctrine->getRepository('RLMainBundle:Settings');
        $rulesTitle = $settingsRepository->findOneByName('rulesTitle')->getValue();
        $rulesText = $settingsRepository->findOneByName('rulesText')->getValue();

        return $this->render(
            $theme->getPath('page.html.twig'), array('title' => $rulesTitle, 'text' => $rulesText,)
        );
    }

    /**
     * @Route("/links", name="links")
     */
    public function linksAction()
    {
        $theme = $this->get('rl_main.theme.provider');
        $doctrine = $this->get('doctrine');
        /** @var $linksRepository \Doctrine\Orm\EntityRepository */
        $linksRepository = $doctrine->getRepository('RLMainBundle:Link');
        $links = $linksRepository->findAll();
        $title = 'Links';
        $text = '<ul>';
        /** @var $link \RL\MainBundle\Entity\Link */
        foreach ($links as $link) {
            $text = $text . '<li><a href="' . $link->getLink() . '">' . $link->getName() . '</a></li>';
        }
        $text = $text . '</ul>';

        return $this->render(
            $theme->getPath('page.html.twig'), array('title' => $title, 'text' => $text,)
        );
    }

    /**
     * @Route("/mark/{id}", name="mark", defaults={"id"=null})
     */
    public function markAction($id)
    {
        /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
        $user = $this->get('security.context')->getToken()->getUser();
        $theme = $this->get('rl_main.theme.provider');
        /** @var $markRepository \Doctrine\Orm\EntityRepository */
        $markRepository = $this->getDoctrine()->getRepository('RLMainBundle:Mark');
        if (null === $id) {
            $mark = $user->getMark();
        } else {
            $mark = $markRepository->findOneById($id);
        }

        return $this->render($theme->getPath('mark.html.twig'), array('mark' => $mark));
    }

    /**
     * @Route("/{page}", name="index", defaults={"page" = 1}, requirements = {"page"="[0-9]*"})
     */
    public function homepageAction($page)
    {
        /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
        $user = $this->get('security.context')->getToken()->getUser();
        $theme = $this->get('rl_main.theme.provider');
        $doctrine = $this->get('doctrine');
        $sectionRepository = $doctrine->getRepository('RLMainBundle:Section');
        $section = $sectionRepository->findOneByRewrite('news');
        /** @var $threadRepository \RL\NewsBundle\Entity\Repository\ThreadRepository */
        $threadRepository = $doctrine->getRepository('RLNewsBundle:Thread');
        $itemsCount = $threadRepository->getNewsCount();
        $itemsOnPage = $user->getNewsOnPage();
        $pagesCount = ceil(($itemsCount) / $itemsOnPage);
        $pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
        $threads = $threadRepository->getNews($itemsOnPage, $offset);
        $pages = new Pages($this->get('router'), $itemsOnPage, $itemsCount, $page, 'index', array("page" => $page));
        $pagesStr = $pages->draw();

        return $this->render(
            $theme->getPath('index.html.twig'), array('threads' => $threads, 'pages' => $pagesStr, 'section' => $section,)
        );
    }

}
