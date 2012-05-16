<?php

namespace RL\SecurityBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFirstForm
{
	/**
	 * @Assert\NotBlank()
	 * @Assert\Regex("#([a-zA-Z0-9\_\-\/\.]{2,})$#")
	 */
	protected $name;
	/**
	 * @Assert\NotBlank()
	 */
	protected $password;
	/**
	 * @Assert\NotBlank()
	 */
	protected $validation;
	/**
	 * @Assert\NotBlank()
	 * @Assert\Email
	 */
	protected $email;

	public function getName()
	{
		return $this->name;
	}
	public function setName($name)
	{
		$this->name = $name;
	}
	public function getPassword()
	{
		return $this->password;
	}
	public function setPassword($pass)
	{
		$this->password = $pass;
	}
	public function getValidation()
	{
		return $this->validation;
	}
	public function setValidation($validation)
	{
		$this->validation = $validation;
	}
	public function getEmail()
	{
		return $this->email;
	}
	public function setEmail($email)
	{
		$this->email=$email;
	}
}

?>