<?php
/**
 * @author Tux-oid
 */
namespace RL\MainBundle\Form;

class AdministratorSettingsForm
{
    /**
     * @var
     */
    protected $group;

    /**
     * @param $groups
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }
}
