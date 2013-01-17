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

namespace RL\NewsBundle\Form\Handler;

use RL\MainBundle\Form\Handler\ThreadHandlerInterface;
use RL\NewsBundle\Entity\Thread;
use RL\MainBundle\Entity\Section;
use RL\MainBundle\Entity\Subsection;
use RL\MainBundle\Security\User\RLUserInterface;
use RL\MainBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * RL\NewsBundle\Form\Handler\ThreadHandler
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ThreadHandler implements ThreadHandlerInterface
{
    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \RL\MainBundle\Entity\Section $section
     * @param \RL\MainBundle\Entity\Subsection $subsection
     * @param \RL\MainBundle\Security\User\RLUserInterface $user
     */
    public function saveThread(
        Registry &$doctrine,
        Request &$request,
        Section $section,
        Subsection $subsection,
        RLUserInterface $user
    ) {
        $em = $doctrine->getManager();
        $thr = $request->request->get('addThread');
        $thread = new Thread();
        $thread->setSubsection($subsection);
        $thread->setProoflink($thr['prooflink']);
        $em->persist($thread);
        $message = new Message();
        if ($user->isAnonymous()) {
            $user = $user->getDbAnonymous();
        }
        $message->setUser($user);
        $message->setSubject($thr['subject']);
        $message->setComment($user->getMark()->render($thr['comment']));
        $message->setRawComment($thr['comment']);
        $message->setThread($thread);
        $em->persist($message);
        $em->flush();
    }

    /**
     * @param $thread
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function preview(&$thread, Request &$request)
    {
        $previewThread = $request->request->get('addThread');
        $thread->setSubject($previewThread['subject']);
        $thread->setComment($previewThread['comment']);
        $thread->setProoflink($previewThread['prooflink']);
    }
}
