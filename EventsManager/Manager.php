<?php
namespace Extensions\EventsManager;

use CComponent;

class Manager extends CComponent
{
	private $_events;

	public function init()
	{
		//
	}

	public function setEvents(array $events)
	{
		$this->_events = $events;
	}

	public function trigger($name, $param = null)
	{
		if (!$listeners = setif($this->_events, $name)) return ;

		foreach ($listeners as $listener)
		{
			$listener($param);
		}
	}
}