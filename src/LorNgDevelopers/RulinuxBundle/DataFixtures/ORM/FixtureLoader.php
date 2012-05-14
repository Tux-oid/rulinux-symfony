<?php

namespace LorNgDevelopers\RulinuxBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use LorNgDevelopers\RulinuxBundle\Entity\User;
use LorNgDevelopers\RulinuxBundle\Entity\Group;
use LorNgDevelopers\RulinuxBundle\Entity\Settings;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class FixtureLoader implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$userRole = new Group();
		$userRole->setName('ROLE_USER');
		$userRole->setDescription('site users');
		$manager->persist($userRole);
		$moderRole = new Group();
		$moderRole->setName('ROLE_MODER');
		$moderRole->setDescription('site moderators');
		$manager->persist($moderRole);
		$adminRole = new Group();
		$adminRole->setName('ROLE_ADMIN');
		$adminRole->setDescription('site administrators');
		//TODO: add super admin role
		$manager->persist($adminRole);
		$captchaLevel = 0;
		$captchaSetting = new Settings();
		$captchaSetting->setName('captchaLevel');
		$captchaSetting->setValue($captchaLevel);
		$manager->persist($captchaSetting);
		$theme = 'default';
		$themeSetting = new Settings();
		$themeSetting->setName('theme');
		$themeSetting->setValue($theme);
		$manager->persist($themeSetting);
		$sortingType = 0;
		$sortingTypeSetting = new Settings();
		$sortingTypeSetting->setName('sortingType');
		$sortingTypeSetting->setValue($sortingType);
		$manager->persist($sortingTypeSetting);
		$newsOnPage = 10;
		$newsOnPageSetting = new Settings();
		$newsOnPageSetting->setName('newsOnPage');
		$newsOnPageSetting->setValue($newsOnPage);
		$manager->persist($newsOnPageSetting);
		$threadsOnPage = 30;
		$threadsOnPageSetting = new Settings();
		$threadsOnPageSetting->setName('threadsOnPage');
		$threadsOnPageSetting->setValue($threadsOnPage);
		$manager->persist($threadsOnPageSetting);
		$commentsOnPage = 50;
		$commentsOnPageSetting = new Settings();
		$commentsOnPageSetting->setName('commentsOnPage');
		$commentsOnPageSetting->setValue($commentsOnPage);
		$manager->persist($commentsOnPageSetting);
		$showEmail = 0;
		$showEmailSetting = new Settings();
		$showEmailSetting->setName('showEmail');
		$showEmailSetting->setValue($showEmail);
		$manager->persist($showEmailSetting);
		$showIm = 0;
		$showImSetting = new Settings();
		$showImSetting->setName('showIm');
		$showImSetting->setValue($showIm);
		$manager->persist($showImSetting);
		$showAvatars = 1;
		$showAvatarsSetting = new Settings();
		$showAvatarsSetting->setName('showAvatars');
		$showAvatarsSetting->setValue($showAvatars);
		$manager->persist($showAvatarsSetting);
		$showUa = 1;
		$showUaSetting = new Settings();
		$showUaSetting->setName('showUa');
		$showUaSetting->setValue($showUa);
		$manager->persist($showUaSetting);
		$showResp = 1;
		$showRespSetting = new Settings();
		$showRespSetting->setName('showResp');
		$showRespSetting->setValue($showResp);
		$manager->persist($showRespSetting);
		$language = 'en';
		$languageSetting = new Settings();
		$languageSetting->setName('language');
		$languageSetting->setValue($language);
		$manager->persist($languageSetting);
		$gmt = 'Europe/London';
		$gmtSetting = new Settings();
		$gmtSetting->setName('gmt');
		$gmtSetting->setValue($gmt);
		$manager->persist($gmtSetting);
		//TODO: other values
		$admin = new User();
		$admin->setUsername('Admin');
		$admin->setName('Site Administrator');
		$admin->setLastName('');
		$admin->setEmail('noemail@example.com');
		$admin->setSalt(md5(time()));
		$admin->setAdditional('');
		$admin->setAdditionalRaw('');
		$admin->setBirthday(new \DateTime('now'));
		$admin->setGender(1);
		$admin->setRegistrationDate(new \DateTime('now'));
		$admin->setLastVisitDate(new \DateTime('now'));
		$admin->setCaptchaLevel($captchaLevel);
		$admin->setTheme($theme);
		$admin->setSortingType($sortingType);
		$admin->setNewsOnPage($newsOnPage);
		$admin->setThreadsOnPage($threadsOnPage);
		$admin->setCommentsOnPage($commentsOnPage);
		$admin->setShowEmail($showEmail);
		$admin->setShowIm($showIm);
		$admin->setShowAvatars($showAvatars);
		$admin->setShowUa($showUa);
		$admin->setShowResp($showResp);
		$admin->setLanguage($language);
		$admin->setGmt($gmt);
		$encoder = new MessageDigestPasswordEncoder('md5', false, 1);
		$password = $encoder->encodePassword('admin', $admin->getSalt());
		$admin->setPassword($password);
		$admin->getGroups()->add($adminRole);
		$manager->persist($admin);
		$manager->flush();
	}
}