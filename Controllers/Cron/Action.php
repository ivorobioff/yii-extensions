<?php
namespace Extensions\Controllers\Cron;

use Extensions\Controllers\Cron\Controller;

class Action extends \CComponent implements \IAction
{
	private $_controller;
	private $_id;

	public function __construct(Controller $controller, $id)
	{
		$this->_controller = $controller;
		$this->_id = $id;
	}

	public function getController()
	{
		return $this->_controller;
	}

	public function getId()
	{
		return $this->_id;
	}
}