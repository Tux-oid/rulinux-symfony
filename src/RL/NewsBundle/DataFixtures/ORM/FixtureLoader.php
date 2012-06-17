<?php
/**
 * @author Tux-oid 
 */

namespace RL\NewsBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RL\MainBundle\Entity\Section;
use RL\ForumBundle\Entity\Subsection;


class FixtureLoader implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$newsSection = new Section();
		$newsSection->setName('News');
		$newsSection->setDescription('News about FOSS');
		$newsSection->setRewrite('news');
		$newsSection->setBundle('RLNewsBundle');
		$manager->persist($newsSection);
	}
}
?>
