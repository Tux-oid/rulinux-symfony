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

namespace RL\MainBundle\Theme;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use RL\MainBundle\Security\User\RLUserInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use AppKernel;

/**
 * RL\MainBundle\Theme\ThemeProvider
 *
 * @author Ax-xa-xa
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ThemeProvider implements ThemeProviderInterface
{
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $context;
    /**
     * @var \AppKernel
     */
    private $httpKernel;
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    public function __construct(SecurityContextInterface $context, AppKernel $kernel, Registry $doctrine)
    {
        $this->context = $context;
        $this->doctrine = $doctrine;
        $this->httpKernel = $kernel;
    }

    public function getTemplate($bundleName, $templateName)
    {
        /** @var $theme \RL\MainBundle\Entity\Theme */
        $theme = $this->getTheme();
        /** @var $defaultTheme \RL\MainBundle\Entity\Theme */
        $defaultTheme = $this->doctrine->getRepository('RLMainBundle:Theme')->findOneByName('Ubertechno');
        $tpl = '@RLMainBundle/Resources/views/' . $theme->getDirectory() . '/' . $bundleName . '_' . $templateName;
        try {
            $this->httpKernel->locateResource($tpl);
        } catch(\Exception $e) {
            $tpl = '@RLMainBundle/Resources/views/' . $defaultTheme->getDirectory(
            ) . '/' . $bundleName . '_' . $templateName;
            try {
                $this->httpKernel->locateResource($tpl);
            } catch(\Exception $e) {
                $tpl = '@' . $bundleName . '/Resources/views/Default/' . $bundleName . '_' . $templateName;
                try {
                    $this->httpKernel->locateResource($tpl);
                } catch(\Exception $e) {
                    throw new \Exception('Template not found');
                }

                return $bundleName . ':Default';
            }

            return $defaultTheme->getPath();
        }

        return $theme->getPath();
    }

    public function getTheme()
    {
        $theme = $this->doctrine->getRepository('RLMainBundle:Theme')->findOneByName('Ubertechno');
        if (isset($theme)) {
            $token = $this->context->getToken();
            if (isset($token)) {
                $user = $token->getUser();
                if ($user instanceof RLUserInterface) {
                    $userTheme = $user->getTheme();
                    if (isset($userTheme)) {
                        $theme = $userTheme;
                    }
                }
            }
        }

        return $theme;
    }

    public function getPath($bundleName, $templateName)
    {
        $theme = $this->getTemplate($bundleName, $templateName);

        return $theme . ":" . $bundleName . '_' . $templateName;
    }
}
