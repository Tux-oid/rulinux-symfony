<?php

namespace LorNgDevelopers\RulinuxBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="groups")
 */
class Group implements RoleInterface/*, Serializable*/
{
	/**
	 * @ORM\Id()
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\Column(name="name", type="string", length=255)
	 * @Assert\NotBlank()
	 */
	protected $name;
	/**
	 * @ORM\Column(name="description", type="string", length=512, unique="true", nullable="false")
	 */
	protected $description;
	/**
	 * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
	 */
	protected $users;
	public function __construct()
	{
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
	}
	/**
	* @see RoleInterface
	*/
	public function getRole()
	{
		return $this->getName();
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
	* @param LorNgDevelopers\RulinuxBundle\Entity\User $users
	*/
	public function addUser(\LorNgDevelopers\RulinuxBundle\Entity\User $users)
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
}