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

use Doctrine\ORM\Mapping as ORM;
use RL\MainBundle\Entity\FilteredMessage;
use RL\MainBundle\Entity\Word;
use RL\MainBundle\Security\User\RLUserInterface;
use RL\MainBundle\Entity\Message;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RL\MainBundle\Entity\Filter
 *
 * @ORM\Entity(repositoryClass="RL\MainBundle\Entity\Repository\FilterRepository")
 * @ORM\Table(name="filters")
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class Filter
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;
    /**
     * @ORM\Column(name="name", type="string", length=512)
     *
     * @var string
     */
    protected $name;
    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\Word", mappedBy="filter", cascade={"all"})
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $words;

    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\UsersFilter", mappedBy="filter", cascade={"all"})
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\FilteredMessage", mappedBy="filter", cascade={"all"})
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $messages;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $filterByHtmlTags;

    /**
     * Constructor
     *
     * @param boolean $filterByHtmlTags
     */
    public function  __construct($filterByHtmlTags = false)
    {
        $this->filterByHtmlTags = $filterByHtmlTags;
        $this->messages = new ArrayCollection();
        $this->words = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * Get id
     *
     * @return int
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
     * Add user
     *
     * @param \RL\MainBundle\Entity\UsersFilter $user
     */
    public function addUser(UsersFilter $user)
    {
        $this->users[] = $user;
    }

    /**
     * Remove user
     *
     * @param \RL\MainBundle\Entity\UsersFilter $user
     */
    public function removeUser(UsersFilter $user)
    {
        $this->users->remove($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add word
     *
     * @param \RL\MainBundle\Entity\Word $word
     */
    public function addWord(Word $word)
    {
        $this->words[] = $word;
        $word->setFilter($this);
    }

    /**
     * Remove word
     *
     * @param \RL\MainBundle\Entity\Word $word
     */
    public function removeWord(Word $word)
    {
        $this->words->remove($word);
    }

    /**
     * Get words
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * Add message
     *
     * @param FilteredMessage $message
     */
    public function addMessage(FilteredMessage $message)
    {
        $this->messages->add($message);
    }

    /**
     * Remove message
     *
     * @param FilteredMessage $message
     */
    public function removeMessage(FilteredMessage $message)
    {
        $this->messages->remove($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set filter by tags
     *
     * @param boolean $filterByHtmlTags
     */
    public function setFilterByHtmlTags($filterByHtmlTags)
    {
        $this->filterByHtmlTags = $filterByHtmlTags;
    }

    /**
     * Is filter by tags
     *
     * @return boolean
     */
    public function isFilterByHtmlTags()
    {
        return $this->filterByHtmlTags;
    }
}
