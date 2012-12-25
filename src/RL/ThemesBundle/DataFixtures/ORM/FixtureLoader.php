<?php
/**
 * @author Tux-oid
 */

namespace RL\ThemesBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RL\ThemesBundle\Entity\Theme;

class FixtureLoader implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param  \RL\ThemesBundle\Entity\Doctrine\Common\Persistence\ObjectManager|\RL\ThemesBundle\Entity\ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $whiteTheme = new Theme();
        $whiteTheme->setName('simple-white');
        $whiteTheme->setDescription('White theme with rounded corners');
        $whiteTheme->setPath('RLThemesBundle:White');
        $whiteTheme->setDirectory('White');
        $manager->persist($whiteTheme);

        $cozyGreenTheme = new Theme();
        $cozyGreenTheme->setName('Cozy-Green');
        $cozyGreenTheme->setDescription('Green theme copied from theme for IPB');
        $cozyGreenTheme->setPath('RLThemesBundle:CozyGreen');
        $cozyGreenTheme->setDirectory('CozyGreen');
        $manager->persist($cozyGreenTheme);

        $manager->flush();
    }
}
