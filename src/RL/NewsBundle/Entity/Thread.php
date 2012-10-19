<?php
/**
 * @author Tux-oid 
 */

namespace RL\NewsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use RL\ArticlesBundle\Entity\Thread as ArticlesThread;

/**
 * @ORM\Entity(repositoryClass="RL\NewsBundle\Entity\ThreadRepository") 
 * @ORM\Table(name="news")
 */
class Thread extends ArticlesThread
{
	/**
	 * @ORM\Column(type="string", length=2048, nullable=true)
	 */
	protected $prooflink;
	public function getProoflink()
	{
		return $this->prooflink;
	}
	public function setProoflink($prooflink)
	{
		$this->prooflink = $prooflink;
	}
}
?>
