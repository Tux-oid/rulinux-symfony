<?php
/**
 * @author Tux-oid
 */
namespace RL\MainBundle\Tests;
use RL\MainBundle\Entity\BbCode;

class BbCodeTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $bbCodeMark = new BbCode();
        $result = $bbCodeMark->render('[b]test[/b]');
        $this->assertEquals('<p><b>test</b></p>', $result);
    }
}
