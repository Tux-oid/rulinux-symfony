<?php
/**
 * @author Ax-xa-xa 
 */

namespace RL\SecurityBundle\Security\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use RL\SecurityBundle\Entity\Group;
use RL\MainBundle\Entity\Mark;

class AnonymousUser implements RLUserInterface, EquatableInterface
{
	protected $attributes;
	protected $identity;
	protected $dbAnon;
	protected $doctrine;
	public function __construct($identity, array $attributes = array(), \Doctrine\Bundle\DoctrineBundle\Registry &$doctrine, $logger = NULL)
	{
		$this->identity = $identity;
		$this->attributes = $attributes;
		$this->doctrine = &$doctrine;
		$userRepository = $doctrine->getRepository('RLSecurityBundle:User');
		$this->dbAnon = $userRepository->findOneByUsername('anonymous');
		$this->dbAnon->setLastVisitDate(new \DateTime('now'));
	}
	public function __toString()
	{
		return 'anon.';
	}
	// RLUserInterface

	public function getIdentity()
	{
		return $this->identity;
	}
	public function isAnonymous()
	{
		return TRUE;
	}
	public function getAttributes()
	{
		return $this->attributes;
	}
	public function getTheme()
	{
		return array_key_exists('theme', $this->attributes) ? $this->doctrine->getManager()->getRepository('RLThemesBundle:Theme')->findOneByName($this->attributes['theme']) : $this->dbAnon->getTheme();
	}
	public function setTheme($value)
	{
		$this->attributes['theme'] = $value->getName();
	}
	// AdvancedUserInterface

	public function isAccountNonExpired()
	{
		return true;
	}
	function isAccountNonLocked()
	{
		return TRUE;
	}
	public function isCredentialsNonExpired()
	{
		return true;
	}
	public function isEnabled()
	{
		return TRUE;
	}
	// UserInterface

	public function getRoles()
	{
		$userRole = $this->dbAnon->getRoles();
		return $userRole;
	}
	public function getPassword()
	{
		return NULL;
	}
	public function getSalt()
	{
		return NULL;
	}
	public function getUsername()
	{
		return $this->dbAnon->getUsername();
	}
	public function eraseCredentials()
	{
		
	}
	public function isEqualTo(UserInterface $user)
	{
		if($user instanceof RLUser)
		{
			if($user->isAnonymous)
			{
				return ($this->getIdentity() == $user->getIdentity());
			}
			else
				return false;
		}
		else
			return false;
	}
	public function getBlocks()
	{
		return array_key_exists('blocks', $this->attributes) ? $this->attributes['blocks'] : $this->dbAnon->getCaptchaLevel();
	}
	public function setBlocks($value)
	{
		$attributes = $this->getAttributes();
		$attributes['blocks'] = $value;
	}
	public function setGroup(Group $group)
	{
	}
	public function isActive()
	{
		return TRUE;
	}
	public function getCaptchaLevel()
	{
		return array_key_exists('captchaLevel', $this->attributes) ? $this->attributes['captchaLevel'] : $this->dbAnon->getCaptchaLevel();
	}
	public function getCommentsOnPage()
	{
		return array_key_exists('commentsOnPage', $this->attributes) ? $this->attributes['commentsOnPage'] : $this->dbAnon->getCommentsOnPage();
	}
	public function getFilters()
	{
		return array_key_exists('filters', $this->attributes) ? $this->attributes['filters'] : $this->dbAnon->getFilters();
	}
	public function getGmt()
	{
		return array_key_exists('gmt', $this->attributes) ? $this->attributes['gmt'] : $this->dbAnon->getGmt();
	}
	public function getGroup()
	{
		return $this->getRoles();
	}
	public function getId()
	{
		return $this->dbAnon->getId();
	}
	public function getLanguage()
	{
		return array_key_exists('language', $this->attributes) ? $this->attributes['language'] : $this->dbAnon->getLanguage();
	}
	public function getLastVisitDate()
	{
		return $this->dbAnon->getLastVisitDate();
	}
	public function getMark()
	{
		return array_key_exists('mark', $this->attributes) ? $this->doctrine->getManager()->getRepository('RLMainBundle:Mark')->findOneByName($this->attributes['mark']) : $this->dbAnon->getMark();
	}
	public function getNewsOnPage()
	{
		return array_key_exists('newsOnPage', $this->attributes) ? $this->attributes['newsOnPage'] : $this->dbAnon->getNewsOnPage();
	}
	public function getRegistrationDate()
	{
		return $this->dbAnon->getRegistrationDate();
	}
	public function getShowAvatars()
	{
		return array_key_exists('showAvatars', $this->attributes) ? $this->attributes['showAvatars'] : $this->dbAnon->getShowAvatars();
	}
	public function getShowResp()
	{
		return array_key_exists('showResp', $this->attributes) ? $this->attributes['showResp'] : $this->dbAnon->getShowResp();
	}
	public function getShowUa()
	{
		return array_key_exists('showUa', $this->attributes) ? $this->attributes['showUa'] : $this->dbAnon->getShowUa();
	}
	public function getSortingType()
	{
		return array_key_exists('sortingType', $this->attributes) ? $this->attributes['sortingType'] : $this->dbAnon->getSortingType();
	}
	public function getThreadsOnPage()
	{
		return array_key_exists('threadsOnPage', $this->attributes) ? $this->attributes['threadsOnPage'] : $this->dbAnon->getThreadsOnPage();
	}
	public function setCaptchaLevel($captchaLevel)
	{
		$this->attributes['captchaLevel'] = $captchaLevel;
	}
	public function setCommentsOnPage($commentsOnPage)
	{
		$this->attributes['commentsOnPage'] = $commentsOnPage;
	}
	public function setFilters($filters)
	{
		$this->attributes['filters'] = $filters;
	}
	public function setGmt($gmt)
	{
		$this->attributes['gmt'] = $gmt;
	}
	public function setLanguage($language)
	{
		$this->attributes['language'] = $language;
	}
	public function setLastVisitDate($lastVisitDate)
	{
		$this->attributes['lastVisitDate'] = $lastVisitDate;
	}
	public function setMark(\RL\MainBundle\Entity\Mark $mark)
	{
		$this->attributes['mark'] = $mark->getName();
	}
	public function setNewsOnPage($newsOnPage)
	{
		$this->attributes['newsOnPage'] = $newsOnPage;
	}
	public function setRegistrationDate($registrationDate)
	{
		$this->attributes['registrationDate'] = $registrationDate;
	}
	public function setShowAvatars($showAvatars)
	{
		$this->attributes['showAvatars'] = $showAvatars;
	}
	public function setShowResp($showResp)
	{
		$this->attributes['showResp'] = $showResp;
	}
	public function setShowUa($showUa)
	{
		$this->attributes['showUa'] = $showUa;
	}
	public function setSortingType($sortingType)
	{
		$this->attributes['sortingType'] = $sortingType;
	}
	public function setThreadsOnPage($threadsOnPage)
	{
		$this->attributes['threadsOnPage'] = $threadsOnPage;
	}

	public function setName($name)
	{
		return $this->dbAnon->setName($name);
	}

	public function getName()
	{
		return $this->dbAnon->getName();
	}

	public function setLastname($lastname)
	{
		return $this->dbAnon->setLastname($lastname);
	}

	public function getLastname()
	{
		return $this->dbAnon->getLastname();
	}

	public function setCountry($country)
	{
		return $this->dbAnon->setCountry($country);
	}

	public function getCountry()
	{
		return $this->dbAnon->getCountry();
	}

	public function setCity($city)
	{
		return $this->dbAnon->setCity($city);
	}

	public function getCity()
	{
		return $this->dbAnon->getCity();
	}

	public function setPhoto($photo)
	{
		return $this->dbAnon->setPhoto($photo);
	}

	public function getPhoto()
	{
		return $this->dbAnon->getPhoto();
	}

	public function setBirthday($birthday)
	{
		return $this->dbAnon->setBirthday($birthday);
	}

	public function getBirthday()
	{
		return $this->dbAnon->getBirthday();
	}

	public function setGender($gender)
	{
		return $this->dbAnon->setGender($gender);
	}

	public function getGender()
	{
		return $this->dbAnon->getGender();
	}

	public function setAdditional($additional)
	{
		return $this->dbAnon->setAdditional($additional);
	}

	public function getAdditional()
	{
		return $this->dbAnon->getAdditional();
	}

	public function setAdditionalRaw($additionalRaw)
	{
		return $this->dbAnon->setAdditionalRaw($additionalRaw);
	}

	public function getAdditionalRaw()
	{
		return $this->dbAnon->getAdditionalRaw();
	}

	public function setEmail($email)
	{
		return $this->dbAnon->setEmail($email);
	}

	public function getEmail()
	{
		return $this->dbAnon->getEmail();
	}

	public function setIm($im)
	{
		return $this->dbAnon->setIm($im);
	}

	public function getIm()
	{
		return $this->dbAnon->getIm();
	}

	public function setOpenid($openid)
	{
		return $this->dbAnon->setOpenid($openid);
	}

	public function getOpenid()
	{
		return $this->dbAnon->getOpenid();
	}

	public function setShowEmail($showEmail)
	{
		return $this->dbAnon->setShowEmail($showEmail);
	}

	public function getShowEmail()
	{
		return $this->dbAnon->getShowEmail();
	}

	public function setShowIm($showIm)
	{
		return $this->dbAnon->setShowIm($showIm);
	}

	public function getShowIm()
	{
		return $this->dbAnon->getShowIm();
	}

	public function setActive($active)
	{
		return $this->dbAnon->setActive($active);
	}

	public function setQuestion($question)
	{
		return $this->dbAnon->setQuestion($question);
	}

	public function getQuestion()
	{
		return $this->dbAnon->getQuestion();
	}

	public function setAnswer($answer)
	{
		return $this->dbAnon->setAnswer($answer);
	}

	public function getAnswer()
	{
		return $this->dbAnon->getAnswer();
	}
}
