<?php
/**
 * Copyright (c) 2008 - 2013, Peter Vasilevsky
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

namespace RL\MainBundle\Service;

use RL\Ucaptcha\UcaptchaInterface;
use RL\Ucaptcha\Ucaptcha;
use JMS\DiExtraBundle\Annotation\Service;

//require __DIR__ . '/../../../../vendor/rl/ucaptcha/RL/Ucaptcha/Ucaptcha.php';
//require __DIR__ . '/../../../../vendor/rl/ucaptcha/RL/Ucaptcha/UcaptchaInterface.php';

/**
 * RL\MainBundle\Service\UcaptchaService
 *
 * @Service("rl_main.ucaptcha")
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class UcaptchaDecoratorService implements UcaptchaInterface
{

    /**
     * @var Ucaptcha
     */
    protected $ucaptcha;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ucaptcha = new Ucaptcha();
    }

    /**
     * @param int $level
     * @return null|string
     * @throws \Exception
     */
    public function draw($level = 0)
    {
        return $this->ucaptcha->draw($level);
    }

    /**
     * @param $val
     * @return bool
     */
    public function check($val)
    {
        return $this->ucaptcha->check($val);
    }

    /**
     *
     */
    public function reset()
    {
        $this->ucaptcha->reset();
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->ucaptcha->getFilename();
    }

    /**
     * @return int
     */
    public function getPluginsLevelsCount()
    {
        return $this->ucaptcha->getPluginsLevelsCount();
    }

    /**
     * @return array
     */
    public function getPluginsLevels()
    {
        return $this->ucaptcha->getPluginsLevels();
    }

    /**
     * @param $captchaFontPath
     */
    public function setCaptchaFontPath($captchaFontPath)
    {
        $this->ucaptcha->setCaptchaFontPath($captchaFontPath);
    }

    /**
     * @return string
     */
    public function getCaptchaFontPath()
    {
        return $this->ucaptcha->getCaptchaFontPath();
    }

    /**
     * @return string
     */
    public function getAbsoluteCaptchaFontPath()
    {
        return $this->ucaptcha->getAbsoluteCaptchaFontPath();
    }

    /**
     * @param $captchaImgPath
     */
    public function setCaptchaImgPath($captchaImgPath)
    {
        $this->ucaptcha->setCaptchaImgPath($captchaImgPath);
    }

    /**
     * @return string
     */
    public function getCaptchaImgPath()
    {
        return $this->getCaptchaImgPath();
    }

    /**
     * @return string
     */
    public function getAbsoluteCaptchaImgPath()
    {
        return $this->ucaptcha->getAbsoluteCaptchaImgPath();
    }

    /**
     * @param $captchaPluginPath
     */
    public function setCaptchaPluginPath($captchaPluginPath)
    {
        $this->ucaptcha->setCaptchaPluginPath($captchaPluginPath);
    }

    /**
     * @return string
     */
    public function getCaptchaPluginPath()
    {
        return $this->getCaptchaPluginPath();
    }

    /**
     * @return string
     */
    public function getAbsoluteCaptchaPluginPath()
    {
        return $this->getAbsoluteCaptchaPluginPath();
    }

    /**
     * @param $captchaTplPath
     */
    public function setCaptchaTplPath($captchaTplPath)
    {
        $this->setCaptchaTplPath($captchaTplPath);
    }

    /**
     * @return string
     */
    public function getCaptchaTplPath()
    {
        return $this->ucaptcha->getCaptchaTplPath();
    }

    /**
     * @return string
     */
    public function getAbsoluteCaptchaTplPath()
    {
        return $this->ucaptcha->getAbsoluteCaptchaTplPath();
    }
}
