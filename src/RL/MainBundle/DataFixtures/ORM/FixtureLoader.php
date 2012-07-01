<?php
/**
 * @author Tux-oid
 */

namespace RL\MainBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RL\SecurityBundle\Entity\Settings;
use RL\MainBundle\Entity\Link;

class FixtureLoader implements FixtureInterface
{

	/**
	 * Load data fixtures with the passed EntityManager
	 *
	 * @param Doctrine\Common\Persistence\ObjectManager $manager
	 */
	function load(ObjectManager $manager)
	{
		$rulesTitle = 'Rules of Rulinux.net';
		$rulesTitleSetting = new Settings();
		$rulesTitleSetting->setName('rulesTitle');
		$rulesTitleSetting->setValue($rulesTitle);
		$manager->persist($rulesTitleSetting);

		$rulesText = '<p style="margin-bottom: 0cm;">Rulinux is a free resource about Unix systems and not . </p>
				<p style="margin-bottom: 0cm;">The rules are designed to maintain an adequate discussion of the given topic, and are advisory in nature with the exception of one item: Do not posting child porn and links to it.</p>
				<p style="margin-bottom: 0cm;">Do not recomended :<br> </p>
				<p style="margin-bottom: 0cm;">
				<ol>
				<li>Use filthy language in username
				<li>Use more than one active account
				<li>Use the same account collectively, except for the account anonymous
				<li>Write software for authomatic posting without the knowledge of the administration of the resource
				<li>Posting messages with binary data encoded to text message, for example base64
				<li>Posting messages with filthy language
				<li>Posting messages with links to malicious software
				<li>Posting messages with porn or links to porno sites
				<li>Posting messages with spam
				<li>Posting messages with flood
				</ol>
				</p><br>';
		$rulesTextSetting = new Settings();
		$rulesTextSetting->setName('rulesText');
		$rulesTextSetting->setValue($rulesText);
		$manager->persist($rulesTextSetting);

		$gpLink = new Link();
		$gpLink->setName('GNU Planet');
		$gpLink->setLink('http://gnuplanet.org');
		$manager->persist($gpLink);

		$opennetLink = new Link();
		$opennetLink->setName('OpenNET');
		$opennetLink->setLink('http://opennet.ru');
		$manager->persist($opennetLink);

		$lorLink = new Link();
		$lorLink->setName('linux.org.ru');
		$lorLink->setLink('http://linux.org.ru');
		$manager->persist($lorLink);

		$lolksLink = new Link();
		$lolksLink->setName('lolks');
		$lolksLink->setLink('http://lolks.ru');
		$manager->persist($lolksLink);

		$manager->flush();
	}
}
