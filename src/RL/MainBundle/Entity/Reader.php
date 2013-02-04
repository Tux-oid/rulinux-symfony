<?php
/**
 * Copyright (c) 2009 - 2013, Peter Vasilevsky
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
use RL\MainBundle\Entity\Thread;
use RL\MainBundle\Security\User\RLUserInterface;

/**
 * RL\MainBundle\Entity\Reader
 *
 * @ORM\Entity(repositoryClass="RL\MainBundle\Entity\Repository\ReaderRepository")
 * @ORM\Table(name="readers")
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class Reader
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="readThreads")
     *
     * @var \RL\MainBundle\Entity\User
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Thread", inversedBy="resders")
     *
     * @var \RL\MainBundle\Entity\Thread
     */
    protected $thread;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $sessionId;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $timestamp;

    /**
     * Constructor
     *
     * @param \RL\MainBundle\Security\User\RLUserInterface $user
     * @param \RL\MainBundle\Entity\Thread $thread
     * @param string $sessionId
     */
    public function __construct(RLUserInterface $user = null, Thread $thread = null, $sessionId)
    {
        $this->thread = $thread;
        if(null !== $user) {
            if($user->isAnonymous()) {
                $user = $user->getDbAnonymous();
            }
        }
        $this->user = $user;
        $this->sessionId = $sessionId;
        $this->timestamp = new \DateTime();
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
     * Set session id
     *
     * @param string $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Get session id
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set thread
     *
     * @param \RL\MainBundle\Entity\Thread $thread
     */
    public function setThread($thread)
    {
        $this->thread = $thread;
    }

    /**
     * Get thread
     *
     * @return \RL\MainBundle\Entity\Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set user
     *
     * @param \RL\MainBundle\Security\User\RLUserInterface $user
     */
    public function setUser(RLUserInterface $user)
    {
        if($user->isAnonymous()) {
            $user = $user->getDbAnonymous();
        }
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return \RL\MainBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

}
