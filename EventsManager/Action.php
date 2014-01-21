<?php
namespace Extensions\EventsManager;

use Extensions\EventsManager\Controller;

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