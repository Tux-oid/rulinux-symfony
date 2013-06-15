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

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use RL\MainBundle\Entity\Message;
use RL\MainBundle\Entity\BlockPosition;
use RL\MainBundle\Entity\Mark;
use RL\MainBundle\Entity\Group;
use RL\MainBundle\Entity\UsersFilter;
use RL\MainBundle\Entity\Reader;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RL\MainBundle\Security\User\RLUserInterface
 *
 * @author Ax-xa-xa
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
interface RLUserInterface extends AdvancedUserInterface
{

    /**
     * @return bool
     */
    public function isAccountNonExpired();

    /**
     * @return bool
     */
    public function isAccountNonLocked();

    /**
     * @return bool
     */
    public function isCredentialsNonExpired();

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * {@inheritDoc}
     */
    public function getUsername();

    /**
     * {@inheritDoc}
     */
    public function getSalt();

    /**
     * {@inheritDoc}
     */
    public function getPassword();

    /**
     * {@inheritDoc}
     */
    public function getRoles();

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials();

    /**
     * {@inheritDoc}
     */
    public function isEqualTo(UserInterface $user);

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();


    /**
     * Set messages
     *
     * @param ArrayCollection|array $messages
     */
    public function setMessages($messages);

    /**
     * Set positions
     *
     * @param ArrayCollection|array $positions
     */
    public function setPositions($positions);

    /**
     * Set read threads
     *
     * @param ArrayCollection|array $readThreads
     */
    public function setReadThreads($readThreads);

    /**
     * Set filters
     *
     * @param ArrayCollection|array $filters
     */
    public function setFilters($filters);

    /**
     * Set edited comments
     *
     * @param ArrayCollection|array $editedComments
     */
    public function setEditedComments($editedComments);

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname);

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname();

    /**
     * Set country
     *
     * @param string $country
     */
    public function setCountry($country);

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry();

    /**
     * Set city
     *
     * @param string $city
     */
    public function setCity($city);

    /**
     * Get city
     *
     * @return string
     */
    public function getCity();

    /**
     * Set photo
     *
     * @param string $photo
     */
    public function setPhoto($photo);

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto();

    /**
     * Set birthday
     *
     * @param \Datetime $birthday
     */
    public function setBirthday($birthday);

    /**
     * Get birthday
     *
     * @return \Datetime
     */
    public function getBirthday();

    /**
     * Set gender
     *
     * @param boolean $gender
     */
    public function setGender($gender);

    /**
     * Get gender
     *
     * @return boolean
     */
    public function getGender();

    /**
     * Set additional
     *
     * @param string $additional
     */
    public function setAdditional($additional);

    /**
     * Get additional
     *
     * @return string
     */
    public function getAdditional();

    /**
     * Set additionalRaw
     *
     * @param string $additionalRaw
     */
    public function setAdditionalRaw($additionalRaw);

    /**
     * Get additionalRaw
     *
     * @return string
     */
    public function getAdditionalRaw();

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email);

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set im
     *
     * @param string $im
     */
    public function setIm($im);

    /**
     * Get im
     *
     * @return string
     */
    public function getIm();

    /**
     * Set registrationDate
     *
     * @param \Datetime $registrationDate
     */
    public function setRegistrationDate($registrationDate);

    /**
     * Get registrationDate
     *
     * @return \Datetime
     */
    public function getRegistrationDate();

    /**
     * Set lastVisitDate
     *
     * @param \Datetime $lastVisitDate
     */
    public function setLastVisitDate($lastVisitDate);

    /**
     * Get lastVisitDate
     *
     * @return \Datetime
     */
    public function getLastVisitDate();

    /**
     * Set captchaLevel
     *
     * @param integer $captchaLevel
     */
    public function setCaptchaLevel($captchaLevel);

    /**
     * Get captchaLevel
     *
     * @return integer
     */
    public function getCaptchaLevel();

    /**
     * Set openid
     *
     * @param string $openid
     */
    public function setOpenid($openid);

    /**
     * Get openid
     *
     * @return string
     */
    public function getOpenid();

    /**
     * Set theme
     *
     * @param string $theme
     */
    public function setTheme($theme);

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme();

    /**
     * Set gmt
     *
     * @param string $gmt
     */
    public function setGmt($gmt);

    /**
     * Get gmt
     *
     * @return string
     */
    public function getGmt();

    /**
     * Add filter
     *
     * @param UsersFilter $filter
     */
    public function addFilter(UsersFilter $filter);

    /**
     * Remove filter
     *
     * @param UsersFilter $filter
     */
    public function removeFilter(UsersFilter $filter);

    /**
     * Get filters
     *
     * @return ArrayCollection|array
     */
    public function getFilters();

    /**
     * Set sortingType
     *
     * @param string $sortingType
     */
    public function setSortingType($sortingType);

    /**
     * Get sortingType
     *
     * @return string
     */
    public function getSortingType();

    /**
     * Set newsOnPage
     *
     * @param integer $newsOnPage
     */
    public function setNewsOnPage($newsOnPage);

    /**
     * Get newsOnPage
     *
     * @return integer
     */
    public function getNewsOnPage();

    /**
     * Set commentsOnPage
     *
     * @param integer $commentsOnPage
     */
    public function setCommentsOnPage($commentsOnPage);

    /**
     * Get commentsOnPage
     *
     * @return integer
     */
    public function getCommentsOnPage();

    /**
     * Set threadsOnPage
     *
     * @param integer $threadsOnPage
     */
    public function setThreadsOnPage($threadsOnPage);

    /**
     * Get threadsOnPage
     *
     * @return integer
     */
    public function getThreadsOnPage();

    /**
     * Set showEmail
     *
     * @param boolean $showEmail
     */
    public function setShowEmail($showEmail);

    /**
     * Get showEmail
     *
     * @return boolean
     */
    public function isShowEmail();

    /**
     * Set showIm
     *
     * @param boolean $showIm
     */
    public function setShowIm($showIm);

    /**
     * Get showIm
     *
     * @return boolean
     */
    public function isShowIm();

    /**
     * Set showAvatars
     *
     * @param boolean $showAvatars
     */
    public function setShowAvatars($showAvatars);

    /**
     * Get showAvatars
     *
     * @return boolean
     */
    public function isShowAvatars();

    /**
     * Set showUa
     *
     * @param boolean $showUa
     */
    public function setShowUa($showUa);

    /**
     * Get showUa
     *
     * @return boolean
     */
    public function isShowUa();

    /**
     * Set showResp
     *
     * @param boolean $showResp
     */
    public function setShowResp($showResp);

    /**
     * Get showResp
     *
     * @return boolean
     */
    public function isShowResp();

    /**
     * Set mark
     *
     * @param \RL\MainBundle\Entity\Mark $mark
     */
    public function setMark(Mark $mark);

    /**
     * Get mark
     *
     * @return Mark
     */
    public function getMark();

    /**
     * Set language
     *
     * @param string $language
     */
    public function setLanguage($language);

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage();

    /**
     * Set active
     *
     * @param boolean $active
     */
    public function setActive($active);

    /**
     * Set question
     *
     * @param string $question
     */
    public function setQuestion($question);

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion();

    /**
     * Set answer
     *
     * @param string $answer
     */
    public function setAnswer($answer);

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer();

    /**
     * @return string
     */
    public function getIdentity();

    /**
     * @return bool
     */
    public function isAnonymous();

    /**
     * @return bool
     */
    public function isActive();

    /**
     * Add comment
     *
     * @param Message $message
     */
    public function addMessage(Message $message);

    /**
     * Remove comment
     *
     * @param Message $message
     */
    public function removeMessage(Message $message);

    /**
     * Get comments
     *
     * @return ArrayCollection
     */
    public function getMessages();

    /**
     * Set group
     *
     * @param Group $group
     */
    public function setGroup(Group $group);

    /**
     * Get group
     *
     * @return Group
     */
    public function getGroup();

    /**
     * @param Message $editedComment
     * @return mixed
     */
    public function addEditedComment(Message $editedComment);

    /**
     * @param Message $editedComment
     * @return mixed
     */
    public function removeEditedComment(Message $editedComment);

    /**
     * @return mixed
     */
    public function getEditedComments();

    /**
     * Add position
     *
     * @param BlockPosition $position
     */
    public function addPosition(BlockPosition $position);

    /**
     * Remove position
     *
     * @param BlockPosition $position
     */
    public function removePosition(BlockPosition $position);

    /**
     * Get positions
     *
     * @return ArrayCollection|array
     */
    public function getPositions();

    /**
     * Add reader
     *
     * @param Reader $reader
     */
    public function addReadThread(Reader $reader);

    /**
     * Remove reader
     *
     * @param Reader $reader
     */
    public function removeReadThread(Reader $reader);

    /**
     * Get readers
     *
     * @return ArrayCollection|array
     */
    public function getReadThreads();

}
