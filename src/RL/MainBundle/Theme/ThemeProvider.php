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
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * RL\MainBundle\Theme\ThemeProvider
 *
 * @Service("rl_main.theme.provider")
 *
 * @author Ax-xa-xa
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class ThemeProvider
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

    /**
     * Constructor
     *
     * @InjectParams({
     * "context" = @Inject("security.context"),
     * "kernel" = @Inject("kernel"),
     * "doctrine" = @Inject("doctrine")
     * })
     *
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $context
     * @param \AppKernel $kernel
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function __construct(SecurityContextInterface $context, AppKernel $kernel, Registry $doctrine)
    {
        $this->context = $context;
        $this->doctrine = $doctrine;
        $this->httpKernel = $kernel;
    }

    /**
     * Get theme
     *
     * @return mixed
     */
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

    /**
     * Get path to template
     *
     * @param string $bundleName
     * @param string $templateName
     * @return string
     * @throws \Exception
     */
    public function getPath($name)
    {
        preg_match("#(.*?:)(.*?:)(.*)#suim", $name, $arr);
        $bundleName = substr($arr[1], 0, strlen($arr[1]) - 1);
        $controllerName = substr($arr[2], 0, strlen($arr[2]) - 1);
        $templateName = $arr[3];
        /** @var $theme \RL\MainBundle\Entity\Theme */
        $theme = $this->getTheme();
        /** @var $defaultTheme \RL\MainBundle\Entity\Theme */
        $defaultTheme = $this->doctrine->getRepository('RLMainBundle:Theme')->findOneByName('Ubertechno');
        $tpl = '@RLMainBundle/Resources/views/' . $theme->getDirectory() . '/' . $bundleName . '/' . $templateName;
        try {
            $this->httpKernel->locateResource($tpl);
        } catch(\Exception $e) {
            $tpl = '@RLMainBundle/Resources/views/' . $defaultTheme->getDirectory() . '/' . $bundleName . '/' . $templateName;
            try {
                $this->httpKernel->locateResource($tpl);
            } catch(\Exception $e) {
                $tpl = '@' . $bundleName . '/Resources/views/' . $theme->getDirectory() . '/' . $templateName;
                try {
                    $this->httpKernel->locateResource($tpl);
                } catch(\Exception $e) {
                    $tpl = '@' . $bundleName . '/Resources/views/' . $defaultTheme->getDirectory() . '/' . $templateName;
                    try {
                        $this->httpKernel->locateResource($tpl);
                    } catch(\Exception $e) {
                        throw new \Exception('Template not found');
                    }

                    return $bundleName . ':' . $defaultTheme->getDirectory() . ':' . $templateName;
                }

                return $bundleName . ':' . $theme->getDirectory() . ':' . $templateName;
            }

            return 'RLMainBundle:' . $defaultTheme->getDirectory() . ':' . $bundleName . '/' . $templateName;
        }

        return 'RLMainBundle:' . $theme->getDirectory() . ':' . $bundleName . '/' . $templateName;
    }
}
