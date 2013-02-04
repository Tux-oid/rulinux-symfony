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

use RL\MainBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * RL\MainBundle\Controller\ModerController
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ModerController extends AbstractController
{
    /**
     * @Route("/approve_thread_{id}", name="approve_thread", requirements = {"id"="[0-9]+"})
     */
    public function approveThreadAction($id)
    {
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        if (!$securityContext->isGranted('ROLE_MODER')) {
            return $this->renderMessage('Access denied', 'You haven\'t privileges to edit this message');
        }
        $doctrine = $this->get('doctrine');
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getEntityManager();
        /** @var $thread \RL\MainBundle\Entity\Thread */
        $thread = $doctrine->getRepository('RLMainBundle:Thread')->findOneById($id);
        if (null === $thread) {
            return $this->renderMessage('Thread not found', 'Thread with specified id was not found');
        }
        $thread->setApproved(true);
        $thread->setApprovedBy($user);
        $thread->setApproveTimest(new \DateTime());
        $em->flush();

        return $this->redirect($this->generateUrl("unconfirmed", array()));
    }

    /**
     * @Route("/attach_thread_{id}_{state}", name="attach_thread", requirements = {"id"="[0-9]+", "state"="true|false"})
     */
    public function attachThreadAction($id, $state)
    {
        $securityContext = $this->get('security.context');
        if (!$securityContext->isGranted('ROLE_MODER')) {
            return $this->renderMessage('Access denied', 'You haven\'t privileges to edit this message');
        }
        $doctrine = $this->get('doctrine');
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getEntityManager();
        /** @var $thread \RL\MainBundle\Entity\Thread */
        $thread = $doctrine->getRepository('RLMainBundle:Thread')->findOneById($id);
        if (null === $thread) {
            return $this->renderMessage('Thread not found', 'Thread with specified id was not found');
        }
        if ($state == "true") {
            $thread->setAttached(true);
        } else {
            $thread->setAttached(false);
        }
        $em->flush();

        return $this->redirect($this->generateUrl("subsection", array('sectionRewrite'=>$thread->getSubsection()->getSection()->getRewrite(), 'subsectionRewrite'=>$thread->getSubsection()->getRewrite())));
    }

}
