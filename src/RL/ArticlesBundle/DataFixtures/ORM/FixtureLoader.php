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

namespace RL\ArticlesBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RL\MainBundle\Entity\Section;
use RL\ForumBundle\Entity\Subsection;

/**
 * RL\ArticlesBundle\DataFixtures\ORM\FixtureLoader
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class FixtureLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $articlesSection = new Section();
        $articlesSection->setName('Articles');
        $articlesSection->setDescription('Articles about FOSS');
        $articlesSection->setRewrite('articles');
        $articlesSection->setBundle('RLArticlesBundle');
        $articlesSection->setBundleNamespace('RL\ArticlesBundle');
        $manager->persist($articlesSection);

        $generalSubsection = new Subsection();
        $generalSubsection->setName('General');
        $generalSubsection->setDescription('general subsection for articles not falling into other groups');
        $generalSubsection->setShortfaq('');
        $generalSubsection->setRewrite('general');
        $generalSubsection->setSection($articlesSection);
        $manager->persist($generalSubsection);

        $desktopSubsection = new Subsection();
        $desktopSubsection->setName('Desktop');
        $desktopSubsection->setDescription('articles about using Linux/Unix on desktop');
        $desktopSubsection->setShortfaq('');
        $desktopSubsection->setRewrite('desktop');
        $desktopSubsection->setSection($articlesSection);
        $manager->persist($desktopSubsection);

        $adminSubsection = new Subsection();
        $adminSubsection->setName('Admin');
        $adminSubsection->setDescription('administration of Linux/Unix systems and networks');
        $adminSubsection->setShortfaq('');
        $adminSubsection->setRewrite('admin');
        $adminSubsection->setSection($articlesSection);
        $manager->persist($adminSubsection);

        $linuxInstallSubsection = new Subsection();
        $linuxInstallSubsection->setName('Linux-install');
        $linuxInstallSubsection->setDescription('Linux installation');
        $linuxInstallSubsection->setShortfaq('');
        $linuxInstallSubsection->setRewrite('linux_install');
        $linuxInstallSubsection->setSection($articlesSection);
        $manager->persist($linuxInstallSubsection);

        $bsdSubsection = new Subsection();
        $bsdSubsection->setName('BSD');
        $bsdSubsection->setDescription('forum about BSD systems');
        $bsdSubsection->setShortfaq('');
        $bsdSubsection->setRewrite('bsd');
        $bsdSubsection->setSection($articlesSection);
        $manager->persist($bsdSubsection);

        $developmentSubsection = new Subsection();
        $developmentSubsection->setName('Development');
        $developmentSubsection->setDescription('programming and software development for Linux/Unix');
        $developmentSubsection->setShortfaq('');
        $developmentSubsection->setRewrite('development');
        $developmentSubsection->setSection($articlesSection);
        $manager->persist($developmentSubsection);

        $securitySubsection = new Subsection();
        $securitySubsection->setName('Security');
        $securitySubsection->setDescription('security');
        $securitySubsection->setShortfaq('');
        $securitySubsection->setRewrite('security');
        $securitySubsection->setSection($articlesSection);
        $manager->persist($securitySubsection);

        $linuxHardwareSubsection = new Subsection();
        $linuxHardwareSubsection->setName('Linux-hardware');
        $linuxHardwareSubsection->setDescription('hardware & Linux');
        $linuxHardwareSubsection->setShortfaq('');
        $linuxHardwareSubsection->setRewrite('linux_hardware');
        $linuxHardwareSubsection->setSection($articlesSection);
        $manager->persist($linuxHardwareSubsection);

        $gamesSubsection = new Subsection();
        $gamesSubsection->setName('Games');
        $gamesSubsection->setDescription('games for Linux/Unix');
        $gamesSubsection->setShortfaq('');
        $gamesSubsection->setRewrite('games');
        $gamesSubsection->setSection($articlesSection);
        $manager->persist($gamesSubsection);

        $webDevelopmentSubsection = new Subsection();
        $webDevelopmentSubsection->setName('Web-development');
        $webDevelopmentSubsection->setDescription('web development');
        $webDevelopmentSubsection->setShortfaq('');
        $webDevelopmentSubsection->setRewrite('web_development');
        $webDevelopmentSubsection->setSection($articlesSection);
        $manager->persist($webDevelopmentSubsection);

        $manager->flush();

    }
}
