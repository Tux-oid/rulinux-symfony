<?php
/**
 * @author Tux-oid
 */
namespace RL\SecurityBundle\Form;

class AdministratorSettingsForm
{
	/**
	 * @var
	 */
	private $group;

	/**
	 * @param $groups
	 */
	public function setGroup($group)
	{
		$this->group = $group;
	}

	/**
	 * @return mixed
	 */
	public function getGroup()
	{
		return $this->group;
	}
}
