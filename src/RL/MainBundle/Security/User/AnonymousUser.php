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

namespace RL\MainBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use RL\MainBundle\Entity\Group;
use RL\MainBundle\Entity\Mark;

/**
 * RL\MainBundle\Security\User\AnonymousUser
 *
 * @author Ax-xa-xa
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class AnonymousUser implements RLUserInterface, EquatableInterface
{
    /**
     * @var array
     */
    protected $attributes;
    /**
     * @var mixed
     */
    protected $identity;
    /**
     * @var \RL\MainBundle\Entity\User
     */
    protected $dbAnon;
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    /**
     * @param $identity
     * @param array $attributes
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     * @param null $logger
     */
    public function __construct(
        $identity,
        array $attributes = array(),
        \Doctrine\Bundle\DoctrineBundle\Registry &$doctrine,
        $logger = null
    ) {
        $this->identity = $identity;
        $this->attributes = $attributes;
        $this->doctrine = & $doctrine;
        $userRepository = $doctrine->getRepository('RLMainBundle:User');
        $this->dbAnon = $userRepository->findOneByUsername('anonymous');
        $this->dbAnon->setLastVisitDate(new \DateTime('now'));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'anon.';
    }

    // RLUserInterface

    /**
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return bool
     */
    public function isAnonymous()
    {
        return true;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return array_key_exists('theme', $this->attributes) ? $this->doctrine->getManager()->getRepository(
            'RLMainBundle:Theme'
        )->findOneByName($this->attributes['theme']) : $this->dbAnon->getTheme();
    }

    /**
     * @param \RL\MainBundle\Entity\Theme $value
     */
    public function setTheme($value)
    {
        $this->attributes['theme'] = $value->getName();
    }

    // AdvancedUserInterface

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }

    // UserInterface

    /**
     * @return mixed
     */
    public function getRoles()
    {
        $userRole = $this->dbAnon->getRoles();

        return $userRole;
    }

    /**
     * @return null
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->dbAnon->getUsername();
    }

    /**
     *
     */
    public function eraseCredentials()
    {

    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if ($user instanceof RLUserInterface) {
            if ($user->isAnonymous()) {
                return ($this->getIdentity() == $user->getIdentity());
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getBlocks()
    {
        return array_key_exists(
            'blocks',
            $this->attributes
        ) ? $this->attributes['blocks'] : $this->dbAnon->getCaptchaLevel();
    }

    /**
     * @param $value
     */
    public function setBlocks($value)
    {
        $attributes = $this->getAttributes();
        $attributes['blocks'] = $value;
    }

    /**
     * @param \RL\MainBundle\Entity\Group $group
     */
    public function setGroup(Group $group)
    {
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function getCaptchaLevel()
    {
        return array_key_exists(
            'captchaLevel',
            $this->attributes
        ) ? $this->attributes['captchaLevel'] : $this->dbAnon->getCaptchaLevel();
    }

    /**
     * @return mixed
     */
    public function getCommentsOnPage()
    {
        return array_key_exists(
            'commentsOnPage',
            $this->attributes
        ) ? $this->attributes['commentsOnPage'] : $this->dbAnon->getCommentsOnPage();
    }

    /**
     * @return mixed
     */
    public function getFilters()
    {
        return array_key_exists(
            'filters',
            $this->attributes
        ) ? $this->attributes['filters'] : $this->dbAnon->getFilters();
    }

    /**
     * @return mixed
     */
    public function getGmt()
    {
        return array_key_exists('gmt', $this->attributes) ? $this->attributes['gmt'] : $this->dbAnon->getGmt();
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->getRoles();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->dbAnon->getId();
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return array_key_exists(
            'language',
            $this->attributes
        ) ? $this->attributes['language'] : $this->dbAnon->getLanguage();
    }

    /**
     * @return mixed
     */
    public function getLastVisitDate()
    {
        return $this->dbAnon->getLastVisitDate();
    }

    /**
     * @return mixed
     */
    public function getMark()
    {
        return array_key_exists('mark', $this->attributes) ? $this->doctrine->getManager()->getRepository(
            'RLMainBundle:Mark'
        )->findOneByName($this->attributes['mark']) : $this->dbAnon->getMark();
    }

    /**
     * @return mixed
     */
    public function getNewsOnPage()
    {
        return array_key_exists(
            'newsOnPage',
            $this->attributes
        ) ? $this->attributes['newsOnPage'] : $this->dbAnon->getNewsOnPage();
    }

    /**
     * @return mixed
     */
    public function getRegistrationDate()
    {
        return $this->dbAnon->getRegistrationDate();
    }

    /**
     * @return mixed
     */
    public function getShowAvatars()
    {
        return array_key_exists(
            'showAvatars',
            $this->attributes
        ) ? $this->attributes['showAvatars'] : $this->dbAnon->getShowAvatars();
    }

    /**
     * @return mixed
     */
    public function getShowResp()
    {
        return array_key_exists(
            'showResp',
            $this->attributes
        ) ? $this->attributes['showResp'] : $this->dbAnon->getShowResp();
    }

    /**
     * @return mixed
     */
    public function getShowUa()
    {
        return array_key_exists('showUa', $this->attributes) ? $this->attributes['showUa'] : $this->dbAnon->getShowUa();
    }

    /**
     * @return mixed
     */
    public function getSortingType()
    {
        return array_key_exists(
            'sortingType',
            $this->attributes
        ) ? $this->attributes['sortingType'] : $this->dbAnon->getSortingType();
    }

    /**
     * @return mixed
     */
    public function getThreadsOnPage()
    {
        return array_key_exists(
            'threadsOnPage',
            $this->attributes
        ) ? $this->attributes['threadsOnPage'] : $this->dbAnon->getThreadsOnPage();
    }

    /**
     * @param $captchaLevel
     */
    public function setCaptchaLevel($captchaLevel)
    {
        $this->attributes['captchaLevel'] = $captchaLevel;
    }

    /**
     * @param $commentsOnPage
     */
    public function setCommentsOnPage($commentsOnPage)
    {
        $this->attributes['commentsOnPage'] = $commentsOnPage;
    }

    /**
     * @param $gmt
     */
    public function setGmt($gmt)
    {
        $this->attributes['gmt'] = $gmt;
    }

    /**
     * @param $language
     */
    public function setLanguage($language)
    {
        $this->attributes['language'] = $language;
    }

    /**
     * @param $lastVisitDate
     */
    public function setLastVisitDate($lastVisitDate)
    {
        $this->attributes['lastVisitDate'] = $lastVisitDate;
    }

    /**
     * @param \RL\MainBundle\Entity\Mark $mark
     */
    public function setMark(\RL\MainBundle\Entity\Mark $mark)
    {
        $this->attributes['mark'] = $mark->getName();
    }

    /**
     * @param $newsOnPage
     */
    public function setNewsOnPage($newsOnPage)
    {
        $this->attributes['newsOnPage'] = $newsOnPage;
    }

    /**
     * @param $registrationDate
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->attributes['registrationDate'] = $registrationDate;
    }

    /**
     * @param $showAvatars
     */
    public function setShowAvatars($showAvatars)
    {
        $this->attributes['showAvatars'] = $showAvatars;
    }

    /**
     * @param $showResp
     */
    public function setShowResp($showResp)
    {
        $this->attributes['showResp'] = $showResp;
    }

    /**
     * @param $showUa
     */
    public function setShowUa($showUa)
    {
        $this->attributes['showUa'] = $showUa;
    }

    /**
     * @param $sortingType
     */
    public function setSortingType($sortingType)
    {
        $this->attributes['sortingType'] = $sortingType;
    }

    /**
     * @param $threadsOnPage
     */
    public function setThreadsOnPage($threadsOnPage)
    {
        $this->attributes['threadsOnPage'] = $threadsOnPage;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name)
    {
        return $this->dbAnon->setName($name);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->dbAnon->getName();
    }

    /**
     * @param $lastname
     * @return mixed
     */
    public function setLastname($lastname)
    {
        return $this->dbAnon->setLastname($lastname);
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->dbAnon->getLastname();
    }

    /**
     * @param $country
     * @return mixed
     */
    public function setCountry($country)
    {
        return $this->dbAnon->setCountry($country);
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->dbAnon->getCountry();
    }

    /**
     * @param $city
     * @return mixed
     */
    public function setCity($city)
    {
        return $this->dbAnon->setCity($city);
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->dbAnon->getCity();
    }

    /**
     * @param $photo
     * @return mixed
     */
    public function setPhoto($photo)
    {
        return $this->dbAnon->setPhoto($photo);
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->dbAnon->getPhoto();
    }

    /**
     * @param $birthday
     * @return mixed
     */
    public function setBirthday($birthday)
    {
        return $this->dbAnon->setBirthday($birthday);
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->dbAnon->getBirthday();
    }

    /**
     * @param $gender
     * @return mixed
     */
    public function setGender($gender)
    {
        return $this->dbAnon->setGender($gender);
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->dbAnon->getGender();
    }

    /**
     * @param $additional
     * @return mixed
     */
    public function setAdditional($additional)
    {
        return $this->dbAnon->setAdditional($additional);
    }

    /**
     * @return mixed
     */
    public function getAdditional()
    {
        return $this->dbAnon->getAdditional();
    }

    /**
     * @param $additionalRaw
     * @return mixed
     */
    public function setAdditionalRaw($additionalRaw)
    {
        return $this->dbAnon->setAdditionalRaw($additionalRaw);
    }

    /**
     * @return mixed
     */
    public function getAdditionalRaw()
    {
        return $this->dbAnon->getAdditionalRaw();
    }

    /**
     * @param $email
     * @return mixed
     */
    public function setEmail($email)
    {
        return $this->dbAnon->setEmail($email);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->dbAnon->getEmail();
    }

    /**
     * @param $im
     * @return mixed
     */
    public function setIm($im)
    {
        return $this->dbAnon->setIm($im);
    }

    /**
     * @return mixed
     */
    public function getIm()
    {
        return $this->dbAnon->getIm();
    }

    /**
     * @param $openid
     * @return mixed
     */
    public function setOpenid($openid)
    {
        return $this->dbAnon->setOpenid($openid);
    }

    /**
     * @return mixed
     */
    public function getOpenid()
    {
        return $this->dbAnon->getOpenid();
    }

    /**
     * @param $showEmail
     * @return mixed
     */
    public function setShowEmail($showEmail)
    {
        return $this->dbAnon->setShowEmail($showEmail);
    }

    /**
     * @return mixed
     */
    public function getShowEmail()
    {
        return $this->dbAnon->getShowEmail();
    }

    /**
     * @param $showIm
     * @return mixed
     */
    public function setShowIm($showIm)
    {
        return $this->dbAnon->setShowIm($showIm);
    }

    /**
     * @return mixed
     */
    public function getShowIm()
    {
        return $this->dbAnon->getShowIm();
    }

    /**
     * @param $active
     * @return mixed
     */
    public function setActive($active)
    {
        return $this->dbAnon->setActive($active);
    }

    /**
     * @param $question
     * @return mixed
     */
    public function setQuestion($question)
    {
        return $this->dbAnon->setQuestion($question);
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->dbAnon->getQuestion();
    }

    /**
     * @param $answer
     * @return mixed
     */
    public function setAnswer($answer)
    {
        return $this->dbAnon->setAnswer($answer);
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->dbAnon->getAnswer();
    }

    /**
     * @return \RL\MainBundle\Entity\User
     */
    public function getDbAnonymous()
    {
        return $this->dbAnon;
    }

    /**
     * @param $filter
     */
    public function addFilter($filter)
    {
        $this->attributes['filters'][] = $filter;
    }

    /**
     * @param $filter
     */
    public function removeFilter($filter)
    {
        $key = array_search($filter,$this->attributes['filters']);
        if($key!==false){
            unset($this->attributes['filters'][$key]);
        }
    }


    /**
     * @return mixed
     */
    public function getEditedComments()
    {
        return $this->attributes['editedComments'];
    }

    /**
     * @param $editedComment
     */
    public function addEditedComment($editedComment)
    {
        $this->attributes['editedComments'][] = $editedComment;
    }

    /**
     * @param $editedComment
     */
    public function removeEditedComment($editedComment)
    {
        $key = array_search($editedComment,$this->attributes['editedComments']);
        if($key!==false){
            unset($this->attributes['editedComments'][$key]);
        }
    }
}
