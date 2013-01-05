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
use RL\MainBundle\Security\User\RLUserInterface;

/**
 * RL\MainBundle\Entity\Block
 *
 * @ORM\Entity
 * @ORM\Table(name="blocks")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="content_type", type="string", length=20)
 * @ORM\DiscriminatorMap({"block"="Block"})
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
abstract class Block
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=256)
     */
    protected $name;

    /**
     * @ORM\Column(name="description", type="string", length=512, unique=true, nullable=true)
     */
    protected $description;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $leftBlocksUsers;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $rightBlocksUsers;

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
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add left block user
     *
     * @param $leftBlocksUser
     */
    public function addLeftBlocksUser(RLUserInterface $leftBlocksUser)
    {
        $this->leftBlocksUsers[] = $leftBlocksUser;
    }

    /**
     * Remove left block user
     *
     * @param $leftBlocksUser
     */
    public function removeLeftBlocksUser(RLUserInterface $leftBlocksUser)
    {
        $this->leftBlocksUsers->remove($leftBlocksUser);
    }

    /**
     * Get left block users
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getLeftBlocksUsers()
    {
        return $this->leftBlocksUsers;
    }

    /**
     * Add right block user
     *
     * @param $rightBlocksUser
     */
    public function addRightBlocksUser(RLUserInterface $rightBlocksUser)
    {
        $this->rightBlocksUsers[] = $rightBlocksUser;
    }

    /**
     * Remove right block user
     *
     * @param $rightBlocksUser
     */
    public function removeRightBlocksUser(RLUserInterface $rightBlocksUser)
    {
        $this->rightBlocksUsers->remove($rightBlocksUser);
    }

    /**
     * Get right block users
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRightBlocksUsers()
    {
        return $this->rightBlocksUsers;
    }

    /**
     * Get list of services which needed to block
     *
     * @return array
     */
    abstract public function getNeededServicesList();

    /**
     * Render block
     * Returns array('templateFile'=> 'fileName.html.twig', 'parameters'=>array())
     *
     * @param array $services
     * @return array
     */
    abstract public function render(array $services);

}
