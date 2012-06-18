<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RL\ForumBundle\Entity\Subsection;
use RL\MainBundle\Entity\Section;


class FixtureLoader implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$forumSection = new Section();
		$forumSection->setName('Forum');
		$forumSection->setDescription('Forum about GNU\Linux and not only');
		$forumSection->setRewrite('forum');
		$forumSection->setBundle('RLForumBundle');
		$forumSection->setBundleNamespace('RL\ForumBundle');
		$manager->persist($forumSection);
		
		$generalSubsection = new Subsection();
		$generalSubsection->setName('General');
		$generalSubsection->setDescription('general forum for issues not falling into other groups');
		$generalSubsection->setShortfaq('');
		$generalSubsection->setRewrite('general');
		$generalSubsection->setSection($forumSection);
		$manager->persist($generalSubsection);
		
		$desktopSubsection = new Subsection();
		$desktopSubsection->setName('Desktop');
		$desktopSubsection->setDescription('questions about using Linux/Unix on desktop');
		$desktopSubsection->setShortfaq('');
		$desktopSubsection->setRewrite('desktop');
		$desktopSubsection->setSection($forumSection);
		$manager->persist($desktopSubsection);
		
		$adminSubsection = new Subsection();
		$adminSubsection->setName('Admin');
		$adminSubsection->setDescription('administration of Linux/Unix systems and networks');
		$adminSubsection->setShortfaq('');
		$adminSubsection->setRewrite('admin');
		$adminSubsection->setSection($forumSection);
		$manager->persist($adminSubsection);
		
		$linuxInstallSubsection = new Subsection();
		$linuxInstallSubsection->setName('Linux-install');
		$linuxInstallSubsection->setDescription('Linux installation');
		$linuxInstallSubsection->setShortfaq('');
		$linuxInstallSubsection->setRewrite('linux_install');
		$linuxInstallSubsection->setSection($forumSection);
		$manager->persist($linuxInstallSubsection);
		
		$bsdSubsection = new Subsection();
		$bsdSubsection->setName('BSD');
		$bsdSubsection->setDescription('forum about BSD systems');
		$bsdSubsection->setShortfaq('');
		$bsdSubsection->setRewrite('bsd');
		$bsdSubsection->setSection($forumSection);
		$manager->persist($bsdSubsection);
		
		$developmentSubsection = new Subsection();
		$developmentSubsection->setName('Development');
		$developmentSubsection->setDescription('programming and software development for Linux/Unix');
		$developmentSubsection->setShortfaq('');
		$developmentSubsection->setRewrite('development');
		$developmentSubsection->setSection($forumSection);
		$manager->persist($developmentSubsection);
		
		$serverSubsection = new Subsection();
		$serverSubsection->setName('Rulinux.net');
		$serverSubsection->setDescription('comment about work of server rulinux.net');
		$serverSubsection->setShortfaq('');
		$serverSubsection->setRewrite('rulinux_net');
		$serverSubsection->setSection($forumSection);
		$manager->persist($serverSubsection);
		
		$securitySubsection = new Subsection();
		$securitySubsection->setName('Security');
		$securitySubsection->setDescription('security');
		$securitySubsection->setShortfaq('');
		$securitySubsection->setRewrite('security');
		$securitySubsection->setSection($forumSection);
		$manager->persist($securitySubsection);
		
		$linuxHardwareSubsection = new Subsection();
		$linuxHardwareSubsection->setName('Linux-hardware');
		$linuxHardwareSubsection->setDescription('hardware & Linux');
		$linuxHardwareSubsection->setShortfaq('');
		$linuxHardwareSubsection->setRewrite('linux_hardware');
		$linuxHardwareSubsection->setSection($forumSection);
		$manager->persist($linuxHardwareSubsection);
		
		$talksSubsection = new Subsection();
		$talksSubsection->setName('Talks');
		$talksSubsection->setDescription('non technical talks about Linux/Unix');
		$talksSubsection->setShortfaq('');
		$talksSubsection->setRewrite('talks');
		$talksSubsection->setSection($forumSection);
		$manager->persist($talksSubsection);
		
		$jobSubsection = new Subsection();
		$jobSubsection->setName('Job');
		$jobSubsection->setDescription('search for and supply of work related to Linux/Unix');
		$jobSubsection->setShortfaq('');
		$jobSubsection->setRewrite('job');
		$jobSubsection->setSection($forumSection);
		$manager->persist($jobSubsection);
		
		$gamesSubsection = new Subsection();
		$gamesSubsection->setName('Games');
		$gamesSubsection->setDescription('games for Linux/Unix');
		$gamesSubsection->setShortfaq('');
		$gamesSubsection->setRewrite('games');
		$gamesSubsection->setSection($forumSection);
		$manager->persist($gamesSubsection);
		
		$webDevelopmentSubsection = new Subsection();
		$webDevelopmentSubsection->setName('Web-development');
		$webDevelopmentSubsection->setDescription('web development');
		$webDevelopmentSubsection->setShortfaq('');
		$webDevelopmentSubsection->setRewrite('web_development');
		$webDevelopmentSubsection->setSection($forumSection);
		$manager->persist($webDevelopmentSubsection);
		
		$trashSubsection = new Subsection();
		$trashSubsection->setName('Trash');
		$trashSubsection->setDescription('trash section');
		$trashSubsection->setShortfaq('');
		$trashSubsection->setRewrite('trash');
		$trashSubsection->setSection($forumSection);
		$manager->persist($trashSubsection);
		
		$manager->flush();
	}
}