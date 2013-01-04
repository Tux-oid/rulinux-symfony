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
 * RL\MainBundle\Form\PersonalInformationForm
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class PersonalInformationForm
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
     * @Assert\NotBlank()
     * @Assert\Country
     */
    protected $country;
    /**
     * @var
     */
    protected $city;
    /**
     * @var
     */
    protected $additionalRaw;

    /**
     * @param $additional
     */
    public function setAdditionalRaw($additionalRaw)
    {
        $this->additionalRaw = $additionalRaw;
    }

    /**
     * @return mixed
     */
    public function getAdditionalRaw()
    {
        return $this->additionalRaw;
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
