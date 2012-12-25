<?php
/**
 * @author Tux-oid
 */

namespace RL\NewsBundle\DataFixtures\ORM;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use RL\MainBundle\Entity\Section;
use RL\NewsBundle\Entity\Subsection;

class FixtureLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $newsSection = new Section();
        $newsSection->setName('News');
        $newsSection->setDescription('News about FOSS');
        $newsSection->setRewrite('news');
        $newsSection->setBundle('RLNewsBundle');
        $newsSection->setBundleNamespace('RL\NewsBundle');
        $manager->persist($newsSection);

        $documentationSubsection = new Subsection();
        $documentationSubsection->setName('Documentation');
        $documentationSubsection->setDescription('News subsection about documentation');
        $documentationSubsection->setRewrite('documentation');
        $documentationSubsection->setSection($newsSection);
        $documentationSubsection->setImage('docs.png');
        $documentationSubsection->setShortfaq('');
        $manager->persist($documentationSubsection);

        $linuxGeneralSubsection = new Subsection();
        $linuxGeneralSubsection->setName('Linux General');
        $linuxGeneralSubsection->setDescription('News about GNU\Linux');
        $linuxGeneralSubsection->setRewrite('linux_general');
        $linuxGeneralSubsection->setSection($newsSection);
        $linuxGeneralSubsection->setImage('general.png');
        $linuxGeneralSubsection->setShortfaq('');
        $manager->persist($linuxGeneralSubsection);

        $opensourceSubsection = new Subsection();
        $opensourceSubsection->setName('Opensource');
        $opensourceSubsection->setDescription('News about FOSS');
        $opensourceSubsection->setRewrite('opensource');
        $opensourceSubsection->setSection($newsSection);
        $opensourceSubsection->setImage('opensource.png');
        $opensourceSubsection->setShortfaq('');
        $manager->persist($opensourceSubsection);

        $mozillaSubsection = new Subsection();
        $mozillaSubsection->setName('Mozilla');
        $mozillaSubsection->setDescription('News about Mozilla Foundation and their products');
        $mozillaSubsection->setRewrite('mozilla');
        $mozillaSubsection->setSection($newsSection);
        $mozillaSubsection->setImage('mozilla.png');
        $mozillaSubsection->setShortfaq('');
        $manager->persist($mozillaSubsection);

        $redHatSubsection = new Subsection();
        $redHatSubsection->setName('RedHat');
        $redHatSubsection->setDescription('News about Red Hat and their products');
        $redHatSubsection->setRewrite('redhat');
        $redHatSubsection->setSection($newsSection);
        $redHatSubsection->setImage('redhat.png');
        $redHatSubsection->setShortfaq('');
        $manager->persist($redHatSubsection);

        $javaSubsection = new Subsection();
        $javaSubsection->setName('Java');
        $javaSubsection->setDescription('News related to the Java language and its associated items');
        $javaSubsection->setRewrite('java');
        $javaSubsection->setSection($newsSection);
        $javaSubsection->setImage('java.png');
        $javaSubsection->setShortfaq('');
        $manager->persist($javaSubsection);

        $gnomeSubsection = new Subsection();
        $gnomeSubsection->setName('GNOME');
        $gnomeSubsection->setDescription('News about G.N.O.M.E. project');
        $gnomeSubsection->setRewrite('gnome');
        $gnomeSubsection->setSection($newsSection);
        $gnomeSubsection->setImage('gnome.png');
        $gnomeSubsection->setShortfaq('');
        $manager->persist($gnomeSubsection);

        $kdeSubsection = new Subsection();
        $kdeSubsection->setName('KDE');
        $kdeSubsection->setDescription('News about KDE SC and related technologies');
        $kdeSubsection->setRewrite('kde');
        $kdeSubsection->setSection($newsSection);
        $kdeSubsection->setImage('kde.png');
        $kdeSubsection->setShortfaq('');
        $manager->persist($kdeSubsection);

        $gnuSubsection = new Subsection();
        $gnuSubsection->setName('GNU');
        $gnuSubsection->setDescription('News about GNU project');
        $gnuSubsection->setRewrite('gnu');
        $gnuSubsection->setSection($newsSection);
        $gnuSubsection->setImage('gnu.png');
        $gnuSubsection->setShortfaq('');
        $manager->persist($gnuSubsection);

        $securitySubsection = new Subsection();
        $securitySubsection->setName('Security');
        $securitySubsection->setDescription('News about security');
        $securitySubsection->setRewrite('security');
        $securitySubsection->setSection($newsSection);
        $securitySubsection->setImage('security.png');
        $securitySubsection->setShortfaq('');
        $manager->persist($securitySubsection);

        $linuxInRussiaSubsection = new Subsection();
        $linuxInRussiaSubsection->setName('Linux in Russia');
        $linuxInRussiaSubsection->setDescription('News about the progress of GNU/Linux in our country');
        $linuxInRussiaSubsection->setRewrite('linux_in_russia');
        $linuxInRussiaSubsection->setSection($newsSection);
        $linuxInRussiaSubsection->setImage('linux_in_russia.png');
        $linuxInRussiaSubsection->setShortfaq('');
        $manager->persist($linuxInRussiaSubsection);

        $linuxKernelSubsection = new Subsection();
        $linuxKernelSubsection->setName('Linux kernel');
        $linuxKernelSubsection->setDescription('News about Linux kernel');
        $linuxKernelSubsection->setRewrite('linux_kernel');
        $linuxKernelSubsection->setSection($newsSection);
        $linuxKernelSubsection->setImage('kernel.png');
        $linuxKernelSubsection->setShortfaq('');
        $manager->persist($linuxKernelSubsection);

        $commercialSubsection = new Subsection();
        $commercialSubsection->setName('Commercial software');
        $commercialSubsection->setDescription('News about commercial software for *nix');
        $commercialSubsection->setRewrite('commercial');
        $commercialSubsection->setSection($newsSection);
        $commercialSubsection->setImage('commercial.png');
        $commercialSubsection->setShortfaq('');
        $manager->persist($commercialSubsection);

        $hardwareAndDriversSubsection = new Subsection();
        $hardwareAndDriversSubsection->setName('Hardware and Drivers');
        $hardwareAndDriversSubsection->setDescription('News about hardware and drivers');
        $hardwareAndDriversSubsection->setRewrite('hardware_and_drivers');
        $hardwareAndDriversSubsection->setSection($newsSection);
        $hardwareAndDriversSubsection->setImage('drivers.png');
        $hardwareAndDriversSubsection->setShortfaq('');
        $manager->persist($hardwareAndDriversSubsection);

        $bsdSubsection = new Subsection();
        $bsdSubsection->setName('BSD');
        $bsdSubsection->setDescription('News about BSD systems');
        $bsdSubsection->setRewrite('bsd');
        $bsdSubsection->setSection($newsSection);
        $bsdSubsection->setImage('bsd.png');
        $bsdSubsection->setShortfaq('');
        $manager->persist($bsdSubsection);

        $debianSubsection = new Subsection();
        $debianSubsection->setName('Debian');
        $debianSubsection->setDescription('News about Debian');
        $debianSubsection->setRewrite('debian');
        $debianSubsection->setSection($newsSection);
        $debianSubsection->setImage('debian.png');
        $debianSubsection->setShortfaq('');
        $manager->persist($debianSubsection);

        $openOfficeSubsection = new Subsection();
        $openOfficeSubsection->setName('Open/Libre Office');
        $openOfficeSubsection->setDescription('News about Open/Libre Office');
        $openOfficeSubsection->setRewrite('openoffice');
        $openOfficeSubsection->setSection($newsSection);
        $openOfficeSubsection->setImage('openoffice.png');
        $openOfficeSubsection->setShortfaq('');
        $manager->persist($openOfficeSubsection);

        $pdaSubsection = new Subsection();
        $pdaSubsection->setName('PDA');
        $pdaSubsection->setDescription('News about mobile applications');
        $pdaSubsection->setRewrite('pda');
        $pdaSubsection->setSection($newsSection);
        $pdaSubsection->setImage('pda.png');
        $pdaSubsection->setShortfaq('');
        $manager->persist($pdaSubsection);

        $scoSubsection = new Subsection();
        $scoSubsection->setName('SCO');
        $scoSubsection->setDescription('News about SCO');
        $scoSubsection->setRewrite('sco');
        $scoSubsection->setSection($newsSection);
        $scoSubsection->setImage('sco.png');
        $scoSubsection->setShortfaq('');
        $manager->persist($scoSubsection);

        $clustersSubsection = new Subsection();
        $clustersSubsection->setName('Clusters');
        $clustersSubsection->setDescription('News about clusters and TOP-500');
        $clustersSubsection->setRewrite('clusters');
        $clustersSubsection->setSection($newsSection);
        $clustersSubsection->setImage('clusters.png');
        $clustersSubsection->setShortfaq('');
        $manager->persist($clustersSubsection);

        $ubuntuSubsection = new Subsection();
        $ubuntuSubsection->setName('Ubuntu Linux');
        $ubuntuSubsection->setDescription('News about Ubuntu');
        $ubuntuSubsection->setRewrite('ubuntu');
        $ubuntuSubsection->setSection($newsSection);
        $ubuntuSubsection->setImage('ubuntu.png');
        $ubuntuSubsection->setShortfaq('');
        $manager->persist($ubuntuSubsection);

        $slackwareSubsection = new Subsection();
        $slackwareSubsection->setName('Slackware Linux');
        $slackwareSubsection->setDescription('News about Slackware');
        $slackwareSubsection->setRewrite('slackware');
        $slackwareSubsection->setSection($newsSection);
        $slackwareSubsection->setImage('slackware.png');
        $slackwareSubsection->setShortfaq('');
        $manager->persist($slackwareSubsection);

        $appleSubsection = new Subsection();
        $appleSubsection->setName('Apple');
        $appleSubsection->setDescription('News about Apple and their products');
        $appleSubsection->setRewrite('apple');
        $appleSubsection->setSection($newsSection);
        $appleSubsection->setImage('apple.png');
        $appleSubsection->setShortfaq('');
        $manager->persist($appleSubsection);

        $novellSubsection = new Subsection();
        $novellSubsection->setName('Novell');
        $novellSubsection->setDescription('News about Novell and their products');
        $novellSubsection->setRewrite('novell');
        $novellSubsection->setSection($newsSection);
        $novellSubsection->setImage('novell.png');
        $novellSubsection->setShortfaq('');
        $manager->persist($novellSubsection);

        $rulinuxSubsection = new Subsection();
        $rulinuxSubsection->setName('Rulinux.net');
        $rulinuxSubsection->setDescription('News about our site');
        $rulinuxSubsection->setRewrite('rulinux');
        $rulinuxSubsection->setSection($newsSection);
        $rulinuxSubsection->setImage('rulinux.png');
        $rulinuxSubsection->setShortfaq('');
        $manager->persist($rulinuxSubsection);

        $altSubsection = new Subsection();
        $altSubsection->setName('ALT');
        $altSubsection->setDescription('News about ALT Linux');
        $altSubsection->setRewrite('alt');
        $altSubsection->setSection($newsSection);
        $altSubsection->setImage('alt.png');
        $altSubsection->setShortfaq('');
        $manager->persist($altSubsection);

        $gtkSubsection = new Subsection();
        $gtkSubsection->setName('GTK');
        $gtkSubsection->setDescription('News about GTK');
        $gtkSubsection->setRewrite('gtk');
        $gtkSubsection->setSection($newsSection);
        $gtkSubsection->setImage('gtk.png');
        $gtkSubsection->setShortfaq('');
        $manager->persist($gtkSubsection);

        $qtSubsection = new Subsection();
        $qtSubsection->setName('Qt');
        $qtSubsection->setDescription('News about Qt');
        $qtSubsection->setRewrite('qt');
        $qtSubsection->setSection($newsSection);
        $qtSubsection->setImage('qt.png');
        $qtSubsection->setShortfaq('');
        $manager->persist($qtSubsection);

        $manager->flush();
    }
}
