<?php
/**
 * Copyright (c) 2008 - 2012, Peter Vasilevsky
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the RL nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL PETER VASILEVSKY BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

namespace RL\MainBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use RL\MainBundle\Entity\Settings;
use RL\MainBundle\Entity\Link;
use RL\MainBundle\Entity\BbCode;
use RL\MainBundle\Entity\BaseHtml;
use RL\MainBundle\Entity\WakabaMark;
use RL\MainBundle\Entity\TexMark;
use RL\MainBundle\Entity\Theme;
use RL\MainBundle\Entity\Group;
use RL\MainBundle\Entity\User;

/**
 * RL\MainBundle\DataFixtures\ORM\FixtureLoader
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class FixtureLoader implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $whiteTheme = new Theme();
        $whiteTheme->setName('simple-white');
        $whiteTheme->setDescription('White theme with rounded corners');
        $whiteTheme->setPath('RLMainBundle:White');
        $whiteTheme->setDirectory('White');
        $manager->persist($whiteTheme);

        $cozyGreenTheme = new Theme();
        $cozyGreenTheme->setName('Cozy-Green');
        $cozyGreenTheme->setDescription('Green theme copied from theme for IPB');
        $cozyGreenTheme->setPath('RLMainBundle:CozyGreen');
        $cozyGreenTheme->setDirectory('CozyGreen');
        $manager->persist($cozyGreenTheme);

        $anonymousRole = new Group();
        $anonymousRole->setName('ROLE_ANONYMOUS');
        $anonymousRole->setDescription('anonymouses');
        $manager->persist($anonymousRole);
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
        $manager->persist($adminRole);
        $captchaLevel = 0;
        $captchaSetting = new Settings();
        $captchaSetting->setName('captchaLevel');
        $captchaSetting->setValue($captchaLevel);
        $manager->persist($captchaSetting);
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

        $ubertechnoTheme = new Theme();
        $ubertechnoTheme->setName('Ubertechno');
        $ubertechnoTheme->setDescription('Acidic theme provided by the user Nosferatu');
        $ubertechnoTheme->setPath('RLMainBundle:Default');
        $ubertechnoTheme->setDirectory('Default');
        $manager->persist($ubertechnoTheme);

        $texMark = new TexMark();
        $texMark->setName('Tex-Mark');
        $texMark->setDescription('\b{<b>Bold text</b>[}<br>
                \i{<i>Italic text</i>}<br>
                \u{<u>Underlined text</u>}<br>
                \s{<s>Strikethrough text</s>}<br>
                \sub{<sub>Subscript text</sub>}<br>
                \sup{<sup>Raised text</sup>}<br>
                \quote{Quote}<br>
                \list{<br>
                {*} First element of marked list<br>
                {*} Second element of marked list<br>
                {*} Third element of marked list<br>
                }<br>
                \num{<br>
                {*} First element of enumerated list<br>
                {*} Second element of enumerated list<br>
                {*} Third element of enumerated list<br>
                }<br>
                \begin{flushleft|flushright|center}<br>
                eft|right|center alignment<br>
                \\\\ - a line break within a tag alignment<br>
                \end{flushleft|flushright|center}<br>
                \begin[lang]{highlight}<br>
                Highlighted code<br>
                \end{highlight}<br>
                \img[left|right|middle|top|bottom]{path to image}<br>
                \user{user}<br>
                \url[text]{link}<br>
                \url{link}<br>
                \br a line break<br>
                \\} Shielded brace<br>
                spoiler{spoiler}<br>
                \begin{math}formula\end{math}');
        $manager->persist($texMark);

        //adding anonymous
        $anonymous = new User();
        $anonymous->setUsername('anonymous');
        $anonymous->setName('unregistered users');
        $anonymous->setLastName('');
        $anonymous->setEmail('anonymous@example.com');
        $anonymous->setSalt(md5(time()));
        $anonymous->setAdditional('');
        $anonymous->setAdditionalRaw('');
        $anonymous->setBirthday(new \DateTime('now'));
        $anonymous->setGender(1);
        $anonymous->setRegistrationDate(new \DateTime('2009-02-12 14:42:51'));
        $anonymous->setLastVisitDate(new \DateTime('now'));
        $anonymous->setCaptchaLevel($captchaLevel);
        $anonymous->setTheme($ubertechnoTheme);
        $anonymous->setMark($texMark);
        $anonymous->setSortingType($sortingType);
        $anonymous->setNewsOnPage($newsOnPage);
        $anonymous->setThreadsOnPage($threadsOnPage);
        $anonymous->setCommentsOnPage($commentsOnPage);
        $anonymous->setShowEmail($showEmail);
        $anonymous->setShowIm($showIm);
        $anonymous->setShowAvatars($showAvatars);
        $anonymous->setShowUa($showUa);
        $anonymous->setShowResp($showResp);
        $anonymous->setLanguage($language);
        $anonymous->setQuestion('');
        $anonymous->setAnswer('');
        $anonymous->setGmt($gmt);
        $encoder = new MessageDigestPasswordEncoder('md5', false, 1);
        $password = $encoder->encodePassword('anonymous', $anonymous->getSalt());
        $anonymous->setPassword($password);
        $anonymous->setGroup($anonymousRole);
        $manager->persist($anonymous);
        //Adding admin
        $admin = new User();
        $admin->setUsername('Admin');
        $admin->setName('Site Administrator');
        $admin->setLastName('');
        $admin->setEmail('admin@example.com');
        $admin->setSalt(md5(time()));
        $admin->setAdditional('');
        $admin->setAdditionalRaw('');
        $admin->setBirthday(new \DateTime('now'));
        $admin->setGender(1);
        $admin->setRegistrationDate(new \DateTime('now'));
        $admin->setLastVisitDate(new \DateTime('now'));
        $admin->setCaptchaLevel($captchaLevel);
        $admin->setTheme($ubertechnoTheme);
        $admin->setMark($texMark);
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
        $admin->setQuestion('');
        $admin->setAnswer('');
        $admin->setGmt($gmt);
        $encoder = new MessageDigestPasswordEncoder('md5', false, 1);
        $password = $encoder->encodePassword('admin', $admin->getSalt());
        $admin->setPassword($password);
        $admin->setGroup($adminRole);
        $manager->persist($admin);

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
