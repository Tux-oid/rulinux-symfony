<?php

namespace LorNgDevelopers\RulinuxBundle\Entity;

class RegistrationFirstForm
{
	protected $name;
	protected $password;
	protected $validation;
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