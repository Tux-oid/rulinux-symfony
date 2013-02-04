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

namespace RL\GalleryBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RL\MainBundle\Entity\Section;
use RL\GalleryBundle\Entity\Subsection;

/**
 * RL\GalleryBundle\DataFixtures\ORM\FixtureLoader
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class FixtureLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $gallerySection = new Section();
        $gallerySection->setName('Gallery');
        $gallerySection->setDescription('Images related to FOSS');
        $gallerySection->setRewrite('gallery');
        $gallerySection->setBundle('RLGalleryBundle');
        $gallerySection->setBundleNamespace('RL\GalleryBundle');
        $manager->persist($gallerySection);

        $screenshotsSubsection = new Subsection();
        $screenshotsSubsection->setName('Screenshots');
        $screenshotsSubsection->setDescription('Screenshots');
        $screenshotsSubsection->setRewrite('shceenshots');
        $screenshotsSubsection->setSection($gallerySection);
        $screenshotsSubsection->setShortfaq('');
        $manager->persist($screenshotsSubsection);

        $devicesSubsection = new Subsection();
        $devicesSubsection->setName('Devices');
        $devicesSubsection->setDescription('Photos of user\'s devices');
        $devicesSubsection->setRewrite('devices');
        $devicesSubsection->setSection($gallerySection);
        $devicesSubsection->setShortfaq('');
        $manager->persist($devicesSubsection);

        $workplacesSubsection = new Subsection();
        $workplacesSubsection->setName('Workplaces');
        $workplacesSubsection->setDescription('Photos of user\'s workplaces');
        $workplacesSubsection->setRewrite('workplaces');
        $workplacesSubsection->setSection($gallerySection);
        $workplacesSubsection->setShortfaq('');
        $manager->persist($workplacesSubsection);

        $otherSubsection = new Subsection();
        $otherSubsection->setName('Other');
        $otherSubsection->setDescription('Other images');
        $otherSubsection->setRewrite('other');
        $otherSubsection->setSection($gallerySection);
        $otherSubsection->setShortfaq('');
        $manager->persist($otherSubsection);

        $manager->flush();
    }
}
