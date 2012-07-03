<?php
/**
 * @author Tux-oid
 */
namespace RL\SecurityBundle\Form;

use Symfony\Component\Validator\Constraint as Assert;

/**

 */class PersonalInformationForm
{
	/**
	 * @var
	 */
	protected $name;
	/**
	 * @var
	 */
	protected $openid;
	/**
	 * @var
	 * @Assert\Image
	 */
	protected $photo;
	/**
	 * @var
	 */
	protected $lastname;
	/**
	 * @var
	 * @Assert\NotBlank()
	 * @Assert\Boolean
	 */
	protected $gender;
	/**
	 * @var
	 * @Assert\DateTime
	 */
	protected $birthday;
	/**
	 * @var
	 * @Assert\Email
	 */
	protected $email;
	/**
	 * @var
	 * @Assert\Email
	 */
	protected $im;
	/**
	 * @var
	 */
	protected $showEmail;
	/**
	 * @var
	 */
	protected $showIm;
	/**
	 * @var
	 */
	protected $country;
	/**
	 * @var
	 */
	protected $city;
	/**
	 * @var
	 */
	protected $additional;

	/**
	 * @param $additional
	 */
	public function setAdditional($additional)
	{
		$this->additional = $additional;
	}

	/**
	 * @return mixed
	 */
	public function getAdditional()
	{
		return $this->additional;
	}

	/**
	 * @param $birthday
	 */
	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;
	}

	/**
	 * @return mixed
	 */
	public function getBirthday()
	{
		return $this->birthday;
	}

	/**
	 * @param $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
	}

	/**
	 * @return mixed
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @param $country
	 */
	public function setCountry($country)
	{
		$this->country = $country;
	}

	/**
	 * @return mixed
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * @param $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param $gender
	 */
	public function setGender($gender)
	{
		$this->gender = $gender;
	}

	/**
	 * @return mixed
	 */
	public function getGender()
	{
		return $this->gender;
	}

	/**
	 * @param $im
	 */
	public function setIm($im)
	{
		$this->im = $im;
	}

	/**
	 * @return mixed
	 */
	public function getIm()
	{
		return $this->im;
	}

	/**
	 * @param $lastname
	 */
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;
	}

	/**
	 * @return mixed
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * @param $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param $openid
	 */
	public function setOpenid($openid)
	{
		$this->openid = $openid;
	}

	/**
	 * @return mixed
	 */
	public function getOpenid()
	{
		return $this->openid;
	}

	/**
	 * @param $showEmail
	 */
	public function setShowEmail($showEmail)
	{
		$this->showEmail = $showEmail;
	}

	/**
	 * @return mixed
	 */
	public function getShowEmail()
	{
		return $this->showEmail;
	}

	/**
	 * @param $showIm
	 */
	public function setShowIm($showIm)
	{
		$this->showIm = $showIm;
	}

	/**
	 * @return mixed
	 */
	public function getShowIm()
	{
		return $this->showIm;
	}

	/**
	 * @param  $photo
	 */
	public function setPhoto($photo)
	{
		$this->photo = $photo;
	}

	/**
	 * @return
	 */
	public function getPhoto()
	{
		return $this->photo;
	}
}
