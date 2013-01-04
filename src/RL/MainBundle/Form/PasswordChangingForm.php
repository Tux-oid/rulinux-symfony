<?php
/**
 * @author Tux-oid
 */
namespace RL\MainBundle\Form;

class PasswordChangingForm
{
    /**
     * @var
     */
    protected $oldPassword;
    /**
     * @var
     */
    protected $newPassword;
    /**
     * @var
     */
    protected $validation;

    /**
     * @param $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param $validation
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;
    }

    /**
     * @return mixed
     */
    public function getValidation()
    {
        return $this->validation;
    }
}
