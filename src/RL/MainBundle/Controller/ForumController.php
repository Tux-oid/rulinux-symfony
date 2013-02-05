<?php
/**
 * Copyright (c) 2009 - 2012, Peter Vasilevsky
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
use RL\MainBundle\Entity\Reader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RL\MainBundle\Controller\AbstractController;
use RL\MainBundle\Form\Type\AddCommentType;
use RL\MainBundle\Form\Model\AddCommentForm;
use RL\MainBundle\Form\Type\EditCommentType;
use RL\MainBundle\Form\Model\EditCommentForm;
use RL\MainBundle\Entity\Message;
use RL\MainBundle\Entity\Section;

/**
 * RL\MainBundle\Controller\DefaultController
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ForumController extends AbstractController
{
    /**
     * @Route("/new_thread/{sectionRewrite}/{subsectionRewrite}", name="new_thread", requirements = {"subsectionRewrite"=".*"})
     */
    public function newThreadAction($sectionRewrite, $subsectionRewrite)
    {
        //FIXME: add subsection selection in form
        $user = $this->get('security.context')->getToken()->getUser();
        $doctrine = $this->get('doctrine');
        $request = $this->getRequest();
        /** @var $section \RL\MainBundle\Entity\Section */
        $section = $doctrine->getRepository('RLMainBundle:Section')->findOneByRewrite($sectionRewrite);
        $subsectionRepository = $doctrine->getRepository($section->getBundle() . ':Subsection');
        $subsection = $subsectionRepository->getSubsectionByRewrite($subsectionRewrite, $section);
        //save thread in database
        $sbm = $request->request->get('submit');
        $hlpCls = $section->getBundleNamespace() . '\Form\Handler\ThreadHandler';
        /** @var $helper \RL\MainBundle\Form\Handler\ThreadHandlerInterface */
        $helper = new $hlpCls();
        if (isset($sbm)) {
            $helper->saveThread($doctrine, $request, $section, $subsection, $user);

            return $this->redirect(
                $this->generateUrl(
                    "subsection",
                    array("sectionRewrite" => $sectionRewrite, "subsectionRewrite" => $subsectionRewrite)
                )
            );
        }
        //preview
        $previewMode = false;
        $pr_val = $request->request->get('preview');
        $formCls = $section->getBundleNamespace() . '\Form\Model\AddThreadForm';
        $preview = '';
        if (isset($pr_val)) {
            $previewMode = true;
            $newThread = new $formCls($user);
            $helper->preview($newThread, $request);
            $preview = new $formCls($user);
            $helper->preview($preview, $request);
            $preview->setComment($user->getMark()->render($preview->getComment()));
        } else {
            $newThread = new $formCls($user);
        }
        //show form
        $typeCls = $section->getBundleNamespace() . '\Form\Type\AddThreadType';
        $form = $this->createForm(new $typeCls(), $newThread);

        return $this->render(
            $section->getBundle() . ':Forum:newThread.html.twig',
            array(
                'form' => $form->createView(),
                'subsection' => $subsectionRewrite,
                'previewMode' => $previewMode,
                'preview' => $preview,
                'message' => $newThread,
                'section' => $section,
            )
        );
    }

    /**
     * @Route("/thread/{id}/{page}", name="thread", defaults={"page" = 1})
     */
    public function threadAction($id, $page)
    {
        /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
        $user = $this->get('security.context')->getToken()->getUser();
        /** @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = $this->get('doctrine');
        /** @var $threadRepository \RL\MainBundle\Entity\Repository\ThreadRepository */
        $threadRepository = $doctrine->getRepository('RLMainBundle:Thread');
        /** @var $section \RL\MainBundle\Entity\Section */
        $section = $threadRepository->findOneById($id)->getSubsection()->getSection();
        $itemsCount = count($threadRepository->findOneById($id)->getMessages());
        $threadRepository = $doctrine->getRepository($section->getBundle() . ':Thread');
        /** @var $thread \RL\MainBundle\Entity\Thread */
        $thread = $threadRepository->findOneById($id);
        $itemsOnPage = $user->getCommentsOnPage();
        $pagesCount = ceil(($itemsCount - 1) / $itemsOnPage);
        $pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
        $startMessage = $threadRepository->getThreadStartMessageById($id);
        if (null === $startMessage) {
            return $this->renderMessage('Thread not found', 'Thread with specified id isn\'t found');
        }
        $threadComments = $threadRepository->getThreadCommentsById($id, $itemsOnPage, $offset);
        $pagesStr = $this->get('rl_main.paginator')->draw(
            $itemsOnPage,
            $itemsCount - 1,
            $page,
            'thread',
            array(
                "id" => $id,
                "page" => $page
            )
        );
        $neighborThreads = $threadRepository->getNeighborThreadsById($id);
        /** @var $readersRepository \RL\MainBundle\Entity\Repository\ReaderRepository */
        $readersRepository = $doctrine->getRepository('RLMainBundle:Reader');
        $readers = $readersRepository->getExpiredReaders($thread, $user, $this->get('session')->getId());
        /** @var $reader \RL\MainBundle\Entity\Reader */
        foreach($readers as $reader) {
            $reader->getThread()->removeReader($reader);
            $reader->getUser()->removeReadThread($reader);
            $doctrine->getManager()->remove($reader);
        }
        $thread->addReader(new Reader($user, $thread, $this->get('session')->getId()));
        $doctrine->getManager()->flush();
        $readers = $readersRepository->getReaders($thread);

        return $this->render(
            'RLMainBundle:Forum:thread.html.twig',
            array(
                'startMessage' => $startMessage,
                'messages' => $threadComments,
                'pages' => $pagesStr,
                'neighborThreads' => $neighborThreads,
                'section' => $section,
                'readers' => $readers
            )
        );
    }

    /**
     * @Route("/comment/{threadId}/{commentId}", name="comment")
     */
    public function commentAction($threadId, $commentId)
    {
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
            $thread = $threadRepository->findOneById($threadId);
            $message = new Message();
            if ($user->isAnonymous()) {
                $user = $user->getDbAnonymous();
            }
            $message->setUser($user);
            $message->setSubject($thr['subject']);
            $message->setComment($user->getMark()->render($thr['comment']));
            $message->setRawComment($thr['comment']);
            $message->setThread($thread);
            $message->setReferer($doctrine->getRepository('RLMainBundle:Message')->findOneById($commentId));
            $em->persist($message);
            $em->flush();

            return $this->redirect(
                $this->generateUrl("thread", array("id" => $threadId, "page" => 1))
            ); //FIXME: set url for redirecting
        }
        //preview
        $previewValue = $request->request->get('preview');
        $preview = '';
        $newComment = new AddCommentForm($user);
        if (isset($previewValue)) {
            $previewThread = $request->request->get('addComment');
            $newComment->setSubject($previewThread['subject']);
            $newComment->setComment($previewThread['comment']);
            $preview = new AddCommentForm($user);
            $preview->setSubject($previewThread['subject']);
            $preview->setComment($user->getMark()->render($previewThread['comment']));
        } else {
            $messageRepository = $doctrine->getRepository('RLMainBundle:Message');
            $preview = $messageRepository->findOneById($commentId);
            $re = '';
            if (substr($preview->getSubject(), 0, 3) != 'Re:') {
                $re = 'Re:';
            }
            $newComment->setSubject($re . $preview->getSubject());
        }
        $form = $this->createForm(new AddCommentType(), $newComment);

        return $this->render(
            'RLMainBundle:Forum:addComment.html.twig',
            array(
                'preview' => $preview,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/message/{messageId}/edit", name="editMessage")
     */
    public function editMessage($messageId)
    {
        /** @var $securityContext \Symfony\Component\Security\Core\SecurityContext */
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $doctrine = $this->get('doctrine');
        $messageRepository = $doctrine->getRepository('RLMainBundle:Message');
        /** @var $message \RL\MainBundle\Entity\Message */
        $message = $messageRepository->findOneById($messageId);
        //check access
        if ($message->getUser() != $user && !$securityContext->isGranted('ROLE_MODER')) {
            return $this->renderMessage('Access denied', 'You haven\'t privileges to edit this message');
        }
        if ($user->isAnonymous()) {
            if ($message->getSessionId() != $this->get('session')->getId()) {
                return $this->renderMessage('Access denied', 'You haven\'t privileges to edit this message');
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

            return $this->redirect(
                $this->generateUrl("thread", array("id" => $message->getThread()->getId()))
            ); //FIXME: set url for redirecting
        }
        //editing
        $comment = new EditCommentForm();
        $comment->setSubject($message->getSubject());
        $comment->setComment($message->getRawComment());
        $form = $this->createForm(new EditCommentType(), $comment);

        return $this->render(
            'RLMainBundle:Forum:editComment.html.twig',
            array(
                'message' => $message,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/view/{sectionRewrite}/{subsectionRewrite}/{page}", name="subsection", defaults={"page" = 1}, requirements = {"subsectionRewrite"=".*"})
     */
    public function subsectionAction($sectionRewrite, $subsectionRewrite, $page)
    {
        /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
        $user = $this->get('security.context')->getToken()->getUser();
        $subsectionRepository = $this->get('doctrine')->getRepository('RLMainBundle:Subsection');
        $sectionRepository = $this->get('doctrine')->getRepository('RLMainBundle:Section');
        /** @var $section \RL\MainBundle\Entity\Section */
        $section = $sectionRepository->findOneByRewrite($sectionRewrite);
        /** @var $subsection \RL\MainBundle\Entity\Subsection */
        $subsection = $subsectionRepository->getSubsectionByRewrite($subsectionRewrite, $section);
        if (empty($subsection)) {
            return $this->renderMessage('unknown subsection', 'subsection ' . $subsectionRewrite . ' not found');
        }
        $threadRepository = $this->get('doctrine')->getRepository($section->getBundle() . ':Thread');
        $itemsCount = count($subsection->getThreads());
        $itemsOnPage = $user->getThreadsOnPage();
        $pagesCount = ceil(($itemsCount) / $itemsOnPage);
        $pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
        $threads = $threadRepository->getThreads($subsection, $user->getThreadsOnPage(), $offset);
        $commentsCount = $threadRepository->getCommentsCount($subsection, $user->getThreadsOnPage(), $offset);
        $pagesStr = $this->get('rl_main.paginator')->draw(
            $itemsOnPage,
            $itemsCount,
            $page,
            'subsection',
            array(
                "sectionRewrite" => $sectionRewrite,
                "subsectionRewrite" => $subsectionRewrite,
                "page" => $page
            )
        );

        return $this->render(
            $section->getBundle() . ':Forum:subsection.html.twig',
            array(
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
     * @Route("/view/{sectionRewrite}", name="section")
     */
    public function sectionAction($sectionRewrite)
    {
        $doctrine = $this->get('doctrine');
        $sectionRepository = $doctrine->getRepository('RLMainBundle:Section');
        /** @var $section \RL\MainBundle\Entity\Section */
        $section = $sectionRepository->findOneByRewrite($sectionRewrite);
        $subsections = $section->getSubsections();
        $threadsCount = $doctrine->getRepository($section->getBundle() . ':Thread')->getThreadsCount($section);

        return $this->render(
            $section->getBundle() . ':Forum:forum.html.twig',
            array(
                'subsections' => $subsections,
                'threadsCount' => $threadsCount,
                'section' => $section,
            )
        );
    }

    /**
     * @Route("/comments/{pageUserName}/{page}", name="userComments", defaults={"pageUserName" = "", "page" = 1})
     */
    public function commentsAction($pageUserName, $page)
    {
        /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
        $user = $this->get('security.context')->getToken()->getUser();
        /** @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = $this->get('doctrine');
        /** @var $userRepository \RL\MainBundle\Entity\Repository\UserRepository */
        $userRepository = $doctrine->getRepository("RLMainBundle:User");
        if ($pageUserName === "") {
            $pageUser = $user;
        } else {
            $pageUser = $userRepository->findOneByUsername($pageUserName);
            if (null === $pageUser) {
                return $this->renderMessage('unknown user', 'user ' . $pageUserName . ' not found');
            }
        }
        $itemsCount = count($pageUser->getMessages());
        $itemsOnPage = $user->getCommentsOnPage();
        $pagesCount = ceil(($itemsCount) / $itemsOnPage);
        $pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
        $messages = $userRepository->getLastMessages($pageUser, $itemsOnPage, $offset);
        $pagesStr = $this->get('rl_main.paginator')
            ->draw(
            $itemsOnPage,
            $itemsCount,
            $page,
            'userComments',
            array(
                "pageUserName" => $pageUser->getUsername(),
                "page" => $page
            )
        );


        return $this->render(
            'RLMainBundle:Forum:userComments.html.twig',
            array(
                'pageUser' => $pageUser,
                'messages' => $messages,
                'pages' => $pagesStr
            )
        );
    }

    /**
     * @Route("/responses/{pageUserName}/{page}", name="userResponses", defaults={"pageUserName" = "", "page" = 1})
     */
    public function responsesAction($pageUserName, $page)
    {
        /** @var $user \RL\MainBundle\Security\User\RLUserInterface */
        $user = $this->get('security.context')->getToken()->getUser();
        /** @var $userRepository \RL\MainBundle\Entity\Repository\UserRepository */
        $userRepository = $this->get('doctrine')->getRepository("RLMainBundle:User");
        if ($pageUserName === "") {
            $pageUser = $user;
        } else {
            $pageUser = $userRepository->findOneByUsername($pageUserName);
            if (null === $pageUser) {
                return $this->renderMessage('unknown user', 'user ' . $pageUserName . ' not found');
            }
        }
        $itemsCount = count($pageUser->getMessages());
        $itemsOnPage = $user->getCommentsOnPage();
        $pagesCount = ceil(($itemsCount) / $itemsOnPage);
        $pagesCount > 1 ? $offset = $itemsOnPage * ($page - 1) : $offset = 0;
        $messages = $userRepository->getLastResponses($pageUser, $itemsOnPage, $offset);
        $pagesStr = $this->get('rl_main.paginator')->draw(
            $itemsOnPage,
            $itemsCount,
            $page,
            'userComments',
            array(
                "pageUserName" => $pageUser->getUsername(),
                "page" => $page
            )
        );


        return $this->render(
            'RLMainBundle:Forum:userResponses.html.twig',
            array(
                'pageUser' => $pageUser,
                'messages' => $messages,
                'pages' => $pagesStr
            )
        );
    }
}
