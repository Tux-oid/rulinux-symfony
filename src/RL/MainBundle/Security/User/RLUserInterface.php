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

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

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
     * Returns string what unique identity user.
     */
    public function getIdentity();
    /**
     * Returns anonymous or not.
     */
    public function isAnonymous();
    /**
     * Returns attributes.
     */
    public function getAttributes();
    public function getId();
    public function setName($name);
    public function getName();
    public function setLastname($lastname);
    public function getLastname();
    public function setCountry($country);
    public function getCountry();
    public function setCity($city);
    public function getCity();
    public function setPhoto($photo);
    public function getPhoto();
    public function setBirthday($birthday);
    public function getBirthday();
    public function setGender($gender);
    public function getGender();
    public function setAdditional($additional);
    public function getAdditional();
    public function setAdditionalRaw($additionalRaw);
    public function getAdditionalRaw();
    public function setEmail($email);
    public function getEmail();
    public function setIm($im);
    public function getIm();
    public function setRegistrationDate($registrationDate);
    public function getRegistrationDate();
    public function setLastVisitDate($lastVisitDate);
    public function getLastVisitDate();
    public function setCaptchaLevel($captchaLevel);
    public function getCaptchaLevel();
    public function setOpenid($openid);
    public function getOpenid();
    public function addLeftBlock($block);
    public function removeLeftBlock($block);
    public function getLeftBlocks();
    public function setLeftBlocksWeights($leftBlocksWeights);
    public function getLeftBlocksWeights();
    public function addRightBlock($block);
    public function removeRightBlock($block);
    public function getRightBlocks();
    public function setRightBlocksWeights($rightBlocksWeights);
    public function getRightBlocksWeights();
    public function setTheme($theme);
    public function getTheme();
    public function setGmt($gmt);
    public function getGmt();
    public function addFilter($filter);
    public function removeFilter($filter);
    public function getFilters();
    public function setSortingType($sortingType);
    public function getSortingType();
    public function setNewsOnPage($newsOnPage);
    public function getNewsOnPage();
    public function setCommentsOnPage($commentsOnPage);
    public function getCommentsOnPage();
    public function setThreadsOnPage($threadsOnPage);
    public function getThreadsOnPage();
    public function setShowEmail($showEmail);
    public function getShowEmail();
    public function setShowIm($showIm);
    public function getShowIm();
    public function setShowAvatars($showAvatars);
    public function getShowAvatars();
    public function setShowUa($showUa);
    public function getShowUa();
    public function setShowResp($showResp);
    public function getShowResp();
    public function setMark(\RL\MainBundle\Entity\Mark $mark);
    public function getMark();
    public function setGroup(\RL\MainBundle\Entity\Group $group);
    public function getGroup();
    public function setLanguage($language);
    public function getLanguage();
    public function setActive($active);
    public function isActive();
    public function setQuestion($question);
    public function getQuestion();
    public function setAnswer($answer);
    public function getAnswer();
    public function addEditedComment($editedComment);
    public function removeEditedComment($editedComment);
    public function getEditedComments();
    public function addMessage(\RL\MainBundle\Entity\Message $message);
    public function removeMessage(\RL\MainBundle\Entity\Message $message);
    public function getMessages();
}
