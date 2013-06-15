<?php
/**
 * Copyright (c) 2009 - 2012, Peter Vasilevsky
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
use RL\MainBundle\Entity\BlockPosition;
use RL\MainBundle\Entity\UsersFilter;
use RL\MainBundle\Entity\Reader;
use \RL\MainBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use RL\MainBundle\Entity\Message;

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
     * @var User
     */
    protected $dbAnon;
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @param $identity
     * @param array $attributes
     * @param Registry $doctrine
     * @param null $logger
     */
    public function __construct($identity, array $attributes = array(), Registry &$doctrine, $logger = null)
    {
        $this->identity = $identity;
        $this->attributes = $attributes;
        $this->doctrine = & $doctrine;
        $userRepository = $doctrine->getRepository('RLMainBundle:User');
        $this->dbAnon = $userRepository->findOneBy(array("username" => 'anonymous'));
        $this->dbAnon->setLastVisitDate(new \DateTime('now'));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'anon.';
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * {@inheritdoc}
     */
    public function isAnonymous()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getTheme()
    {
        return array_key_exists('theme', $this->attributes) ? $this->doctrine->getManager()->getRepository(
            'RLMainBundle:Theme'
        )->findOneBy(array("name" => $this->attributes['theme'])) : $this->dbAnon->getTheme();
    }

    /**
     * {@inheritdoc}
     */
    public function setTheme($value)
    {
        $this->attributes['theme'] = $value->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $userRole = $this->dbAnon->getRoles();

        return $userRole;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->dbAnon->getUsername();
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {

    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function setGroup(Group $group)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getCaptchaLevel()
    {
        return array_key_exists(
            'captchaLevel',
            $this->attributes
        ) ? $this->attributes['captchaLevel'] : $this->dbAnon->getCaptchaLevel();
    }

    /**
     * {@inheritdoc}
     */
    public function getCommentsOnPage()
    {
        return array_key_exists(
            'commentsOnPage',
            $this->attributes
        ) ? $this->attributes['commentsOnPage'] : $this->dbAnon->getCommentsOnPage();
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array_key_exists(
            'filters',
            $this->attributes
        ) ? $this->attributes['filters'] : $this->dbAnon->getFilters();
    }

    /**
     * {@inheritdoc}
     */
    public function getGmt()
    {
        return array_key_exists('gmt', $this->attributes) ? $this->attributes['gmt'] : $this->dbAnon->getGmt();
    }

    /**
     * {@inheritdoc}
     */
    public function getGroup()
    {
        return $this->getRoles();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->dbAnon->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguage()
    {
        return array_key_exists(
            'language',
            $this->attributes
        ) ? $this->attributes['language'] : $this->dbAnon->getLanguage();
    }

    /**
     * {@inheritdoc}
     */
    public function getLastVisitDate()
    {
        return $this->dbAnon->getLastVisitDate();
    }

    /**
     * {@inheritdoc}
     */
    public function getMark()
    {
        return array_key_exists('mark', $this->attributes) ? $this->doctrine->getManager()->getRepository(
            'RLMainBundle:Mark'
        )->findOneBy(array("name" => $this->attributes['mark'])) : $this->dbAnon->getMark();
    }

    /**
     * {@inheritdoc}
     */
    public function getNewsOnPage()
    {
        return array_key_exists(
            'newsOnPage',
            $this->attributes
        ) ? $this->attributes['newsOnPage'] : $this->dbAnon->getNewsOnPage();
    }

    /**
     * {@inheritdoc}
     */
    public function getRegistrationDate()
    {
        return $this->dbAnon->getRegistrationDate();
    }

    /**
     * {@inheritdoc}
     */
    public function isShowAvatars()
    {
        return array_key_exists(
            'showAvatars',
            $this->attributes
        ) ? $this->attributes['showAvatars'] : $this->dbAnon->isShowAvatars();
    }

    /**
     * {@inheritdoc}
     */
    public function isShowResp()
    {
        return array_key_exists(
            'showResp',
            $this->attributes
        ) ? $this->attributes['showResp'] : $this->dbAnon->isShowResp();
    }

    /**
     * {@inheritdoc}
     */
    public function isShowUa()
    {
        return array_key_exists('showUa', $this->attributes) ? $this->attributes['showUa'] : $this->dbAnon->isShowUa();
    }

    /**
     * {@inheritdoc}
     */
    public function getSortingType()
    {
        return array_key_exists(
            'sortingType',
            $this->attributes
        ) ? $this->attributes['sortingType'] : $this->dbAnon->getSortingType();
    }

    /**
     * {@inheritdoc}
     */
    public function getThreadsOnPage()
    {
        return array_key_exists(
            'threadsOnPage',
            $this->attributes
        ) ? $this->attributes['threadsOnPage'] : $this->dbAnon->getThreadsOnPage();
    }

    /**
     * {@inheritdoc}
     */
    public function setCaptchaLevel($captchaLevel)
    {
        $this->attributes['captchaLevel'] = $captchaLevel;
    }

    /**
     * {@inheritdoc}
     */
    public function setCommentsOnPage($commentsOnPage)
    {
        $this->attributes['commentsOnPage'] = $commentsOnPage;
    }

    /**
     * {@inheritdoc}
     */
    public function setGmt($gmt)
    {
        $this->attributes['gmt'] = $gmt;
    }

    /**
     * {@inheritdoc}
     */
    public function setLanguage($language)
    {
        $this->attributes['language'] = $language;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastVisitDate($lastVisitDate)
    {
        $this->attributes['lastVisitDate'] = $lastVisitDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setMark(\RL\MainBundle\Entity\Mark $mark)
    {
        $this->attributes['mark'] = $mark->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setNewsOnPage($newsOnPage)
    {
        $this->attributes['newsOnPage'] = $newsOnPage;
    }

    /**
     * {@inheritdoc}
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->attributes['registrationDate'] = $registrationDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setShowAvatars($showAvatars)
    {
        $this->attributes['showAvatars'] = $showAvatars;
    }

    /**
     * {@inheritdoc}
     */
    public function setShowResp($showResp)
    {
        $this->attributes['showResp'] = $showResp;
    }

    /**
     * {@inheritdoc}
     */
    public function setShowUa($showUa)
    {
        $this->attributes['showUa'] = $showUa;
    }

    /**
     * {@inheritdoc}
     */
    public function setSortingType($sortingType)
    {
        $this->attributes['sortingType'] = $sortingType;
    }

    /**
     * {@inheritdoc}
     */
    public function setThreadsOnPage($threadsOnPage)
    {
        $this->attributes['threadsOnPage'] = $threadsOnPage;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        return $this->dbAnon->setName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->dbAnon->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setLastname($lastname)
    {
        return $this->dbAnon->setLastname($lastname);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastname()
    {
        return $this->dbAnon->getLastname();
    }

    /**
     * {@inheritdoc}
     */
    public function setCountry($country)
    {
        return $this->dbAnon->setCountry($country);
    }

    /**
     * {@inheritdoc}
     */
    public function getCountry()
    {
        return $this->dbAnon->getCountry();
    }

    /**
     * {@inheritdoc}
     */
    public function setCity($city)
    {
        return $this->dbAnon->setCity($city);
    }

    /**
     * {@inheritdoc}
     */
    public function getCity()
    {
        return $this->dbAnon->getCity();
    }

    /**
     * {@inheritdoc}
     */
    public function setPhoto($photo)
    {
        return $this->dbAnon->setPhoto($photo);
    }

    /**
     * {@inheritdoc}
     */
    public function getPhoto()
    {
        return $this->dbAnon->getPhoto();
    }

    /**
     * {@inheritdoc}
     */
    public function setBirthday($birthday)
    {
        return $this->dbAnon->setBirthday($birthday);
    }

    /**
     * {@inheritdoc}
     */
    public function getBirthday()
    {
        return $this->dbAnon->getBirthday();
    }

    /**
     * {@inheritdoc}
     */
    public function setGender($gender)
    {
        return $this->dbAnon->setGender($gender);
    }

    /**
     * {@inheritdoc}
     */
    public function getGender()
    {
        return $this->dbAnon->getGender();
    }

    /**
     * {@inheritdoc}
     */
    public function setAdditional($additional)
    {
        return $this->dbAnon->setAdditional($additional);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdditional()
    {
        return $this->dbAnon->getAdditional();
    }

    /**
     * {@inheritdoc}
     */
    public function setAdditionalRaw($additionalRaw)
    {
        return $this->dbAnon->setAdditionalRaw($additionalRaw);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdditionalRaw()
    {
        return $this->dbAnon->getAdditionalRaw();
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        return $this->dbAnon->setEmail($email);
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->dbAnon->getEmail();
    }

    /**
     * {@inheritdoc}
     */
    public function setIm($im)
    {
        return $this->dbAnon->setIm($im);
    }

    /**
     * {@inheritdoc}
     */
    public function getIm()
    {
        return $this->dbAnon->getIm();
    }

    /**
     * {@inheritdoc}
     */
    public function setOpenid($openid)
    {
        return $this->dbAnon->setOpenid($openid);
    }

    /**
     * {@inheritdoc}
     */
    public function getOpenid()
    {
        return $this->dbAnon->getOpenid();
    }

    /**
     * {@inheritdoc}
     */
    public function setShowEmail($showEmail)
    {
        return $this->dbAnon->setShowEmail($showEmail);
    }

    /**
     * {@inheritdoc}
     */
    public function isShowEmail()
    {
        return $this->dbAnon->isShowEmail();
    }

    /**
     * {@inheritdoc}
     */
    public function setShowIm($showIm)
    {
        return $this->dbAnon->setShowIm($showIm);
    }

    /**
     * {@inheritdoc}
     */
    public function isShowIm()
    {
        return $this->dbAnon->isShowIm();
    }

    /**
     * {@inheritdoc}
     */
    public function setActive($active)
    {
        return $this->dbAnon->setActive($active);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuestion($question)
    {
        return $this->dbAnon->setQuestion($question);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestion()
    {
        return $this->dbAnon->getQuestion();
    }

    /**
     * {@inheritdoc}
     */
    public function setAnswer($answer)
    {
        return $this->dbAnon->setAnswer($answer);
    }

    /**
     * {@inheritdoc}
     */
    public function getAnswer()
    {
        return $this->dbAnon->getAnswer();
    }

    /**
     * {@inheritdoc}
     */
    public function getDbAnonymous()
    {
        return $this->dbAnon;
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(UsersFilter $filter)
    {
        $this->attributes['filters'][] = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function removeFilter(UsersFilter $filter)
    {
        $key = array_search($filter, $this->attributes['filters']);
        if ($key !== false) {
            unset($this->attributes['filters'][$key]);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function getEditedComments()
    {
        return array_key_exists(
            'editedComments',
            $this->attributes
        ) ? $this->attributes['editedComments'] : $this->dbAnon->getEditedComments();
    }

    /**
     * {@inheritdoc}
     */
    public function addEditedComment(Message $editedComment)
    {
        $this->attributes['editedComments'][] = $editedComment;
    }

    /**
     * {@inheritdoc}
     */
    public function removeEditedComment(Message $editedComment)
    {
        $key = array_search($editedComment, $this->attributes['editedComments']);
        if ($key !== false) {
            unset($this->attributes['editedComments'][$key]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addMessage(Message $message)
    {
        $this->dbAnon->addMessage($message);
    }

    /**
     * {@inheritdoc}
     */
    public function removeMessage(Message $message)
    {
        $this->dbAnon->removeMessage($message);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        return $this->dbAnon->getMessages();
    }

    /**
     * {@inheritdoc}
     */
    public function addPosition(BlockPosition $position)
    {
        $this->attributes['block_position'][] = $position;
    }

    /**
     * {@inheritdoc}
     */
    public function removePosition(BlockPosition $position)
    {
        $key = array_search($position, $this->attributes['block_position']);
        if ($key !== false) {
            unset($this->attributes['block_position'][$key]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPositions()
    {
        return array_key_exists(
            'block_position',
            $this->attributes
        ) ? $this->attributes['block_position'] : $this->dbAnon->getPositions();
    }

    /**
     * {@inheritdoc}
     */
    public function addReadThread(Reader $reader)
    {
        $this->dbAnon->addReadThread($reader);
    }

    /**
     * {@inheritdoc}
     */
    public function removeReadThread(Reader $reader)
    {
        $this->dbAnon->removeReadThread($reader);
    }

    /**
     * {@inheritdoc}
     */
    public function getReadThreads()
    {
        return $this->dbAnon->getReadThreads();
    }

    /**
     * {@inheritdoc}
     */
    public function setMessages($messages)
    {
        $this->dbAnon->setMessages($messages);
    }

    /**
     * {@inheritdoc}
     */
    public function setPositions($positions)
    {
        if (is_array($positions)) {
            $this->attributes['block_position'] = $positions;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setReadThreads($readThreads)
    {
        if (is_array($readThreads)) {
            $this->dbAnon->setReadThreads($readThreads);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setFilters($filters)
    {
        if (is_array($filters)) {
            $this->attributes['filters'] = $filters;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setEditedComments($editedComments)
    {
        if (is_array($editedComments)) {
            $this->attributes['editedComments'] = $editedComments;
        }
    }
}
