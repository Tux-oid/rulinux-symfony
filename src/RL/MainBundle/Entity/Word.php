<?php

namespace RL\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RL\MainBundle\Entity\Word
 *
 * @ORM\Entity()
 * @ORM\Table(name="words")
 *
 * @author Tux-oid
 */
final class Word
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
     * @ORM\Column(name="word", type="string", length=512)
     *
     * @var string
     */
    protected $word;

    /**
     * @ORM\Column(name="weight", type="float")
     *
     * @var float
     */
    protected $weight;

    /**
     * @ORM\ManyToOne(targetEntity="RL\MainBundle\Entity\Filter", inversedBy="words")
     *
     * @var \RL\MainBundle\Entity\Filter
     */
    protected $filter;

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
     * Set weight
     *
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * Get weight
     *
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set word
     *
     * @param string $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }

    /**
     * Get word
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set filter
     *
     * @param \RL\MainBundle\Entity\Filter $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     * Get filter
     *
     * @return \RL\MainBundle\Entity\Filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

}
