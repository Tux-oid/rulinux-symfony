<?php
/**
 * @author Tux-oid
 */
namespace RL\MainBundle\Tests;
use RL\MainBundle\Entity\TexMark;

class texMarkTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $texMark = new TexMark();
        $result = $texMark->render('\b{test}');
        $this->assertEquals('<p><b>test</b></p>', $result);
    }
}
