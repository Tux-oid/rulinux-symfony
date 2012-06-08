<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RL\ForumBundle\Entity\Subsection;


class FixtureLoader implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$generalSubsection = new Subsection();
		$generalSubsection->setName('General');
		$generalSubsection->setDescription('general forum for issues not falling into other groups');
		$generalSubsection->setShortfaq('');
		$generalSubsection->setRewrite('general');
		$manager->persist($generalSubsection);
		
		$desktopSubsection = new Subsection();
		$desktopSubsection->setName('Desktop');
		$desktopSubsection->setDescription('questions about using Linux/Unix on desktop');
		$desktopSubsection->setShortfaq('');
		$desktopSubsection->setRewrite('desktop');
		$manager->persist($desktopSubsection);
		
		$adminSubsection = new Subsection();
		$adminSubsection->setName('Admin');
		$adminSubsection->setDescription('administration of Linux/Unix systems and networks');
		$adminSubsection->setShortfaq('');
		$adminSubsection->setRewrite('admin');
		$manager->persist($adminSubsection);
		
		$linuxInstallSubsection = new Subsection();
		$linuxInstallSubsection->setName('Linux-install');
		$linuxInstallSubsection->setDescription('Linux installation');
		$linuxInstallSubsection->setShortfaq('');
		$linuxInstallSubsection->setRewrite('linux_install');
		$manager->persist($linuxInstallSubsection);
		
		$bsdSubsection = new Subsection();
		$bsdSubsection->setName('BSD');
		$bsdSubsection->setDescription('forum about BSD systems');
		$bsdSubsection->setShortfaq('');
		$bsdSubsection->setRewrite('bsd');
		$manager->persist($bsdSubsection);
		
		$developmentSubsection = new Subsection();
		$developmentSubsection->setName('Development');
		$developmentSubsection->setDescription('programming and software development for Linux/Unix');
		$developmentSubsection->setShortfaq('');
		$developmentSubsection->setRewrite('development');
		$manager->persist($developmentSubsection);
		
		$serverSubsection = new Subsection();
		$serverSubsection->setName('Rulinux.net');
		$serverSubsection->setDescription('comment about work of server rulinux.net');
		$serverSubsection->setShortfaq('');
		$serverSubsection->setRewrite('rulinux_net');
		$manager->persist($serverSubsection);
		
		$securitySubsection = new Subsection();
		$securitySubsection->setName('Security');
		$securitySubsection->setDescription('security');
		$securitySubsection->setShortfaq('');
		$securitySubsection->setRewrite('security');
		$manager->persist($securitySubsection);
		
		$linuxHardwareSubsection = new Subsection();
		$linuxHardwareSubsection->setName('Linux-hardware');
		$linuxHardwareSubsection->setDescription('hardware & Linux');
		$linuxHardwareSubsection->setShortfaq('');
		$linuxHardwareSubsection->setRewrite('linux_hardware');
		$manager->persist($linuxHardwareSubsection);
		
		$talksSubsection = new Subsection();
		$talksSubsection->setName('Talks');
		$talksSubsection->setDescription('non technical talks about Linux/Unix');
		$talksSubsection->setShortfaq('');
		$talksSubsection->setRewrite('talks');
		$manager->persist($talksSubsection);
		
		$jobSubsection = new Subsection();
		$jobSubsection->setName('Job');
		$jobSubsection->setDescription('search for and supply of work related to Linux/Unix');
		$jobSubsection->setShortfaq('');
		$jobSubsection->setRewrite('job');
		$manager->persist($jobSubsection);
		
		$gamesSubsection = new Subsection();
		$gamesSubsection->setName('Games');
		$gamesSubsection->setDescription('games for Linux/Unix');
		$gamesSubsection->setShortfaq('');
		$gamesSubsection->setRewrite('games');
		$manager->persist($gamesSubsection);
		
		$webDevelopmentSubsection = new Subsection();
		$webDevelopmentSubsection->setName('Web-development');
		$webDevelopmentSubsection->setDescription('web development');
		$webDevelopmentSubsection->setShortfaq('');
		$webDevelopmentSubsection->setRewrite('web_development');
		$manager->persist($webDevelopmentSubsection);
		
		$trashSubsection = new Subsection();
		$trashSubsection->setName('Trash');
		$trashSubsection->setDescription('trash section');
		$trashSubsection->setShortfaq('');
		$trashSubsection->setRewrite('trash');
		$manager->persist($trashSubsection);
		
		$manager->flush();
	}
}