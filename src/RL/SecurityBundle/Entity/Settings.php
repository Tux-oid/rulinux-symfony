<?php
/**
 * @author Tux-oid
 */

namespace RL\SecurityBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="settings")
 */
class Settings
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\Column(type="text")
	 */
	protected $name;
	/**
	 * @ORM\Column(type="text")
	 */
	protected $value;
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
	 * @param text $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	/**
	 * Get name
	 *
	 * @return text
	 */
	public function getName()
	{
		return $this->name;
	}
	/**
	 * Set value
	 *
	 * @param text $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}
	/**
	 * Get value
	 *
	 * @return text
	 */
	public function getValue()
	{
		return $this->value;
	}
}