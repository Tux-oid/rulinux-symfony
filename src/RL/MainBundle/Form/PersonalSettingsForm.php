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

namespace RL\MainBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * RL\MainBundle\Form\PersonalSettingsForm
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class PersonalSettingsForm
{
    /**
     * @var
     * @Assert\NotBlank()
     */
    protected $theme;
    /**
     * @var
     * @Assert\NotBlank()
     */
    protected $mark;
    /**
     * @var
     * @Assert\NotBlank()
     */
    protected $gmt;
    /**
     * @var
     * @Assert\NotBlank()
     */
    protected $newsOnPage;
    /**
     * @var
     * @Assert\NotBlank()
     */
    protected $commentsOnPage;
    /**
     * @var
     * @Assert\NotBlank()
     */
    protected $threadsOnPage;
    /**
     * @var
     */
    protected $showAvatars;
    /**
     * @var
     */
    protected $showUa;
    /**
     * @var
     */
    protected $sortingType;
    /**
     * @var
     */
    protected $showResp;
    /**
     * @var
     * @Assert\NotBlank()
     * @Assert\Language
     */
    protected $language;
    /**
     * @param $commentsOnPage
     */
    public function setCommentsOnPage($commentsOnPage)
    {
        $this->commentsOnPage = $commentsOnPage;
    }

    /**
     * @return mixed
     */
    public function getCommentsOnPage()
    {
        return $this->commentsOnPage;
    }

    /**
     * @param $gmt
     */
    public function setGmt($gmt)
    {
        $this->gmt = $gmt;
    }

    /**
     * @return mixed
     */
    public function getGmt()
    {
        return $this->gmt;
    }

    /**
     * @param $mark
     */
    public function setMark($mark)
    {
        $this->mark = $mark;
    }

    /**
     * @return mixed
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * @param $newsOnPage
     */
    public function setNewsOnPage($newsOnPage)
    {
        $this->newsOnPage = $newsOnPage;
    }

    /**
     * @return mixed
     */
    public function getNewsOnPage()
    {
        return $this->newsOnPage;
    }

    /**
     * @param $showAvatars
     */
    public function setShowAvatars($showAvatars)
    {
        $this->showAvatars = $showAvatars;
    }

    /**
     * @return mixed
     */
    public function getShowAvatars()
    {
        return $this->showAvatars;
    }

    /**
     * @param $showResp
     */
    public function setShowResp($showResp)
    {
        $this->showResp = $showResp;
    }

    /**
     * @return mixed
     */
    public function getShowResp()
    {
        return $this->showResp;
    }

    /**
     * @param $showUa
     */
    public function setShowUa($showUa)
    {
        $this->showUa = $showUa;
    }

    /**
     * @return mixed
     */
    public function getShowUa()
    {
        return $this->showUa;
    }

    /**
     * @param $sortingType
     */
    public function setSortingType($sortingType)
    {
        $this->sortingType = $sortingType;
    }

    /**
     * @return mixed
     */
    public function getSortingType()
    {
        return $this->sortingType;
    }

    /**
     * @param $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param $threadsOnPage
     */
    public function setThreadsOnPage($threadsOnPage)
    {
        $this->threadsOnPage = $threadsOnPage;
    }

    /**
     * @return mixed
     */
    public function getThreadsOnPage()
    {
        return $this->threadsOnPage;
    }

    /**
     * @param $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
