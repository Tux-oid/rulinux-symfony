<?php
/**
 * @author Tux-oid
 */
namespace RL\MainBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;

/**

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
