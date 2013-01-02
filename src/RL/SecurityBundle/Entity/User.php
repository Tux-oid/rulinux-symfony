<?php
/**
 * @author Tux-oid
 */

namespace RL\SecurityBundle\Entity;
use RL\SecurityBundle\Security\User\RLUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use \RL\SecurityBundle\Entity\Group;
use RL\MainBundle\Entity\Mark;

/**
 * @ORM\Entity(repositoryClass="RL\SecurityBundle\Entity\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements RLUserInterface, \Serializable, EquatableInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="users")
     */
    protected $group;
    /**
     * @ORM\Column(name="nick", type="string", length=100, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex("#([a-zA-Z0-9\_\-\/\.]{2,})$#")
     */
    protected $username;
    /**
     * @ORM\Column(type="string", length=32)
     */
    protected  $salt;
    /**
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $password;
    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;
    /**
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    protected $lastname;
    /**
     * @ORM\Column(name="country", type="string", length=512, nullable=true)
     * @Assert\Country
     */
    protected $country;
    /**
     * @ORM\Column(name="city", type="string", length=512, nullable=true)
     */
    protected $city;
    /**
     * @ORM\Column(name="photo", type="string", length=512, nullable=true)
     * * @Assert\Image
     */
    protected $photo;
    /**
     * @ORM\Column(name="birthday", type="datetime", nullable=true)
     * * @Assert\DateTime
     */
    protected $birthday;
    /**
     * @ORM\Column(name="gender", type="boolean")
     */
    protected $gender;
    /**
     * @ORM\Column(name="additional", type="text", nullable=true)
     */
    protected $additional;
    /**
     * @ORM\Column(name="raw_additional", type="text", nullable=true)
     */
    protected $additionalRaw;
    /**
     * @ORM\Column(name="email", type="string", length=512, unique=true, nullable=false)
     * * @Assert\Email
     */
    protected $email;
    /**
     * @ORM\Column(name="im", type="string", length=512, nullable=true)
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
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active;
    /**
     * @ORM\Column(name="captcha", type="integer")
     */
    protected $captchaLevel;
    /**
     * @ORM\Column(name="openid", type="string", length=1024, nullable=true)
     */
    protected $openid;
    /**
     * @ORM\Column(name="question", type="string", length=1024, nullable=false)
     */
    protected $question;
    /**
     * @ORM\Column(name="answer", type="string", length=1024, nullable=false)
     */
    protected $answer;
    //Settings
    /**
     * @ORM\Column(name="language", type="text")
     * @Assert\NotBlank()
     * @Assert\Language
     */
    protected $language;
    /**
     * @ORM\Column(name="blocks", type="array")
     */
    protected $blocks;
    /**
     * @ORM\ManyToOne(targetEntity="RL\ThemesBundle\Entity\Theme")
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
     * @ORM\ManyToOne(targetEntity="RL\MainBundle\Entity\Mark", inversedBy="users")
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
    /**
     * @ORM\OneToMany(targetEntity="RL\ForumBundle\Entity\Message", mappedBy="user")
     */
    protected $comments;

    /**
     * @ORM\ManyToMany(targetEntity="RL\ForumBundle\Entity\Message", inversedBy="changedBy")
     */
    protected $editedComments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->active = true;
        $this->salt = md5(uniqid(null, true));
        $this->groups = new ArrayCollection();
    }
    public function serialize()
    {
        return serialize(
                array(
                    'id' => $this->id,
                    'username' => $this->username,
                    'group' => $this->group,
                    'salt' => $this->salt,
                    'password' => $this->password,
                    'name' => $this->name,
                    'lastname' => $this->lastname,
                    'country' => $this->country,
                    'city' => $this->city,
                    'photo' => $this->photo,
                    'birthday' => $this->birthday,
                    'gender' => $this->gender,
                    'additional' => $this->additional,
                    'additionalRaw' => $this->additionalRaw,
                    'email' => $this->email,
                    'im' => $this->im,
                    'registrationDate' => $this->registrationDate,
                    'lastVisitDate' => $this->lastVisitDate,
                    'active' => $this->active,
                    'captchaLevel' => $this->captchaLevel,
                    'openid' => $this->openid,
                    'question' => $this->question,
                    'answer' => $this->answer,
                    'language' => $this->language,
                    'blocks' => $this->blocks,
                    'theme' => $this->theme,
                    'gmt' => $this->gmt,
                    'filters' => $this->filters,
                    'mark' => $this->mark,
                    'sortingType' => $this->sortingType,
                    'newsOnPage' => $this->newsOnPage,
                    'commentsOnPage' => $this->commentsOnPage,
                    'threadsOnPage' => $this->threadsOnPage,
                    'showEmail' => $this->showEmail,
                    'showIm' => $this->showIm,
                    'showAvatars' => $this->showAvatars,
                    'showUa' => $this->showUa,
                    'showResp' => $this->showResp
                )
        );
    }
    public function unserialize($serializedData)
    {
        $unserializedData = unserialize($serializedData);
        $this->id = isset($unserializedData['id']) ? $unserializedData['id'] : null;
        $this->username = isset($unserializedData['username']) ? $unserializedData['username'] : null;
        $this->group = isset($unserializedData['group']) ? $unserializedData['group'] : null;
        $this->salt = isset($unserializedData['salt']) ? $unserializedData['salt'] : null;
        $this->password = isset($unserializedData['password']) ? $unserializedData['password'] : null;
        $this->name = isset($unserializedData['name']) ? $unserializedData['name'] : null;
        $this->lastname = isset($unserializedData['lastname']) ? $unserializedData['lastname'] : null;
        $this->country = isset($unserializedData['country']) ? $unserializedData['country'] : null;
        $this->city = isset($unserializedData['city']) ? $unserializedData['city'] : null;
        $this->photo = isset($unserializedData['photo']) ? $unserializedData['photo'] : null;
        $this->birthday = isset($unserializedData['birthday']) ? $unserializedData['birthday'] : null;
        $this->gender = isset($unserializedData['gender']) ? $unserializedData['gender'] : null;
        $this->additional = isset($unserializedData['additional']) ? $unserializedData['additional'] : null;
        $this->additionalRaw = isset($unserializedData['additionalRaw']) ? $unserializedData['additionalRaw'] : null;
        $this->email = isset($unserializedData['email']) ? $unserializedData['email'] : null;
        $this->im = isset($unserializedData['im']) ? $unserializedData['im'] : null;
        $this->registrationDate = isset($unserializedData['registrationDate']) ? $unserializedData['registrationDate'] : null;
        $this->lastVisitDate = isset($unserializedData['lastVisitDate']) ? $unserializedData['lastVisitDate'] : null;
        $this->active = isset($unserializedData['active']) ? $unserializedData['active'] : null;
        $this->captchaLevel = isset($unserializedData['captchaLevel']) ? $unserializedData['captchaLevel'] : null;
        $this->openid = isset($unserializedData['openid']) ? $unserializedData['openid'] : null;
        $this->question = isset($unserializedData['question']) ? $unserializedData['question'] : null;
        $this->answer = isset($unserializedData['answer']) ? $unserializedData['answer'] : null;
        $this->language = isset($unserializedData['language']) ? $unserializedData['language'] : null;
        $this->blocks = isset($unserializedData['blocks']) ? $unserializedData['blocks'] : null;
        $this->theme = isset($unserializedData['theme']) ? $unserializedData['theme'] : null;
        $this->gmt = isset($unserializedData['gmt']) ? $unserializedData['gmt'] : null;
        $this->filters = isset($unserializedData['filters']) ? $unserializedData['filters'] : null;
        $this->mark = isset($unserializedData['mark']) ? $unserializedData['mark'] : null;
        $this->sortingType = isset($unserializedData['sortingType']) ? $unserializedData['sortingType'] : null;
        $this->newsOnPage = isset($unserializedData['newsOnPage']) ? $unserializedData['newsOnPage'] : null;
        $this->commentsOnPage = isset($unserializedData['commentsOnPage']) ? $unserializedData['commentsOnPage'] : null;
        $this->threadsOnPage = isset($unserializedData['threadsOnPage']) ? $unserializedData['threadsOnPage'] : null;
        $this->showEmail = isset($unserializedData['showEmail']) ? $unserializedData['showEmail'] : null;
        $this->showIm = isset($unserializedData['showIm']) ? $unserializedData['showIm'] : null;
        $this->showAvatars = isset($unserializedData['showAvatars']) ? $unserializedData['showAvatars'] : null;
        $this->showUa = isset($unserializedData['showUa']) ? $unserializedData['showUa'] : null;
        $this->showResp = isset($unserializedData['showResp']) ? $unserializedData['showResp'] : null;
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
//		return $this->group->toArray();
        return array($this->group);
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
    public function isEqualTo(UserInterface $user)
    {
        return $this->username === $user->getUsername();
    }
    public function eq(UserInterface $user)
    {
        $thisHash = md5($this->serialize());
        $userHash = md5($this->serialize());
        if($thisHash == $userHash)

            return true;
        else
            return false;
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
     * @param \Datetime $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }
    /**
     * Get birthday
     *
     * @return \Datetime
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
     * @param string $additional
     */
    public function setAdditional($additional)
    {
        $this->additional = $additional;
    }
    /**
     * Get additional
     *
     * @return string
     */
    public function getAdditional()
    {
        return $this->additional;
    }
    /**
     * Set additionalRaw
     *
     * @param string $additionalRaw
     */
    public function setAdditionalRaw($additionalRaw)
    {
        $this->additionalRaw = $additionalRaw;
    }
    /**
     * Get additionalRaw
     *
     * @return string
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
     * @param \Datetime $registrationDate
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;
    }
    /**
     * Get registrationDate
     *
     * @return \Datetime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }
    /**
     * Set lastVisitDate
     *
     * @param \Datetime $lastVisitDate
     */
    public function setLastVisitDate($lastVisitDate)
    {
        $this->lastVisitDate = $lastVisitDate;
    }
    /**
     * Get lastVisitDate
     *
     * @return \Datetime
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
     * @param \RL\MainBundle\Entity\Mark $mark
     */
    public function setMark(\RL\MainBundle\Entity\Mark $mark)
    {
        $this->mark = $mark;
    }
    /**
     * Get mark
     *
     * @return \RL\MainBundle\Entity\Mark
     */
    public function getMark()
    {
        return $this->mark;
    }
    /**
     * Set language
     *
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }
    /**
     * Get language
     *
     * @return string
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
    public function getAttributes()
    {
        return array('id' => $this->id,
            'username' => $this->username,
            'groups' => $this->groups,
            'salt' => $this->salt,
            'password' => $this->password,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'country' => $this->country,
            'city' => $this->city,
            'photo' => $this->photo,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'additional' => $this->additional,
            'additionalRaw' => $this->additionalRaw,
            'email' => $this->email,
            'im' => $this->im,
            'registrationDate' => $this->registrationDate,
            'lastVisitDate' => $this->lastVisitDate,
            'active' => $this->active,
            'captchaLevel' => $this->captchaLevel,
            'openid' => $this->openid,
            'question' => $this->question,
            'answer' => $this->answer,
            'language' => $this->language,
            'blocks' => $this->blocks,
            'theme' => $this->theme,
            'gmt' => $this->gmt,
            'filters' => $this->filters,
            'mark' => $this->mark,
            'sortingType' => $this->sortingType,
            'newsOnPage' => $this->newsOnPage,
            'commentsOnPage' => $this->commentsOnPage,
            'threadsOnPage' => $this->threadsOnPage,
            'showEmail' => $this->showEmail,
            'showIm' => $this->showIm,
            'showAvatars' => $this->showAvatars,
            'showUa' => $this->showUa,
            'showResp' => $this->showResp);
    }
    public function getIdentity()
    {
        return md5($this->username);
    }
    public function isAnonymous()
    {
        return false;
    }
    public function isActive()
    {
        return $this->active;
    }
    /**
     * Add comments
     *
     * @param \RL\ForumBundle\Entity\Message $comments
     */
    public function addMessage(\RL\ForumBundle\Entity\Message $comments)
    {
        $this->comments[] = $comments;
    }
    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set group
     *
     * @param \RL\SecurityBundle\Entity\Group $group
     */
    public function setGroup(\RL\SecurityBundle\Entity\Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return \RL\SecurityBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    public function addEditedComment($editedComment)
    {
        $this->editedComments[] = $editedComment;
    }

    public function removeEditedComment($editedComment)
    {
        $this->editedComments->remove($editedComment);
    }

    public function getEditedComments()
    {
        return $this->editedComments;
    }
}
