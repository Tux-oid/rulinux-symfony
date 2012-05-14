<?php

namespace LorNgDevelopers\RulinuxBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use LorNgDevelopers\RulinuxBundle\Entity\User;
use LorNgDevelopers\RulinuxBundle\Entity\Group;
use LorNgDevelopers\RulinuxBundle\Entity\Settings;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class FixtureLoader implements FixtureInterface
{
	public function load($manager)
	{
		$userRole = new Role();
		$userRole->setName('ROLE_USER');
		$manager->persist($userRole);
		$moderRole = new Role();
		$moderRole->setName('ROLE_MODER');
		$manager->persist($moderRole);
		$adminRole = new Role();
		$adminRole->setName('ROLE_ADMIN');
		$manager->persist($adminRole);

		$admin = new User();
		$admin->setUsername('Admin');
		$admin->setName('Site Administrator');
		$admin->setLastName('');
		$admin->setEmail('noemail@example.com');
		$admin->setSalt(md5(time()));
		$admin->setAdditional('');
		$admin->setAdditionalRaw('');
		$admin->setBirthday(new \DateTime('now'));
		$admin->setGender(true);
		$admin->setRegistrationDate(new \DateTime('now'));
		$admin->setLastVisitDate(new \DateTime('now'));
		$admin->setCaptchaLevel($settings->findOneByName('captcha_level')->getValue());
		$admin->setTheme($settings->findOneByName('theme')->getValue());
		$admin->setSortingType($settings->findOneByName('sortingType')->getValue());
		$admin->setNewsOnPage($settings->findOneByName('news_on_page')->getValue());
		$admin->setThreadsOnPage($settings->findOneByName('threads_on_page')->getValue());
		$admin->setCommentsOnPage($settings->findOneByName('comments_on_page')->getValue());
		$admin->setShowEmail($settings->findOneByName('showEmail')->getValue());
		$admin->setShowIm($settings->findOneByName('showIm')->getValue());
		$admin->setShowAvatars($settings->findOneByName('showAvatars')->getValue());
		$admin->setShowUa($settings->findOneByName('showUa')->getValue());
		$admin->setShowResp($settings->findOneByName('showResp')->getValue());
		$encoder = new MessageDigestPasswordEncoder('md5', false, 1);
		$password = $encoder->encodePassword('admin', $admin->getSalt());
		$admin->setPassword($password);
		$admin->getUserRoles()->add($adminRole);
		$manager->persist($admin);
		$manager->flush();
	}
}