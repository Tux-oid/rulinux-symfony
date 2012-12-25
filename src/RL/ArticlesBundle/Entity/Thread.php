<?php
/**
 * @author Tux-oid
 */

namespace RL\ArticlesBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use RL\ForumBundle\Entity\Thread as ForumThread;

/**
 * @ORM\Entity(repositoryClass="RL\ArticlesBundle\Entity\ThreadRepository")
 * @ORM\Table(name="articles")
 */
class Thread extends ForumThread
{
    /**
     * @ORM\Column(type="boolean", name="approved")
     */
    protected $approved = false;
    /**
     * @ORM\ManyToOne(targetEntity="RL\SecurityBundle\Entity\User")
     */
    protected $approvedBy;
    /**
     * @ORM\Column(type="datetime", name="approve_timest", nullable=true)
     */
    protected $approveTimest;
    public function getApproved()
    {
        return $this->approved;
    }
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }
    public function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;
    }
    public function getApproveTimest()
    {
        return $this->approveTimest;
    }
    public function setApproveTimest($approveTimest)
    {
        $this->approveTimest = $approveTimest;
    }
}
