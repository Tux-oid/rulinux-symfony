<?php
/**
 * @author Tux-oid
 */
namespace RL\MainBundle\Tests;
use RL\MainBundle\Entity\WakabaMark;

class WakabaMarkTest extends \PHPUnit_Framework_TestCase
{
	public function testAdd()
	{
		$wakabaMark = new WakabaMark();
		$result = $wakabaMark->render('**test**');
		$this->assertEquals('<p><b>test</b></p>', $result);

	}
}
