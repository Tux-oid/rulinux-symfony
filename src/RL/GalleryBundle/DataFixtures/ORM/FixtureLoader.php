<?php
/**
 * @author Tux-oid 
 */

namespace RL\GalleryBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RL\MainBundle\Entity\Section;
use RL\GalleryBundle\Entity\Subsection;


class FixtureLoader implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$gallerySection = new Section();
		$gallerySection->setName('Gallery');
		$gallerySection->setDescription('Images related to FOSS');
		$gallerySection->setRewrite('gallery');
		$gallerySection->setBundle('RLGalleryBundle');
		$gallerySection->setBundleNamespace('RL\GalleryBundle');
		$manager->persist($gallerySection);
		
		$screenshotsSubsection = new Subsection();
		$screenshotsSubsection->setName('Screenshots');
		$screenshotsSubsection->setDescription('Screenshots');
		$screenshotsSubsection->setRewrite('shceenshots');
		$screenshotsSubsection->setSection($gallerySection);
		$screenshotsSubsection->setShortfaq('');
		$manager->persist($screenshotsSubsection);
		
		$devicesSubsection = new Subsection();
		$devicesSubsection->setName('Devices');
		$devicesSubsection->setDescription('Photos of user\'s devices');
		$devicesSubsection->setRewrite('devices');
		$devicesSubsection->setSection($gallerySection);
		$devicesSubsection->setShortfaq('');
		$manager->persist($devicesSubsection);
		
		$workplacesSubsection = new Subsection();
		$workplacesSubsection->setName('Workplaces');
		$workplacesSubsection->setDescription('Photos of user\'s workplaces');
		$workplacesSubsection->setRewrite('workplaces');
		$workplacesSubsection->setSection($gallerySection);
		$workplacesSubsection->setShortfaq('');
		$manager->persist($workplacesSubsection);
		
		$otherSubsection = new Subsection();
		$otherSubsection->setName('Other');
		$otherSubsection->setDescription('Other images');
		$otherSubsection->setRewrite('other');
		$otherSubsection->setSection($gallerySection);
		$otherSubsection->setShortfaq('');
		$manager->persist($otherSubsection);
		
		$manager->flush();
	}
}
?>
