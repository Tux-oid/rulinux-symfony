<?php
/**
 * @author Tux-oid
 */

namespace RL\ForumBundle\Helper;

interface ThreadHelperInterface
{
    public function saveThread(\Doctrine\Bundle\DoctrineBundle\Registry &$doctrine, \Symfony\Component\HttpFoundation\Request &$request, $section, $subsection, $user);
    public function preview(&$thread, \Symfony\Component\HttpFoundation\Request &$request);
}
