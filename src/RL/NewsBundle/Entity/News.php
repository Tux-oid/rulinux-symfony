<?php
/**
 * @author Tux-oid 
 */

namespace RL\NewsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use RL\ArticlesBundle\Entity\Article;

/**
 * @ORM\Entity(repositoryClass="RL\ArticlesBundle\Entity\ArticleRepository") 
 * @ORM\Table(name="news")
 */
class News extends Article
{
	/**
	 * @ORM\Column(type="string", length="2048", nullable="true")
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
