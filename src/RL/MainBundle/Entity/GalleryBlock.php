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

namespace RL\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RL\MainBundle\Entity\GalleryBlock
 *
 * @ORM\Entity
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class GalleryBlock extends Block
{

    /**
     * Get list of services which needed to block
     *
     * @return array
     */
    public function getNeededServicesList()
    {
        return array('doctrine');
    }

    /**
     * Render block
     * Returns array('templateFile'=> 'fileName.html.twig', 'parameters'=>array())
     *
     * @param array $services
     * @return array
     */
    public function render(array $services)
    {
        /** @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = $services['doctrine'];
        /** @var $threadsRepository \RL\GalleryBundle\Entity\Repository\ThreadRepository */
        $threadsRepository = $doctrine->getRepository('RLGalleryBundle:Thread');
        $threads = $threadsRepository->getLastThreads(3);
        return array('templateFile'=> 'galleryBlock.html.twig', 'parameters'=>array('threads'=>$threads));
    }
}
