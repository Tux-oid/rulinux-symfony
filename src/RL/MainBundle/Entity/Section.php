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
use RL\MainBundle\Entity\Subsection;

/**
 * RL\MainBundle\Entity\Section
 *
 * @ORM\Entity()
 * @ORM\Table(name="sections")
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class Section
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=512)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    protected $description;
    /**
     * @ORM\Column(type="string", length=256, nullable=false)
     */
    protected $rewrite;
    /**
     * @ORM\Column(type="string", length=1024, nullable=false)
     */
    protected $bundle;
    /**
     * @ORM\Column(type="string", length=1024, nullable=false)
     */
    protected $bundleNamespace;
    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\Subsection", mappedBy="section")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    protected $subsections;

    /**
     *
     */
    public function __construct()
    {
        $this->subsections = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getRewrite()
    {
        return $this->rewrite;
    }

    /**
     * @param $rewrite
     */
    public function setRewrite($rewrite)
    {
        $this->rewrite = $rewrite;
    }

    /**
     * @return mixed
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @param $bundle
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSubsections()
    {
        return $this->subsections;
    }

    /**
     * @param $subsections
     */
    public function setSubsections($subsections)
    {
        $this->subsections = $subsections;
    }

    /**
     * @return mixed
     */
    public function getBundleNamespace()
    {
        return $this->bundleNamespace;
    }

    /**
     * @param $bundleNamespace
     */
    public function setBundleNamespace($bundleNamespace)
    {
        $this->bundleNamespace = $bundleNamespace;
    }

}
