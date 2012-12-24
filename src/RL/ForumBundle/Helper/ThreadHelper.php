<?php
/**
 * @author Tux-oid 
 */

namespace RL\ForumBundle\Helper;
use RL\ForumBundle\Helper\ThreadHelperInterface;
use RL\ForumBundle\Entity\Thread;
use RL\ForumBundle\Entity\Message;

class ThreadHelper implements ThreadHelperInterface
{
	public function saveThread(\Doctrine\Bundle\DoctrineBundle\Registry &$doctrine, \Symfony\Component\HttpFoundation\Request &$request, $section, $subsection, $user)
	{
		$em = $doctrine->getManager();
		$thr = $request->request->get('addThread');
		$threadCls = $section->getBundleNamespace().'\Entity\Thread';
		$thread = new $threadCls();
		$thread->setSubsection($subsection);
		$em->persist($thread);
		$message = new Message();
		if($user->isAnonymous())
		{
			$user = $user->getDbAnonymous();
		}
		$message->setUser($user);
		$message->setSubject($thr['subject']);
		$message->setComment($user->getMark()->render($thr['comment']));
		$message->setRawComment($thr['comment']);
		$message->setThread($thread);
		$em->persist($message);
		$em->flush();
	}
	public function preview(&$thread, \Symfony\Component\HttpFoundation\Request &$request)
	{
		$prv_thr = $request->request->get('addThread');
		$thread->setSubject($prv_thr['subject']);
		$thread->setComment($prv_thr['comment']);
	}
}
?>
