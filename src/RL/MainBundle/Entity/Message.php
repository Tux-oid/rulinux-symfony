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
use Doctrine\Common\Collections\ArrayCollection;
use RL\MainBundle\Entity\User;
use RL\MainBundle\Entity\Thread;

/**
 * RL\MainBundle\Entity\Message
 *
 * @ORM\Entity(repositoryClass="RL\MainBundle\Entity\Repository\MessageRepository")
 * @ORM\Table(name="comments")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
*/
class Message
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="RL\MainBundle\Entity\Thread", inversedBy="messages")
     */
    protected $thread;
    /**
     * @ORM\ManyToOne(targetEntity="RL\MainBundle\Entity\User", inversedBy="comments")
     */
    protected $user;
    /**
     * @ORM\ManyToOne(targetEntity="RL\MainBundle\Entity\Message", inversedBy="responses")
     */
    protected $referer;
    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\Message", mappedBy="referer")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $responses;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $postingTime;
    /**
     * @ORM\Column(type="text")
     */
    protected $subject;
    /**
     * @ORM\Column(type="text")
     */
    protected $comment;
    /**
     * @ORM\Column(type="text", name="raw_comment")
     */
    protected $rawComment;
    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    protected $useragent;
    /**
     * @ORM\Column(type="datetime", name="changing_timest")
     */
    protected $changingTime;
    /**
     * @ORM\ManyToMany(targetEntity="RL\MainBundle\Entity\User", mappedBy="editedComments")
     */
    protected $changedBy;
    /**
     * @ORM\Column(type="string", length=512, name="changed_for", nullable=true)
     */
    protected $changedFor;
    /**
     * @ORM\ManyToMany(targetEntity="RL\MainBundle\Entity\Filter", mappedBy="messages")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $filters;
    /**
     * @ORM\Column(type="boolean", name="show_ua")
     */
    protected $showUa = true;
    /**
     * @ORM\Column(type="string", length=128, name="session_id", nullable=true)
     */
    protected $sessionId;

    public function __construct()
    {
        $this->postingTime = $this->changingTime = new \DateTime('now');
        $this->responses = new ArrayCollection();
    }
    /**
     * @ORM\PrePersist
     */
    public function setDefaultValues()
    {
        $this->postingTime = $this->changingTime = new \DateTime('now');
        $this->sessionId = \session_id();
        $this->useragent = $_SERVER['HTTP_USER_AGENT'];
        if($this->user->getShowUA())
            $this->showUa = true;
        else
            $this->showUa = false;

    }
    /**
     * @ORM\PreUpdate
     */
    public function updateChangingTime()
    {
        $this->changingTime = new \DateTime('now');
        $this->getThread()->setChangingTime($this->changingTime);
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
     * Set referer
     *
     * @param \RL\MainBundle\Entity\Message $referer
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
        $referer->addResponse($this);
    }
    /**
     * Get referer
     *
     * @return integer
     */
    public function getReferer()
    {
        return $this->referer;
    }
    /**
     * Set postingTime
     *
     * @param \Datetime $postingTime
     */
    public function setPostingTime($postingTime)
    {
        $this->postingTime = $postingTime;
    }
    /**
     * Get postingTime
     *
     * @return \Datetime
     */
    public function getPostingTime()
    {
        return $this->postingTime;
    }
    /**
     * Set subject
     *
     * @param text $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }
    /**
     * Get subject
     *
     * @return text
     */
    public function getSubject()
    {
        return $this->subject;
    }
    /**
     * Set comment
     *
     * @param text $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
    /**
     * Get comment
     *
     * @return text
     */
    public function getComment()
    {
        return $this->comment;
    }
    /**
     * Set rawComment
     *
     * @param text $rawComment
     */
    public function setRawComment($rawComment)
    {
        $this->rawComment = $rawComment;
    }
    /**
     * Get rawComment
     *
     * @return text
     */
    public function getRawComment()
    {
        return $this->rawComment;
    }
    /**
     * Set useragent
     *
     * @param string $useragent
     */
    public function setUseragent($useragent)
    {
        $this->useragent = $useragent;
    }
    /**
     * Get useragent
     *
     * @return string
     */
    public function getUseragent()
    {
        return $this->useragent;
    }
    /**
     * Set changingTime
     *
     * @param \Datetime $changingTime
     */
    public function setChangingTime($changingTime)
    {
        $this->changingTime = $changingTime;
    }
    /**
     * Get changingTime
     *
     * @return \Datetime
     */
    public function getChangingTime()
    {
        return $this->changingTime;
    }
    /**
     * Set changedFor
     *
     * @param string $changedFor
     */
    public function setChangedFor($changedFor)
    {
        $this->changedFor = $changedFor;
    }
    /**
     * Get changedFor
     *
     * @return string
     */
    public function getChangedFor()
    {
        return $this->changedFor;
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
     * Add changedBy
     *
     * @param \RL\MainBundle\Entity\User $changedBy
     */
    public function addChangedBy($changedBy)
    {
        $this->changedBy[] = $changedBy;
    }

    /**
     * Remove changedBy
     *
     * @param \RL\MainBundle\Entity\User $changedBy
     */
    public function removeChangedBy($changedBy)
    {
        $this->changedBy[] = $changedBy;
    }

    /**
     * Get changedBy
     *
     * @return \RL\MainBundle\Entity\User
     */
    public function getChangedBy()
    {
        return $this->changedBy;
    }
    /**
     * Set user
     *
     * @param \RL\MainBundle\Entity\User $user
     */
    public function setUser($user)
    {
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
    /**
     * Set sessionId
     *
     * @param string $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }
    /**
     * Get sessionId
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
    public function setThread(\RL\MainBundle\Entity\Thread $thread)
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
     * Add responses
     *
     * @param \RL\MainBundle\Entity\Message $response
     */
    public function addResponse(Message $response)
    {
        $this->responses[] = $response;
    }

    /**
     * Remove responses
     *
     * @param \RL\MainBundle\Entity\Message $response
     */
    public function removeResponse(Message $response)
    {
        $this->responses->removeElement($response);
    }

    /**
     * Get responses
     *
     * @return \RL\MainBundle\Entity\Message
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * Add filter
     *
     * @param \RL\MainBundle\Entity\Filter $filter
     */
    public function addFilter($filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Remove filter
     *
     * @param \RL\MainBundle\Entity\Filter $filter
     */
    public function removeFilter($filter)
    {
        $this->filters->remove($filter);
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getFilters()
    {
        return $this->filters;
    }

}
