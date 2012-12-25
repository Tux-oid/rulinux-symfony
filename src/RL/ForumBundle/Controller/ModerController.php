<?php
/**
 * @author Tux-oid
 */
namespace RL\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ModerController extends Controller
{
    /**
     * @Route("/approve_thread_{id}", name="approve_thread", requirements = {"id"="[0-9]+"})
     */
    public function approveThreadAction($id)
    {
        $theme = $this->get('rl_themes.theme.provider');
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        if (!$securityContext->isGranted('ROLE_MODER')) {
            $legend = 'Access denied';
            $title = 'Edit message';
            $text = 'You have not privelegies to edit this message';

            return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array(
                    'theme' => $theme,
                    'user' => $user,
                    'legend' => $legend,
                    'title' => $title,
                    'text' => $text,
                ));
        }
        $doctrine = $this->get('doctrine');
        $em = $doctrine->getEntityManager();
        $thread = $doctrine->getRepository('RLForumBundle:Thread')->findOneById($id);
        if (null === $thread) {
            $legend = 'Thread not found';
            $title = 'Thread not found';
            $text = 'Thread with specified id isn\'t found';

            return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array(
                    'theme' => $theme,
                    'user' => $user,
                    'legend' => $legend,
                    'title' => $title,
                    'text' => $text,
                ));
        }
        $thread->setApproved(true);
        $thread->setApprovedBy($user);
        $thread->setApproveTimest(new \DateTime());
        $em->flush();

        return $this->redirect($this->generateUrl("unconfirmed", array()));
    }

    /**
     * @Route("/attach_thread_{id}_{state}", name="attach_thread", requirements = {"id"="[0-9]+", "state"="true|false"})
     */
    public function attachThreadAction($id, $state)
    {
        $theme = $this->get('rl_themes.theme.provider');
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        if (!$securityContext->isGranted('ROLE_MODER')) {
            $legend = 'Access denied';
            $title = 'Edit message';
            $text = 'You have not privelegies to edit this message';

            return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array(
                    'theme' => $theme,
                    'user' => $user,
                    'legend' => $legend,
                    'title' => $title,
                    'text' => $text,
                ));
        }
        $doctrine = $this->get('doctrine');
        $em = $doctrine->getEntityManager();
        $thread = $doctrine->getRepository('RLForumBundle:Thread')->findOneById($id);
        if (null === $thread) {
            $legend = 'Thread not found';
            $title = 'Thread not found';
            $text = 'Thread with specified id isn\'t found';

            return $this->render($theme->getPath('RLMainBundle', 'fieldset.html.twig'), array(
                    'theme' => $theme,
                    'user' => $user,
                    'legend' => $legend,
                    'title' => $title,
                    'text' => $text,
                ));
        }
        if ($state == "true") {
            $thread->setAttached(true);
        } else {
            $thread->setAttached(false);
        }
        $em->flush();

        return $this->redirect($this->generateUrl("subsection", array('sectionRewrite'=>$thread->getSubsection()->getSection()->getRewrite(), 'subsectionRewrite'=>$thread->getSubsection()->getRewrite())));
    }

}
