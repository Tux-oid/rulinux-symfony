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
use RL\MainBundle\Entity\Section;

/**
 * RL\MainBundle\Entity\Subsection
 *
 * @ORM\Entity(repositoryClass="RL\MainBundle\Entity\Repository\SubsectionRepository")
 * @ORM\Table(name="subsections")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="content_type", type="string", length=20)
 * @ORM\DiscriminatorMap({"subsection" = "Subsection"})
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
*/
class Subsection
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=512)
     */
    protected $description;
    /**
     * @ORM\Column(type="text")
     */
    protected $shortfaq;
    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    protected $rewrite;
    /**
     * @ORM\OneToMany(targetEntity="Thread", mappedBy="subsection")
     */
    protected $threads;
    /**
     * @ORM\ManyToOne(targetEntity="RL\MainBundle\Entity\Section", inversedBy="subsections")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    protected $section;
    public function __construct()
    {
        $this->threads = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set shortfaq
     *
     * @param text $shortfaq
     */
    public function setShortfaq($shortfaq)
    {
        $this->shortfaq = $shortfaq;
    }
    /**
     * Get shortfaq
     *
     * @return text
     */
    public function getShortfaq()
    {
        return $this->shortfaq;
    }
    /**
     * Set rewrite
     *
     * @param string $rewrite
     */
    public function setRewrite($rewrite)
    {
        $this->rewrite = $rewrite;
    }
    /**
     * Get rewrite
     *
     * @return string
     */
    public function getRewrite()
    {
        return $this->rewrite;
    }
    /**
     * Add threads
     *
     * @param RL\MainBundle\Entity\Thread $threads
     */
    public function addThread(\RL\MainBundle\Entity\Thread $threads)
    {
        $this->threads[] = $threads;
    }
    /**
     * Get threads
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getThreads()
    {
        return $this->threads;
    }
    public function getSection()
    {
        return $this->section;
    }
    public function setSection($section)
    {
        $this->section = $section;
    }

}
