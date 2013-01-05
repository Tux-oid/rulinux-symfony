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

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RL\MainBundle\Helper\Pages;
use RL\MainBundle\Form\AddCommentType;
use RL\MainBundle\Form\AddCommentForm;
use RL\MainBundle\Form\EditCommentType;
use RL\MainBundle\Form\EditCommentForm;
use RL\MainBundle\Entity\Message;
use RL\MainBundle\Entity\Section;

/**
 * RL\MainBundle\Controller\DefaultController
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ForumController extends Controller
{
    /**
     * @Route("/new_thread_in_{sectionRewrite}_{subsectionRewrite}", name="new_thread", requirements = {"subsectionRewrite"=".*"})
     */
    public function newThreadAction($sectionRewrite, $subsectionRewrite)
    {
        //FIXME: add subsection selection in form
        $theme = $this->get('rl_main.theme.provider');
        $user = $this->get('security.context')->getToken()->getUser();
        $doctrine = $this->get('doctrine');
        $request = $this->getRequest();
        /** @var $section \RL\MainBundle\Entity\Section */
        $section = $doctrine->getRepository('RLMainBundle:Section')->findOneByRewrite($sectionRewrite);
        $subsectionRepository = $doctrine->getRepository($section->getBundle().':Subsection');
        $subsection = $subsectionRepository->getSubsectionByRewrite($subsectionRewrite, $section);
        //save thread in database
        $sbm = $request->request->get('submit');
        $hlpCls = $section->getBundleNamespace().'\Helper\ThreadHelper';
        /** @var $helper \RL\MainBundle\Helper\ThreadHelperInterface */
        $helper = new $hlpCls();
        if (isset($sbm)) {
            $helper->saveThread($doctrine, $request, $section, $subsection, $user);

            return $this->redirect($this->generateUrl("subsection", array("sectionRewrite" => $sectionRewrite, "subsectionRewrite" => $subsectionRewrite)));
        }
        //preview
        $previewMode = false;
        $pr_val = $request->request->get('preview');
        $formCls = $section->getBundleNamespace().'\Form\AddThreadForm';
        $preview = '';
        if (isset($pr_val)) {
            $previewMode = true;
            $newThread = new $formCls($user);
            $helper->preview($newThread, $request);
            $preview = new $formCls($user);
            $helper->preview($preview, $request);
            $preview->setComment($user->getMark()->render($preview->getComment()));
        } else
            $newThread = new $formCls($user);
        //show form
        $typeCls = $section->getBundleNamespace().'\Form\AddThreadType';
        $form = $this->createForm(new $typeCls(), $newThread);

        return $this->render($theme->getPath('newThread.html.twig', $section->getBundle()), array(
                'theme' => $theme,
                'user' => $user,
                'form' => $form->createView(),
                'subsection' => $subsectionRewrite,
                'previewMode' => $previewMode,
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
        $theme = $this->get('rl_main.theme.provider');
        /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
        $user = $this->get('security.context')->getToken()->getUser();
        $doctrine = $this->get('doctrine');
        $threadRepository = $doctrine->getRepository('RLMainBundle:Thread');
        /** @var $section \RL\MainBundle\Entity\Section */
        $section = $threadRepository->findOneById($id)->getSubsection()->getSection();
        $itemsCount = count($threadRepository->findOneById($id)->getMessages());
        $threadRepository = $doctrine->getRepository($section->getBundle().':Thread');
        $itemsOnPage = $user->getCommentsOnPage();
        $pagesCount = ceil(($itemsCount - 1) / $itemsOnPage);
        $pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
        $startMessage = $threadRepository->getThreadStartMessageById($id);
        if (null === $startMessage) {
            $legend = 'Thread not found';
            $title = 'Thread not found';
            $text = 'Thread with specified id isn\'t found';

            return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array(
                    'theme' => $theme,
                    'user' => $user,
                    'legend' => $legend,
                    'title' => $title,
                    'text' => $text,
                ));
        }
        $threadComments = $threadRepository->getThreadCommentsById($id, $itemsOnPage, $offset);
        $pages = new Pages($this->get('router'), $itemsOnPage, $itemsCount - 1, $page, 'thread', array("id" => $id, "page" => $page));
        $pagesStr = $pages->draw();
        $neighborThreads = $threadRepository->getNeighborThreadsById($id);

        return $this->render($theme->getPath('thread.html.twig'), array(
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
        $theme = $this->get('rl_main.theme.provider');
        /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
        $user = $this->get('security.context')->getToken()->getUser();
        $doctrine = $this->get('doctrine');
        $request = $this->getRequest();
        //save comment in database
        $sbm = $request->request->get('submit');
        if (isset($sbm)) {
            $em = $doctrine->getEntityManager();
            $thr = $request->request->get('addComment');
            $threadRepository = $doctrine->getRepository('RLMainBundle:Thread');
            $thread = $threadRepository->findOneById($thread_id);
            $message = new Message();
            if ($user->isAnonymous()) {
                $user = $user->getDbAnonymous();
            }
            $message->setUser($user);
            $message->setSubject($thr['subject']);
            $message->setComment($user->getMark()->render($thr['comment']));
            $message->setRawComment($thr['comment']);
            $message->setThread($thread);
            $message->setReferer($doctrine->getRepository('RLMainBundle:Message')->findOneById($comment_id));
            $em->persist($message);
            $em->flush();

            return $this->redirect($this->generateUrl("thread", array("id" => $thread_id, "page" => 1))); //FIXME: set url for redirecting
        }
        //preview
        $pr_val = $request->request->get('preview');
        $preview = '';
        $newComment = new AddCommentForm($user);
        if (isset($pr_val)) {
            $prv_thr = $request->request->get('addComment');
            $newComment->setSubject($prv_thr['subject']);
            $newComment->setComment($prv_thr['comment']);
            $preview = new AddCommentForm($user);
            $preview->setSubject($prv_thr['subject']);
            $preview->setComment($user->getMark()->render($prv_thr['comment']));
        } else {
            $messageRepository = $doctrine->getRepository('RLMainBundle:Message');
            $preview = $messageRepository->findOneById($comment_id);
            $re = '';
            if(substr($preview->getSubject(), 0, 3) != 'Re:')
                $re = 'Re:';
            $newComment->setSubject($re.$preview->getSubject());
        }
        $form = $this->createForm(new AddCommentType(), $newComment);

        return $this->render($theme->getPath('addComment.html.twig'), array(
                'theme' => $theme,
                'user' => $user,
                'preview' => $preview,
                'form' => $form->createView(),
            ));
    }
    /**
     * @Route("/message/{messageId}/edit", name="editMessage")
     */
    public function editMessage($messageId)
    {
        $theme = $this->get('rl_main.theme.provider');
        /** @var $securityContext \Symfony\Component\Security\Core\SecurityContext */
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $doctrine = $this->get('doctrine');
        $messageRepository = $doctrine->getRepository('RLMainBundle:Message');
        /** @var $message \RL\MainBundle\Entity\Message */
        $message = $messageRepository->findOneById($messageId);
        //check access
        if ($message->getUser() != $user && !$securityContext->isGranted('ROLE_MODER')) {
            $legend = 'Access denied';
            $title = 'Edit message';
            $text = 'You have not privelegies to edit this message';

            return $this->render($theme->getPath('fieldset.html.twig'), array(
                    'theme' => $theme,
                    'user' => $user,
                    'legend' => $legend,
                    'title' => $title,
                    'text' => $text,
                ));
        }
        if ($user->isAnonymous()) {
            if ($message->getSessionId() != \session_id()) {
                $legend = 'Access denied';
                $title = 'Edit message';
                $text = 'You have not privelegies to edit this message';

                return $this->render($theme->getPath('fieldset.html.twig'), array(
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
        if (isset($sbm)) {
            /** @var $em \Doctrine\ORM\EntityManager */
            $em = $doctrine->getEntityManager();
            $cmnt = $request->request->get('editComment');
            $message->setSubject($cmnt['subject']);
            $message->setComment($user->getMark()->render($cmnt['comment']));
            $message->setRawComment($cmnt['comment']);
            if ($user->isAnonymous()) {
                $user = $user->getDbAnonymous();
            }
            $message->addChangedBy($user);
            $message->setChangedFor($cmnt['editionReason']);
            $em->flush();

            return $this->redirect($this->generateUrl("thread", array("id" => $message->getThread()->getId()))); //FIXME: set url for redirecting
        }
        //editing
        $comment = new EditCommentForm();
        $comment->setSubject($message->getSubject());
        $comment->setComment($message->getRawComment());
        $form = $this->createForm(new EditCommentType(), $comment);

        return $this->render($theme->getPath('editComment.html.twig'), array(
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
        $theme = $this->get('rl_main.theme.provider');
        /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
        $user = $this->get('security.context')->getToken()->getUser();
        $subsectionRepository = $this->get('doctrine')->getRepository('RLMainBundle:Subsection');
        $sectionRepository = $this->get('doctrine')->getRepository('RLMainBundle:Section');
        /** @var $section \RL\MainBundle\Entity\Section */
        $section = $sectionRepository->findOneByRewrite($sectionRewrite);
        /** @var $subsection \RL\MainBundle\Entity\Subsection */
        $subsection = $subsectionRepository->getSubsectionByRewrite($subsectionRewrite, $section);
        if (empty($subsection)) {
            $legend = 'subsection not found';
            $title = 'unknown subsection';
            $text = 'subsection '.$subsectionRewrite.' not found';

            return $this->render($theme->getPath('fieldset.html.twig'), array(
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

        return $this->render($theme->getPath('subsection.html.twig', $section->getBundle()), array('theme' => $theme,
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
        $theme = $this->get('rl_main.theme.provider');
        $doctrine = $this->get('doctrine');
        $sectionRepository = $doctrine->getRepository('RLMainBundle:Section');
        /** @var $section \RL\MainBundle\Entity\Section */
        $section = $sectionRepository->findOneByRewrite($sectionRewrite);
        $subsections = $section->getSubsections();
        $threadsCount = $doctrine->getRepository($section->getBundle().':Thread')->getThreadsCount($section);

        return $this->render($theme->getPath('forum.html.twig', $section->getBundle()), array('theme' => $theme,
                'user' => $this->get('security.context')->getToken()->getUser(),
                'subsections' => $subsections,
                'threadsCount' => $threadsCount,
                'section' => $section,
                )
        );
    }
}
