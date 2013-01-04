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

namespace RL\ForumBundle\Helper;

use RL\ForumBundle\Helper\ThreadHelperInterface;
use RL\ForumBundle\Entity\Thread;
use RL\ForumBundle\Entity\Message;

/**
 * RL\ForumBundle\Helper\ThreadHelper
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ThreadHelper implements ThreadHelperInterface
{
    public function saveThread(\Doctrine\Bundle\DoctrineBundle\Registry &$doctrine, \Symfony\Component\HttpFoundation\Request &$request, $section, $subsection, $user)
    {
        $em = $doctrine->getManager();
        $thr = $request->request->get('addThread');
        $threadCls = $section->getBundleNamespace().'\Entity\Thread';
        $thread = new $threadCls();
        $thread->setSubsection($subsection);
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
    public function preview(&$thread, \Symfony\Component\HttpFoundation\Request &$request)
    {
        $prv_thr = $request->request->get('addThread');
        $thread->setSubject($prv_thr['subject']);
        $thread->setComment($prv_thr['comment']);
    }
}
