<?php
/**
 * @author Tux-oid 
 */

namespace RL\NewsBundle\Helper;
use RL\ForumBundle\Helper\ThreadHelperInterface;
use RL\NewsBundle\Entity\Thread;
use RL\ForumBundle\Entity\Message;

class ThreadHelper implements ThreadHelperInterface
{
	public function saveThread(\Symfony\Bundle\DoctrineBundle\Registry &$doctrine, \Symfony\Component\HttpFoundation\Request &$request, $section, $subsection, $user)
	{
		$em = $doctrine->getEntityManager();
		$thr = $request->request->get('addThread');
		$threadCls = $section->getBundleNamespace().'\Entity\Thread';
		$thread = new $threadCls();
		$thread->setSubsection($subsection);
		$thread->setProoflink($thr['prooflink']);
		$em->persist($thread);
		$message = new Message();
		$message->setUser($user);
		$message->setReferer(0);
		$message->setSubject($thr['subject']);
		$message->setComment($thr['comment']);
		$message->setRawComment($thr['comment']);
		$message->setThread($thread);
		$em->persist($message);
		$em->flush();
	}
}
?>
