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
use Doctrine\Common\Collections\ArrayCollection;
use RL\MainBundle\Entity\Message;
use RL\MainBundle\Entity\Subsection;

/**
 * RL\MainBundle\Entity\Thread
 *
 * @ORM\Entity(repositoryClass="RL\MainBundle\Entity\Repository\ThreadRepository")
 * @ORM\Table(name="threads")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="content_type", type="string", length=20)
 * @ORM\DiscriminatorMap({"thread" = "Thread"})
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class Thread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\Message", mappedBy="thread")
     * @ORM\OrderBy({"id" = "ASC"})
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $messages;

    /**
     * @ORM\ManyToOne(targetEntity="RL\MainBundle\Entity\Subsection", inversedBy="threads")
     *
     * @var \RL\MainBundle\Entity\Subsection
     */
    protected $subsection;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $attached = false;

    /**
     * @ORM\Column(type="datetime", name="timest")
     *
     * @var \DateTime
     */
    protected $postingTime;

    /**
     * @ORM\Column(type="datetime", name="changing_timest")
     *
     * Var \DateTime
     */
    protected $changingTime;

    /**
     * @ORM\OneToMany(targetEntity="Reader", mappedBy="thread", cascade={"all"})
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $readers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->readers = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function setDefaultValues()
    {
        $this->postingTime = $this->changingTime = new \DateTime('now');
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateChangingTime()
    {
        $this->changingTime = new \DateTime('now');
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
     * Set attached
     *
     * @param boolean $attached
     */
    public function setAttached($attached)
    {
        $this->attached = $attached;
    }

    /**
     * Get attached
     *
     * @return boolean
     */
    public function getAttached()
    {
        return $this->attached;
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
     * Add messages
     *
     * @param \RL\MainBundle\Entity\Message $messages
     */
    public function addMessage(Message $messages)
    {
        $this->messages[] = $messages;
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set subsection
     *
     * @param \RL\MainBundle\Entity\Subsection $subsection
     */
    public function setSubsection(Subsection $subsection)
    {
        $this->subsection = $subsection;
    }

    /**
     * Get subsection
     *
     * @return \RL\MainBundle\Entity\Subsection
     */
    public function getSubsection()
    {
        return $this->subsection;
    }

    /**
     * Set posting time
     *
     * @param $postingTime
     */
    public function setPostingTime($postingTime)
    {
        $this->postingTime = $postingTime;
    }

    /**
     * Get posting time
     *
     * @return mixed
     */
    public function getPostingTime()
    {
        return $this->postingTime;
    }

    /**
     * Add reader
     *
     * @param \RL\MainBundle\Entity\Reader $reader
     */
    public function addReader(Reader $reader)
    {
        $this->readers[] = $reader;
    }

    /**
     * Remove reader
     *
     * @param \RL\MainBundle\Entity\Reader $reader
     */
    public function removeReader(Reader $reader)
    {
        $this->readers[] = $reader;
    }

    /**
     * Get readers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReaders()
    {
        return $this->readers;
    }

}
