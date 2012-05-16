<?php
namespace RL\SecurityBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="RL\SecurityBundle\Entity\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements AdvancedUserInterface/*, Serializable*/
{
	/**
	 * @ORM\Id()
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\ManyToMany(targetEntity="Group")
	 * @ORM\JoinTable(name="user_group",
	 *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
	 * )
	 */
	protected $groups;
	/**
	 * @ORM\Column(name="nick", type="string", length=100, unique="true", nullable="false")
	 * @Assert\NotBlank()
	 * @Assert\Regex("#([a-zA-Z0-9\_\-\/\.]{2,})$#")
	 */
	protected $username;
	/**
	* @ORM\Column(type="string", length=32)
	*/
	private $salt;
	/**
	 * @ORM\Column(name="password", type="string", length=255)
	 * @Assert\NotBlank()
	 */
	protected $password;
	/**
	 * @ORM\Column(name="name", type="string", length=255, nullable="true")
	 */
	protected $name;
	/**
	 * @ORM\Column(name="lastname", type="string", length=255, nullable="true")
	 */
	protected $lastname;
	/**
	 * @ORM\Column(name="country", type="string", length=512, nullable="true")
	 * @Assert\Country
	 */
	protected $country;
	/**
	 * @ORM\Column(name="city", type="string", length=512, nullable="true")
	 */
	protected $city;
	/**
	 * @ORM\Column(name="photo", type="string", length=512, nullable="true")
	 * * @Assert\Image
	 */
	protected $photo;
	/**
	 * @ORM\Column(name="birthday", type="datetime", nullable="true")
	 * * @Assert\DateTime
	 */
	protected $birthday;
	/**
	 * @ORM\Column(name="gender", type="boolean")
	 */
	protected $gender;
	/**
	 * @ORM\Column(name="additional", type="text", nullable="true")
	 */
	protected $additional;
	/**
	 * @ORM\Column(name="raw_additional", type="text", nullable="true")
	 */
	protected $additionalRaw;
	/**
	 * @ORM\Column(name="email", type="string", length=512, unique="true", nullable="false")
	 * * @Assert\Email
	 */
	protected $email;
	/**
	 * @ORM\Column(name="im", type="string", length=512, nullable="true")
	 * @Assert\Email
	 */
	protected $im;
	/**
	 * @ORM\Column(name="register_date", type="datetime")
	 * @Assert\DateTime
	 */
	protected $registrationDate;
	/**
	 * @ORM\Column(name="last_visit", type="datetime")
	 * @Assert\DateTime
	 */
	protected $lastVisitDate;
	/**
	 * @ORM\Column(name="banned", type="boolean")
	 */
	protected $active;
	/**
	 * @ORM\Column(name="captcha", type="integer")
	 */
	protected $captchaLevel;
	/**
	 * @ORM\Column(name="openid", type="string", length=1024, nullable="true")
	 */
	protected $openid;
	/**
	 * @ORM\Column(name="question", type="string", length="1024", nullable="false")
	 */
	protected $question;
	/**
	 * @ORM\Column(name="answer", type="string", length="1024", nullable="false")
	 */
	protected $answer;
	//Settings
	/**
	 * @ORM\Column(name="language", type="text")
	 */
	protected $language;
	/**
	 * @ORM\Column(name="blocks", type="array")
	 * @Assert\Language
	 */
	protected $blocks;
	/**
	 * @ORM\Column(name="theme", type="string", length=512)
	 */
	protected $theme;
	/**
	 * @ORM\Column(name="gmt", type="string", length=64)
	 */
	protected $gmt;
	/**
	 * @ORM\Column(name="filters", type="array")
	 */
	protected $filters;
	/**
	 * @ORM\ManyToOne(targetEntity="Mark", inversedBy="users")
	 * @ORM\JoinColumn(name="mark", referencedColumnName="id")
	 */
	protected $mark;
	/**
	 * @ORM\Column(name="sort_to", type="string", length=512)
	 */
	protected $sortingType;
	/**
	 * @ORM\Column(name="news_on_page", type="integer")
	 */
	protected $newsOnPage;
	/**
	 * @ORM\Column(name="comments_on_page", type="integer")
	 */
	protected $commentsOnPage;
	/**
	 * @ORM\Column(name="threads_on_page", type="integer")
	 */
	protected $threadsOnPage;
	/**
	 * @ORM\Column(name="show_email", type="boolean")
	 */
	protected $showEmail;
	/**
	 * @ORM\Column(name="show_im", type="boolean")
	 */
	protected $showIm;
	/**
	 * @ORM\Column(name="show_avatars", type="boolean")
	 */
	protected $showAvatars;
	/**
	 * @ORM\Column(name="show_ua", type="boolean")
	 */
	protected $showUa;
	/**
	 * @ORM\Column(name="show_resp", type="boolean")
	 */
	protected $showResp;
	 public function __construct()
	{
		$this->active = true;
		$this->salt = md5(uniqid(null, true));
		$this->groups = new ArrayCollection();
	}

	public function isAccountNonExpired()
	{
		return true;
	}

	public function isAccountNonLocked()
	{
		return true;
	}

	public function isCredentialsNonExpired()
	{
		return true;
	}

	public function isEnabled()
	{
		return $this->active;
	}
	/**
	* @inheritDoc
	*/
	public function getUsername()
	{
		return $this->username;
	}
	/**
	* @inheritDoc
	*/
	public function getSalt()
	{
		return $this->salt;
	}
	/**
	* @inheritDoc
	*/
	public function getPassword()
	{
		return $this->password;
	}
	/**
	* @inheritDoc
	*/
	public function getRoles()
	{
		return $this->groups->toArray();
	}
	/**
	* @inheritDoc
	*/
	public function eraseCredentials()
	{
		
	}
	/**
	* @inheritDoc
	*/
	public function equals(UserInterface $user)
	{
		return $this->username === $user->getUsername();
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
	* Set username
	*
	* @param string $username
	*/
	public function setUsername($username)
	{
		$this->username = $username;
	}
	/**
	* Set salt
	*
	* @param string $salt
	*/
	public function setSalt($salt)
	{
		$this->salt = $salt;
	}
	/**
	* Set password
	*
	* @param string $password
	*/
	public function setPassword($password)
	{
		$this->password = $password;
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
	* Set lastname
	*
	* @param string $lastname
	*/
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;
	}
	/**
	* Get lastname
	*
	* @return string
	*/
	public function getLastname()
	{
		return $this->lastname;
	}
	/**
	* Set country
	*
	* @param string $country
	*/
	public function setCountry($country)
	{
		$this->country = $country;
	}
	/**
	* Get country
	*
	* @return string
	*/
	public function getCountry()
	{
		return $this->country;
	}
	/**
	* Set city
	*
	* @param string $city
	*/
	public function setCity($city)
	{
		$this->city = $city;
	}
	/**
	* Get city
	*
	* @return string
	*/
	public function getCity()
	{
		return $this->city;
	}
	/**
	* Set photo
	*
	* @param string $photo
	*/
	public function setPhoto($photo)
	{
		$this->photo = $photo;
	}
	/**
	* Get photo
	*
	* @return string
	*/
	public function getPhoto()
	{
		return $this->photo;
	}
	/**
	* Set birthday
	*
	* @param datetime $birthday
	*/
	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;
	}
	/**
	* Get birthday
	*
	* @return datetime
	*/
	public function getBirthday()
	{
		return $this->birthday;
	}
	/**
	* Set gender
	*
	* @param boolean $gender
	*/
	public function setGender($gender)
	{
		$this->gender = $gender;
	}
	/**
	* Get gender
	*
	* @return boolean
	*/
	public function getGender()
	{
		return $this->gender;
	}
	/**
	* Set additional
	*
	* @param text $additional
	*/
	public function setAdditional($additional)
	{
		$this->additional = $additional;
	}
	/**
	* Get additional
	*
	* @return text
	*/
	public function getAdditional()
	{
		return $this->additional;
	}
	/**
	* Set additionalRaw
	*
	* @param text $additionalRaw
	*/
	public function setAdditionalRaw($additionalRaw)
	{
		$this->additionalRaw = $additionalRaw;
	}
	/**
	* Get additionalRaw
	*
	* @return text
	*/
	public function getAdditionalRaw()
	{
		return $this->additionalRaw;
	}
	/**
	* Set email
	*
	* @param string $email
	*/
	public function setEmail($email)
	{
		$this->email = $email;
	}
	/**
	* Get email
	*
	* @return string
	*/
	public function getEmail()
	{
		return $this->email;
	}
	/**
	* Set im
	*
	* @param string $im
	*/
	public function setIm($im)
	{
		$this->im = $im;
	}
	/**
	* Get im
	*
	* @return string
	*/
	public function getIm()
	{
		return $this->im;
	}
	/**
	* Set registrationDate
	*
	* @param datetime $registrationDate
	*/
	public function setRegistrationDate($registrationDate)
	{
		$this->registrationDate = $registrationDate;
	}
	/**
	* Get registrationDate
	*
	* @return datetime
	*/
	public function getRegistrationDate()
	{
		return $this->registrationDate;
	}
	/**
	* Set lastVisitDate
	*
	* @param datetime $lastVisitDate
	*/
	public function setLastVisitDate($lastVisitDate)
	{
		$this->lastVisitDate = $lastVisitDate;
	}
	/**
	* Get lastVisitDate
	*
	* @return datetime
	*/
	public function getLastVisitDate()
	{
		return $this->lastVisitDate;
	}
	/**
	* Set captchaLevel
	*
	* @param integer $captchaLevel
	*/
	public function setCaptchaLevel($captchaLevel)
	{
		$this->captchaLevel = $captchaLevel;
	}
	/**
	* Get captchaLevel
	*
	* @return integer
	*/
	public function getCaptchaLevel()
	{
		return $this->captchaLevel;
	}
	/**
	* Set openid
	*
	* @param string $openid
	*/
	public function setOpenid($openid)
	{
		$this->openid = $openid;
	}
	/**
	* Get openid
	*
	* @return string
	*/
	public function getOpenid()
	{
		return $this->openid;
	}
	/**
	* Set blocks
	*
	* @param array $blocks
	*/
	public function setBlocks($blocks)
	{
		$this->blocks = $blocks;
	}
	/**
	* Get blocks
	*
	* @return array
	*/
	public function getBlocks()
	{
		return $this->blocks;
	}
	/**
	* Set theme
	*
	* @param string $theme
	*/
	public function setTheme($theme)
	{
		$this->theme = $theme;
	}
	/**
	* Get theme
	*
	* @return string
	*/
	public function getTheme()
	{
		return $this->theme;
	}
	/**
	* Set gmt
	*
	* @param string $gmt
	*/
	public function setGmt($gmt)
	{
		$this->gmt = $gmt;
	}
	/**
	* Get gmt
	*
	* @return string
	*/
	public function getGmt()
	{
		return $this->gmt;
	}
	/**
	* Set filters
	*
	* @param array $filters
	*/
	public function setFilters($filters)
	{
		$this->filters = $filters;
	}
	/**
	* Get filters
	*
	* @return array
	*/
	public function getFilters()
	{
		return $this->filters;
	}
	/**
	* Set sortingType
	*
	* @param string $sortingType
	*/
	public function setSortingType($sortingType)
	{
		$this->sortingType = $sortingType;
	}
	/**
	* Get sortingType
	*
	* @return string
	*/
	public function getSortingType()
	{
		return $this->sortingType;
	}
	/**
	* Set newsOnPage
	*
	* @param integer $newsOnPage
	*/
	public function setNewsOnPage($newsOnPage)
	{
		$this->newsOnPage = $newsOnPage;
	}
	/**
	* Get newsOnPage
	*
	* @return integer
	*/
	public function getNewsOnPage()
	{
		return $this->newsOnPage;
	}
	/**
	* Set commentsOnPage
	*
	* @param integer $commentsOnPage
	*/
	public function setCommentsOnPage($commentsOnPage)
	{
		$this->commentsOnPage = $commentsOnPage;
	}
	/**
	* Get commentsOnPage
	*
	* @return integer
	*/
	public function getCommentsOnPage()
	{
		return $this->commentsOnPage;
	}
	/**
	* Set threadsOnPage
	*
	* @param integer $threadsOnPage
	*/
	public function setThreadsOnPage($threadsOnPage)
	{
		$this->threadsOnPage = $threadsOnPage;
	}
	/**
	* Get threadsOnPage
	*
	* @return integer
	*/
	public function getThreadsOnPage()
	{
		return $this->threadsOnPage;
	}
	/**
	* Set showEmail
	*
	* @param boolean $showEmail
	*/
	public function setShowEmail($showEmail)
	{
		$this->showEmail = $showEmail;
	}
	/**
	* Get showEmail
	*
	* @return boolean
	*/
	public function getShowEmail()
	{
		return $this->showEmail;
	}
	/**
	* Set showIm
	*
	* @param boolean $showIm
	*/
	public function setShowIm($showIm)
	{
		$this->showIm = $showIm;
	}
	/**
	* Get showIm
	*
	* @return boolean
	*/
	public function getShowIm()
	{
		return $this->showIm;
	}
	/**
	* Set showAvatars
	*
	* @param boolean $showAvatars
	*/
	public function setShowAvatars($showAvatars)
	{
		$this->showAvatars = $showAvatars;
	}
	/**
	* Get showAvatars
	*
	* @return boolean
	*/
	public function getShowAvatars()
	{
		return $this->showAvatars;
	}
	/**
	* Set showUa
	*
	* @param boolean $showUa
	*/
	public function setShowUa($showUa)
	{
		$this->showUa = $showUa;
	}
	/**
	* Get showUa
	*
	* @return boolean
	*/
	public function getShowUa()
	{
		return $this->showUa;
	}
	/**
	* Set showResp
	*
	* @param boolean $showResp
	*/
	public function setShowResp($showResp)
	{
		$this->showResp = $showResp;
	}
	/**
	* Get showResp
	*
	* @return boolean
	*/
	public function getShowResp()
	{
		return $this->showResp;
	}
	/**
	* Set mark
	*
	* @param RL\SecurityBundle\Entity\Mark $mark
	*/
	public function setMark(\RL\SecurityBundle\Entity\Mark $mark)
	{
		$this->mark = $mark;
	}
	/**
	* Get mark
	*
	* @return RL\SecurityBundle\Entity\Mark
	*/
	public function getMark()
	{
		return $this->mark;
	}
	/**
	* Add groups
	*
	* @param RL\SecurityBundle\Entity\Group $groups
	*/
	public function addGroup(\RL\SecurityBundle\Entity\Group $groups)
	{
		$this->groups[] = $groups;
	}
	/**
	* Get groups
	*
	* @return Doctrine\Common\Collections\Collection
	*/
	public function getGroups()
	{
		return $this->groups;
	}
	/**
	* Set language
	*
	* @param text $language
	*/
	public function setLanguage($language)
	{
		$this->language = $language;
	}
	/**
	* Get language
	*
	* @return text
	*/
	public function getLanguage()
	{
		return $this->language;
	}
	/**
	* Set active
	*
	* @param boolean $active
	*/
	public function setActive($active)
	{
		$this->active = $active;
	}
	/**
	* Get active
	*
	* @return boolean
	*/
	public function getActive()
	{
		return $this->active;
	}
	/**
	* Set question
	*
	* @param string $question
	*/
	public function setQuestion($question)
	{
		$this->question = $question;
	}
	/**
	* Get question
	*
	* @return string
	*/
	public function getQuestion()
	{
		return $this->question;
	}
	/**
	* Set answer
	*
	* @param string $answer
	*/
	public function setAnswer($answer)
	{
		$this->answer = $answer;
	}
	/**
	* Get answer
	*
	* @return string
	*/
	public function getAnswer()
	{
		return $this->answer;
	}
}