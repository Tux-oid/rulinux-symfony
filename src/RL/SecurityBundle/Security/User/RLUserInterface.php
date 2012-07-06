<?php
/**
 * @author Ax-xa-xa 
 * @author Tux-oid
 */

namespace RL\SecurityBundle\Security\User;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface RLUserInterface extends AdvancedUserInterface
{
	/**
	 * Returns string what unique identity user.
	 */
	function getIdentity();
	/**
	 * Returns anonymous or not.
	 */
	function isAnonymous();
	/**
	 * Returns attributes.
	 */
	function getAttributes();
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
	public function setBlocks($blocks);
	public function getBlocks();
	public function setTheme($theme);
	public function getTheme();
	public function setGmt($gmt);
	public function getGmt();
	public function setFilters($filters);
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
	public function addGroup(\RL\SecurityBundle\Entity\Group $groups);
	public function getGroups();
	public function setLanguage($language);
	public function getLanguage();
	public function setActive($active);
	public function isActive();
	public function setQuestion($question);
	public function getQuestion();
	public function setAnswer($answer);
	public function getAnswer();
}
