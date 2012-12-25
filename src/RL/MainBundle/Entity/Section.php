<?php
/**
 * @author Tux-oid
 */

namespace RL\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use RL\ForumBundle\Entity\Subsection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sections")
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
     * @ORM\OneToMany(targetEntity="RL\ForumBundle\Entity\Subsection", mappedBy="section")
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
