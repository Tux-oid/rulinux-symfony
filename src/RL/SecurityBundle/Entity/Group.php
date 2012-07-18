<?php
/**
 * @author Tux-oid
 */

namespace RL\SecurityBundle\Entity;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="groups")
 */
class Group implements RoleInterface, \Serializable
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
	 * @ORM\OneToMany(targetEntity="User", mappedBy="group")
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
	public function serialize()
	{
		return serialize(
				array(
					'id' => $this->id,
					'name' => $this->name,
					'description' => $this->description,
					'users' => $this->users
				)
		);
	}
	public function unserialize($serialized)
	{
		$unserializedData = unserialize($serialized);
		$this->id = isset($unserializedData['id']) ? $unserializedData['id'] : null;
		$this->name = isset($unserializedData['name']) ? $unserializedData['name'] : null;
		$this->description = isset($unserializedData['description']) ? $unserializedData['description'] : null;
		$this->users = isset($unserializedData['users']) ? $unserializedData['users'] : null;
	}
}