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
use RL\MainBundle\Security\User\RLUserInterface;

/**
 * RL\MainBundle\Entity\BlockSet
 *
 * @ORM\Entity(repositoryClass="RL\MainBundle\Entity\Repository\BlockPositionRepository")
 * @ORM\Table(name="block_sets")
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class BlockPosition
{
    /**
     * Block position. Left
     */
    const POSITION_UNUSED = 0;

    /**
     * Block position. Left
     */
    const POSITION_LEFT = 1;

    /**
     * Block position. Right
     */
    const POSITION_RIGHT = 2;

    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Block", inversedBy="positions", cascade={"all"})
     *
     * @var \RL\MainBundle\Entity\Block
     */
    protected $block;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="positions", cascade={"all"})
     *
     * @var \RL\MainBundle\Security\User\RLUserInterface
     */
    protected $user;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $weight;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $position;

    /**
     * Constructor
     *
     * @param \RL\MainBundle\Security\User\RLUserInterface $user
     * @param \RL\MainBundle\Entity\Block $block
     * @param int $position
     * @param int $weight
     */
    public function __construct(
        RLUserInterface $user = null,
        Block $block = null,
        $position = BlockPosition::POSITION_LEFT,
        $weight = 1
    ) {
        if (null !== $user) {
            $this->user = $user;
        }
        if (null !== $block) {
            $this->block = $block;
        }
        $this->position = $position;
        $this->weight = $weight;
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
     * Set position
     *
     * @param int $position
     */
    public function setPosition($position)
    {
        if ($position === BlockPosition::POSITION_LEFT || $position === BlockPosition::POSITION_RIGHT) {
            $this->position = $position;
        } else {
            $this->position = BlockPosition::POSITION_LEFT;
        }
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set user
     *
     * @param \RL\MainBundle\Security\User\RLUserInterface $user
     */
    public function setUser(RLUserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return \RL\MainBundle\Security\User\RLUserInterface
     */
    public function getUser()
    {
        return $this->user;
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
     * Set block
     *
     * @param \RL\MainBundle\Entity\Block $block
     */
    public function setBlock($block)
    {
        $this->block = $block;
    }

    /**
     * Get block
     *
     * @return \RL\MainBundle\Entity\Block
     */
    public function getBlock()
    {
        return $this->block;
    }

}
