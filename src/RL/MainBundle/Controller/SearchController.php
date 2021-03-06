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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use RL\MainBundle\Form\Type\SearchType;
use RL\MainBundle\Form\Model\SearchForm;

/**
 * RL\MainBundle\Controller\ModerController
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function searchAction()
    {
        return $this->render('RLMainBundle:Search:search.html.twig', array('form' => $this->getSearchForm()->createView()));
    }

    /**
     * @Route("/found", name="found")
     * @Method("POST")
     */
    public function foundAction()
    {
        $form = $this->getSearchForm();
        $form->submit($this->getRequest());
        if ($form->isValid()) {
            /** @var $messageRepository \RL\MainBundle\Entity\Repository\MessageRepository */
            $messageRepository = $this->getDoctrine()->getRepository('RLMainBundle:Message');
            $messages = $messageRepository->search($form->getData()->toArray());

            return $this->render(
                'RLMainBundle:Search:search.html.twig',
                array('form' => $form->createView(), 'messages' => $messages)
            );
        }
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    public function getSearchForm()
    {
        return $this->createForm(new SearchType(), new SearchForm());
    }
}
