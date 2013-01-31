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

namespace RL\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RL\MainBundle\Entity\FilteredMessage;
use RL\MainBundle\Entity\Word;
use RL\MainBundle\Security\User\RLUserInterface;

/**
 * RL\MainBundle\Entity\Filter
 *
 * @ORM\Entity()
 * @ORM\Table(name="filters")
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
final class Filter
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
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\Word", mappedBy="filter")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $words;

    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\UsersFilter", mappedBy="filters")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\FilteredMessage", mappedBy="filters")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $messages;

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
     * @param \RL\MainBundle\Security\User\RLUserInterface $user
     */
    public function addUser(RLUserInterface$user)
    {
        if($user->isAnonymous()) {
            $user = $user->getDbAnonymous();
        }
        $this->users[] = $user;
    }

    /**
     * Remove user
     *
     * @param \RL\MainBundle\Security\User\RLUserInterface $user
     */
    public function removeUser(RLUserInterface $user)
    {
        if($user->isAnonymous()) {
            $user = $user->getDbAnonymous();
        }
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
     * @param \RL\MainBundle\Entity\FilteredMessage $message
     */
    public function addMessage(FilteredMessage $message)
    {
        $this->words[] = $message;
    }

    /**
     * Remove message
     *
     * @param \RL\MainBundle\Entity\FilteredMessage $message
     */
    public function removeMessage(FilteredMessage $message)
    {
        $this->words->remove($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMessages()
    {
        return $this->words;
    }

    public function filter($message)
    {

    }
}
