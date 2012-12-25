<?php
/**
 * @author Tux-oid
 */

namespace RL\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PMP;

require_once __DIR__.'/../../../../vendor/GeSHi/GeSHi/src/geshi.php';

/**
 * @ORM\Entity
 * @ORM\Table(name="marks")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="content_type", type="string", length=20)
 * @ORM\DiscriminatorMap({"mark"="Mark"})
 */
abstract class Mark
{
	/**
	 * @ORM\Id
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\Column(name="name", type="string", length=256)
	 */
	protected $name;
	/**
	 * @ORM\Column(name="description", type="text", unique=true, nullable=false)
	 */
	protected $description;
	/**
	 * @ORM\OneToMany(targetEntity="RL\SecurityBundle\Entity\User", mappedBy="mark")
	 */
	protected $users;

	public function __construct()
	{
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Add users
	 *
	 * @param \RL\SecurityBundle\Entity\User $users
	 */
	public function addUser(\RL\SecurityBundle\Entity\User $users)
	{
		$this->users[] = $users;
	}

	/**
	 * Get users
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getUsers()
	{
		return $this->users;
	}

	abstract public function render($string);

	public function makeFormula($string)
	{
		$text = '<m>' . $string . '</m>';
		$size = 10;
		$pathToImg = '/web/bundles/rlmain/images/formulas/'; //TODO:save path to config file
		$phpMathPublisher = new \PMP\PhpMathPublisher();
		$str = $phpMathPublisher->mathfilter($text, $size, $pathToImg);
		return $str;
	}

	public function highlight($code, $lang)
	{
		if(empty($lang))
			$lang = 'c';
		$path = $_SERVER["DOCUMENT_ROOT"] . 'vendor/geshi/lib/Geshi/src/geshi'; //TODO:save path to config file
		$geshi = new \Geshi_GeSHi($code, $lang);
		$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 1);
		$code = geshi_highlight($code, $lang, $path, true);
		return $code;
	}

	public function getHighlightedLanguages()
	{
		$geshi = new \GeSHi('', '');
		$languages = $geshi->get_supported_languages();
		asort($languages);
		return $languages;
	}
}
