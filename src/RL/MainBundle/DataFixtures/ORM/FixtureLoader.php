<?php
/**
 * @author Tux-oid
 */

namespace RL\MainBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RL\MainBundle\Entity\Settings;
use RL\MainBundle\Entity\Link;
use RL\MainBundle\Entity\BbCode;
use RL\MainBundle\Entity\BaseHtml;
use RL\MainBundle\Entity\WakabaMark;

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

		$bbCode = new BbCode();
		$bbCode->setName('BBCode');
		$bbCode->setDescription('[b]<b>Bold text</b>[/b]<br>
		[i]<i>Italic text</i>[/i]<br>
		[u]<u>Underlined text</u>[/u]<br>
		[s]<s>Strikethrough text</s>[/s]<br>
		[sub]<sub>Subscript text</sub>[/sub]<br>
		[sup]<sup>Raised text</sup>[/sup]<br>
		[quote]Quote[/quote]<br>
		[list]<br>
		[*] First element of marked list<br>
		[*] Second element of marked list<br>
		[*] Third element of marked list<br>
		[/list]<br>
		[num]<br>
		[*] First element of enumerated list<br>
		[*] Second element of enumerated list<br>
		[*] Third element of enumerated list<br>
		[/num]<br>
		[p align=left|right|center]<br>
		left|right|center alignment<br>
		[/p]<br>
		[code=lang]<br>
		Highlighted code<br>
		[/code]<br>
		[img align=left|right|middle|top|bottom]path to image[/img]<br>
		[user]user[/user]<br>
		[url=link]text[/url] or [url]link[/url]<br>
		[spoiler]spoiler[/spoiler]<br>
		[math]formula[/math]');
		$manager->persist($bbCode);

		$wakabaMark = new WakabaMark();
		$wakabaMark->setName('extWakaba-Mark');
		$wakabaMark->setDescription('**<b>Bold text</b>**<br>
		__<b>Bold text</b>__<br>
		*<i>Italic text</i>*<br>
		_<i>Italic text</i>_<br>
		$<u>Underlined text</u>$<br>
		<s>Strikethrough text</s>^W<br>
		#<sup>Raised text</sup>#<br>
		##<sub>Subscript text</sub>##<br>
		`Quote`<br>
		* First element of marked list<br>
		* Second element of marked list<br>
		* Third element of marked list<br>
		+ First element of marked list<br>
		+ Second element of marked list<br>
		+ Third element of marked list<br>
		- First element of marked list<br>
		- Second element of marked list<br>
		- Third element of marked list<br>
		1. First element of enumerated list<br>
		1. Second element of enumerated list<br>
		1. Third element of enumerated list<br>
		<<left alignment<<<br>
		>>right alignment>><br>
		<>center alignment<><br>
		``@lang@<br>
		Highlighted code``<br>
		^user^<br>
		~@text@URL~<br>
		~URL~<br>
		~~@left|right|middle|top|bottom@path to image~~<br>
		%%spoiler%%<br>
		{{formula}}');
		$manager->persist($wakabaMark);

		$baseHTML = new BaseHtml();
		$baseHTML->setName('Base HTML');
		$baseHTML->setDescription('&lt;br&gt; line break<br>
		&lt;b&gt;<b>Bold text</b>&lt;/b&gt;<br>
		&lt;i&gt;<i>Italic text</i>&lt;/i&gt;<br>
		&lt;u&gt;<u>Underlined text</u>&lt;/u&gt;<br>
		&lt;s&gt;<s>Strikethrough text</s>&lt;/s&gt;<br>
		&lt;sub&gt;<sup>Raised text</sup>&lt;/sub&gt;<br>
		&lt;sup&gt;<sub>Subscript text</sub>&lt;/sup&gt;<br>
		&lt;q&gt;Quote&lt;/q&gt;<br>
		&lt;ul&gt;<br>
		&lt;li&gt;First element of marked list<br>
		&lt;li&gt;Second element of marked list<br>
		&lt;li&gt;Third element of marked listа<br>
		&lt;/ul&gt;<br>
		&lt;ol&gt;<br>
		&lt;li&gt;First element of enumerated list<br>
		&lt;li&gt;Second element of enumerated list<br>
		&lt;li&gt;Third element of enumerated list<br>
		&lt;/ol&gt;<br>
		&lt;p align="left|right|center"&gt;<br>
		left|right|center alignment<br>
		&lt;/p&gt;<br>
		&lt;code lang="lang"&gt;Highlighted code&lt;/code&gt;<br>
		&lt;img align="left|right|middle|top|bottom" src="path to image"&gt;<br>
		&lt;span class="user"&gt;user&lt;/span&gt;<br>
		&lt;a href="URL"&gt;text&lt;/a&gt;<br>
		&lt;span class="spoiler"&gt;spoiler&lt;/span&gt;<br>
		&lt;m&gt;formula&lt;/m&gt;');
		$manager->persist($baseHTML);

		$manager->flush();
	}
}
