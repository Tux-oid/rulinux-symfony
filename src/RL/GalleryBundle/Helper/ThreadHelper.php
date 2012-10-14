<?php
/**
 * @author Tux-oid 
 */

namespace RL\GalleryBundle\Helper;
use RL\ForumBundle\Helper\ThreadHelperInterface;
use RL\GalleryBundle\Entity\Thread;
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
		$filesArr = $request->files->get('addThread');
		$thread->setFile($filesArr['file']);
		$em->persist($thread);
		$message = new Message();
		$message->setUser($user);
		$message->setReferer(0);
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