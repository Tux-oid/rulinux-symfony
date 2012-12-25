<?php
/**
 * @author Tux-oid
 */
namespace RL\SecurityBundle\Form;
use Symfony\Component\Validator\Constraints as Assert;

class ModeratorSettingsForm
{
    /**
     * @var
     */
    private $active;
    /**
     * @var
     * @Assert\Regex("#([0-9]+)#")
     */
    private $captchaLevel;

    /**
     * @param $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param $captchaLevel
     */
    public function setCaptchaLevel($captchaLevel)
    {
        $this->captchaLevel = $captchaLevel;
    }

    /**
     * @return mixed
     */
    public function getCaptchaLevel()
    {
        return $this->captchaLevel;
    }
}
