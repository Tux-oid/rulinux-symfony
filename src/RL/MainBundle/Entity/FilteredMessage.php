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
use RL\MainBundle\Entity\Filter;

/**
 * RL\MainBundle\Entity\UsersFilter
 *
 * @ORM\Entity()
 * @ORM\Table(name="filtered_message")
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class FilteredMessage
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
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="filters")
     *
     * @var \RL\MainBundle\Entity\Message
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="Filter", inversedBy="messages")
     *
     * @var \RL\MainBundle\Entity\Filter
     */
    protected $filter;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $weight;

    /**
     * Constructor
     *
     * @param |RL\MainBundle\Entity\Filter $filter
     * @param int $weight
     */
    public function __construct(Filter $filter = null, $weight = 0)
    {
        $this->filter = $filter;
        $this->weight = $weight;
    }

    /**
     * Set filter
     *
     * @param \RL\MainBundle\Entity\Filter $filter
     */
    public function setFilter(Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Get filter
     *
     * @return \RL\MainBundle\Entity\Filter
     */
    public function getFilter()
    {
        return $this->filter;
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
     * Set weight
     *
     * @param int $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * Get weight
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set message
     *
     * @param \RL\MainBundle\Entity\Message $message
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return \RL\MainBundle\Entity\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

}
