<?php

namespace RL\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RL\MainBundle\Entity\Filter
 *
 * @ORM\Entity()
 * @ORM\Table(name="filters")
 *
 * @author Tux-oid
 */
final class Filter
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;
    /**
     * @ORM\Column(name="name", type="string", length=512)
     *
     * @var string
     */
    protected $name;
    /**
     * @ORM\OneToMany(targetEntity="RL\MainBundle\Entity\Word", mappedBy="filter")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $words;

    /**
     * @ORM\ManyToMany(targetEntity="RL\MainBundle\Entity\User", inversedBy="filters")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $users;

    /**
     * @ORM\ManyToMany(targetEntity="RL\ForumBundle\Entity\Message", inversedBy="filters")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $messages;

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
     * Add user
     *
     * @param \RL\MainBundle\Entity\User $user
     */
    public function addUser($user)
    {
        $this->users[] = $user;
    }

    /**
     * Remove user
     *
     * @param \RL\MainBundle\Entity\User $user
     */
    public function removeUser($user)
    {
        $this->users->remove($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add word
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $word
     */
    public function addWord($word)
    {
        $this->words[] = $word;
    }

    /**
     * Remove word
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $word
     */
    public function removeWord($word)
    {
        $this->words->remove($word);
    }

    /**
     * Get words
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getWords()
    {
        return $this->words;
    }


}
