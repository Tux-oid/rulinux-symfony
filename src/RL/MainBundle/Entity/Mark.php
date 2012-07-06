<?php
/**
 * @author Tux-oid
 */

namespace RL\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="marks")
  */
class Mark /* implements Serializable */
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
	 * @ORM\Column(name="description", type="text", unique="true", nullable="false")
	 */
	protected $description;
	/**
	 * @ORM\Column(name="markClass", type="string", length=256, unique="true", nullable="false")
	 */
	protected $markClass;
	/**
	 * @ORM\OneToMany(targetEntity="RL\SecurityBundle\Entity\User", mappedBy="mark")
	 */
	protected $users;

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
	 * @param RL\SecurityBundle\Entity\User $users
	 */
	public function addUser(\RL\SecurityBundle\Entity\User $users)
	{
		$this->users[] = $users;
	}

	/**
	 * Get users
	 *
	 * @return Doctrine\Common\Collections\Collection
	 */
	public function getUsers()
	{
		return $this->users;
	}

	public function getMarkObject()
	{
		$className = 'RL\MainBundle\Marks\\'.$this->getMarkClass();
		return new $className();
	}

	/**
	 * @param $markClass
	 */
	public function setMarkClass($markClass)
	{
		$this->markClass = $markClass;
	}

	/**
	 * @return mixed
	 */
	public function getMarkClass()
	{
		return $this->markClass;
	}
}
