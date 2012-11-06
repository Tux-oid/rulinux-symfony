<?php
/**
 * @author Tux-oid
 */

namespace RL\MainBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;

class TrackerForm
{
	private $hours;

	public function __construct($hours)
	{
		$this->hours = $hours;
	}

	public function setHours($hours)
	{
		$this->hours = $hours;
	}

	public function getHours()
	{
		return $this->hours;
	}


}
