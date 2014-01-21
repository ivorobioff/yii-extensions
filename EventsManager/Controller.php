<?php
namespace Extensions\EventsManager;

use Extensions\EventsManager\Action;

abstract class Controller extends \CComponent
{
	public function init()
	{
		//
	}

	public function beforeAction(Action $action)
	{
		//
	}
}