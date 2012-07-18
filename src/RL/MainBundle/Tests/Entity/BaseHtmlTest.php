<?php
/**
 * @author Tux-oid
 */
namespace RL\MainBundle\Tests;
use RL\MainBundle\Entity\BaseHtml;

class BaseHtmlTest extends \PHPUnit_Framework_TestCase
{
	public function testAdd()
	{
		$baseHtmlMark = new BaseHtml();
		$result = $baseHtmlMark->render('<b>test</b>');
		$this->assertEquals('<p><b>test</b></p>', $result);
	}
}
