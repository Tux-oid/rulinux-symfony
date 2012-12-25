<?php
/**
 * @author Tux-oid
 */

namespace RL\SecurityBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RL\SecurityBundle\Entity\User;
use RL\SecurityBundle\Entity\Group;
use RL\MainBundle\Entity\Settings;
use RL\MainBundle\Entity\TexMark;
use RL\ThemesBundle\Entity\Theme;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class FixtureLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
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
        $ubertechnoTheme->setPath('RLThemesBundle:Default');
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
        $manager->flush();
    }
}
