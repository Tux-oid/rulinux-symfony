<?php
/**
 * Copyright (c) 2009 - 2012, Peter Vasilevsky
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

namespace RL\MainBundle\Entity;

use RL\MainBundle\Security\User\RLUserInterface;
use RL\MainBundle\Entity\Message;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use \RL\MainBundle\Entity\Group;
use RL\MainBundle\Entity\Mark;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * RL\MainBundle\Entity\User
 *
 * @ORM\Entity(repositoryClass="RL\MainBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="users")
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class User implements RLUserInterface, EquatableInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="users", cascade={"all"})
     *
     * @var \RL\MainBundle\Entity\Group
     */
    protected $group;

    /**
     * @ORM\Column(name="nick", type="string", length=100, unique=true, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Regex("#([a-zA-Z0-9\_\-\/\.]{2,})$#")
     *
     * @var string
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=32)
     *
     * @var string
     */
    protected $salt;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $lastname;

    /**
     * @ORM\Column(name="country", type="string", length=512, nullable=true)
     *
     * @Assert\Country
     *
     * @var string
     */
    protected $country;

    /**
     * @ORM\Column(name="city", type="string", length=512, nullable=true)
     *
     * @var string
     */
    protected $city;

    /**
     * @ORM\Column(name="photo", type="string", length=512, nullable=true)
     *
     * @Assert\Image
     *
     * @var string
     */
    protected $photo;

    /**
     * @ORM\Column(name="birthday", type="datetime", nullable=true)
     *
     * @Assert\DateTime
     *
     * @var \DateTime
     */
    protected $birthday;

    /**
     * @ORM\Column(name="gender", type="boolean")
     *
     * @var boolean
     */
    protected $gender;

    /**
     * @ORM\Column(name="additional", type="text", nullable=true)
     *
     * @var string
     */
    protected $additional;

    /**
     * @ORM\Column(name="raw_additional", type="text", nullable=true)
     *
     * @var string
     */
    protected $additionalRaw;

    /**
     * @ORM\Column(name="email", type="string", length=512, unique=true, nullable=false)
     *
     * @Assert\Email
     *
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(name="im", type="string", length=512, nullable=true)
     *
     * @Assert\Email
     *
     * @var string
     */
    protected $im;

    /**
     * @ORM\Column(name="register_date", type="datetime")
     *
     * @Assert\DateTime
     *
     * @var \DateTime
     */
    protected $registrationDate;

    /**
     * @ORM\Column(name="last_visit", type="datetime")
     *
     * @Assert\DateTime
     *
     * @var \DateTime
     */
    protected $lastVisitDate;

    /**
     * @ORM\Column(name="active", type="boolean")
     *
     * @var boolean
     */
    protected $active;

    /**
     * @ORM\Column(name="captcha", type="integer", nullable=true)
     *
     * @var integer
     */
    protected $captchaLevel;

    /**
     * @ORM\Column(name="openid", type="string", length=1024, nullable=true)
     *
     * @var string
     */
    protected $openid;

    /**
     * @ORM\Column(name="question", type="string", length=1024, nullable=false)
     *
     * @var string
     */
    protected $question;

    /**
     * @ORM\Column(name="answer", type="string", length=1024, nullable=false)
     *
     * @var string
     */
    protected $answer;

    //Settings
    /**
     * @ORM\Column(name="language", type="text")
     *
     * @Assert\NotBlank()
     * @Assert\Language
     *
     * @var string
     */
    protected $language;

    /**
     * @ORM\ManyToOne(targetEntity="RL\MainBundle\Entity\Theme")
     *
     * @var \RL\MainBundle\Entity\Theme
     */
    protected $theme;

    /**
     * @ORM\Column(name="gmt", type="string", length=64)
     *
     * @var string
     */
    protected $gmt;

    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\UsersFilter", mappedBy="user", cascade={"all"})
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $filters;

    /**
     * @ORM\ManyToOne(targetEntity="RL\MainBundle\Entity\Mark", inversedBy="users", cascade={"all"})
     *
     * \RL\MainBundle\Entity\Mark
     */
    protected $mark;

    /**
     * @ORM\Column(name="sort_to", type="string", length=512)
     *
     * @var string
     */
    protected $sortingType;

    /**
     * @ORM\Column(name="news_on_page", type="integer")
     *
     * @var integer
     */
    protected $newsOnPage;

    /**
     * @ORM\Column(name="comments_on_page", type="integer")
     *
     * @var integer
     */
    protected $commentsOnPage;

    /**
     * @ORM\Column(name="threads_on_page", type="integer")
     *
     * @var integer
     */
    protected $threadsOnPage;

    /**
     * @ORM\Column(name="show_email", type="boolean")
     *
     * @var boolean
     */
    protected $showEmail;

    /**
     * @ORM\Column(name="show_im", type="boolean")
     *
     * @var boolean
     */
    protected $showIm;

    /**
     * @ORM\Column(name="show_avatars", type="boolean")
     *
     * @var boolean
     */
    protected $showAvatars;

    /**
     * @ORM\Column(name="show_ua", type="boolean")
     *
     * @var boolean
     */
    protected $showUa;

    /**
     * @ORM\Column(name="show_resp", type="boolean")
     *
     * @var boolean
     */
    protected $showResp;

    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\Message", mappedBy="user", cascade={"all"})
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $messages;

    /**
     * @ORM\ManyToMany(targetEntity="RL\MainBundle\Entity\Message", inversedBy="changedBy", cascade={"all"})
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $editedComments;

    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\BlockPosition", mappedBy="user", cascade={"all"})
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $positions;

    /**
     * @ORM\OneToMany(targetEntity="Reader", mappedBy="user", cascade={"all"})
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $readThreads;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->active = true;
        $this->salt = md5(uniqid(null, true));
        $this->positions = new ArrayCollection();
        $this->filters = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->editedComments = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
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

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return bool
     */
    public function eq(UserInterface $user)
    {
        if ($this === $user) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $ret = array();
        $vars = get_class_vars(get_class($this));
        foreach ($vars as $key => $var) {
            $ret[$key] = $this->$key;
        }

        return $ret;
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
     * Set id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set messages
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

    /**
     * Set positions
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $positions
     */
    public function setPositions($positions)
    {
        $this->positions = $positions;
    }

    /**
     * Set read threads
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $readThreads
     */
    public function setReadThreads($readThreads)
    {
        $this->readThreads = $readThreads;
    }

    /**
     * Set filters
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * Set edited comments
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $editedComments
     */
    public function setEditedComments($editedComments)
    {
        $this->editedComments = $editedComments;
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
     * Add filter
     *
     * @param \RL\MainBundle\Entity\UsersFilter $filter
     */
    public function addFilter(UsersFilter $filter)
    {
        $this->filters->add($filter);
    }

    /**
     * Remove filter
     *
     * @param \RL\MainBundle\Entity\UsersFilter $filter
     */
    public function removeFilter(UsersFilter $filter)
    {
        $this->filters->remove($filter);
    }

    /**
     * Get filters
     *
     * @return ArrayCollection
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
    public function isShowEmail()
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
    public function isShowIm()
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
    public function isShowAvatars()
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
    public function isShowUa()
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
    public function isShowResp()
    {
        return $this->showResp;
    }

    /**
     * Set mark
     *
     * @param \RL\MainBundle\Entity\Mark $mark
     */
    public function setMark(Mark $mark)
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

    /**
     * @return string
     */
    public function getIdentity()
    {
        return md5($this->username);
    }

    /**
     * @return bool
     */
    public function isAnonymous()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Add comments
     *
     * @param \RL\MainBundle\Entity\Message $message
     */
    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
    }

    /**
     * Add comments
     *
     * @param \RL\MainBundle\Entity\Message $message
     */
    public function removeMessage(Message $message)
    {
        $this->messages->remove($message);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set group
     *
     * @param \RL\MainBundle\Entity\Group $group
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return \RL\MainBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param \RL\MainBundle\Entity\Message $editedComment
     */
    public function addEditedComment(Message $editedComment)
    {
        $this->editedComments[] = $editedComment;
    }

    /**
     * @param \RL\MainBundle\Entity\Message $editedComment
     */
    public function removeEditedComment(Message $editedComment)
    {
        $this->editedComments->remove($editedComment);
    }

    /**
     * @return mixed
     */
    public function getEditedComments()
    {
        return $this->editedComments;
    }

    /**
     * Add position
     *
     * @param \RL\MainBundle\Entity\BlockPosition $position
     */
    public function addPosition(BlockPosition $position)
    {
        $this->positions->add($position);
    }

    /**
     * Remove position
     *
     * @param \RL\MainBundle\Entity\BlockPosition $position
     */
    public function removePosition(BlockPosition $position)
    {
        $this->positions->remove($position);
    }

    /**
     * Get positions
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * Add reader
     *
     * @param \RL\MainBundle\Entity\Reader $reader
     */
    public function addReadThread(Reader $reader)
    {
        $this->readThreads[] = $reader;
    }

    /**
     * Remove reader
     *
     * @param \RL\MainBundle\Entity\Reader $reader
     */
    public function removeReadThread(Reader $reader)
    {
        $this->readThreads[] = $reader;
    }

    /**
     * Get readers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReadThreads()
    {
        return $this->readThreads;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return json_encode(array('id' => $this->getId()));
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        $unserializedData = json_decode($serialized, true);
        $this->setId($unserializedData['id']);
    }
}
