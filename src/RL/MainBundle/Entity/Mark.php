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
use Doctrine\ORM\EntityManager;
use PMP;

require_once __DIR__.'/../../../../vendor/GeSHi/GeSHi/src/geshi.php';

/**
 * RL\MainBundle\Entity\Mark
 *
 * @ORM\Entity
 * @ORM\Table(name="marks")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="content_type", type="string", length=20)
 * @ORM\DiscriminatorMap({"mark"="Mark"})
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
*/
abstract class Mark
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
     * @ORM\Column(name="description", type="text", unique=true, nullable=false)
     */
    protected $description;
    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\User", mappedBy="mark")
     */
    protected $users;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add users
     *
     * @param \RL\MainBundle\Entity\User $users
     */
    public function addUser(\RL\MainBundle\Entity\User $users)
    {
        $this->users[] = $users;
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Render message
     *
     * @param $string
     * @return mixed
     */
    abstract public function render($string);

    /**
     * Create formulas
     *
     * @param $string
     * @return mixed|string
     */
    public function makeFormula($string)
    {
        $text = '<m>' . $string . '</m>';
        $size = 10;
        $pathToImg = '/web/bundles/rlmain/images/formulas/'; //TODO:save path to config file
        $phpMathPublisher = new \PMP\PhpMathPublisher();
        $str = $phpMathPublisher->mathfilter($text, $size, $pathToImg);

        return $str;
    }

    /**
     * Highlight source code
     *
     * @param $code
     * @param $lang
     * @return string
     */
    public function highlight($code, $lang)
    {
        if(empty($lang))
            $lang = 'c';
        $path = $_SERVER["DOCUMENT_ROOT"] . 'vendor/geshi/lib/Geshi/src/geshi'; //TODO:save path to config file
        $geshi = new \GeSHi($code, $lang);
        $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 1);
        $code = geshi_highlight($code, $lang, $path, true);

        return $code;
    }

    /**
     * Get list of highlighted languages
     *
     * @return array
     */
    public function getHighlightedLanguages()
    {
        $geshi = new \GeSHi('', '');
        $languages = $geshi->get_supported_languages();
        asort($languages);

        return $languages;
    }

    public function __sleep()
    {
        unset($this->entityManager);
        return array_keys(get_object_vars($this));
    }
}
