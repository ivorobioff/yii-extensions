<?php
namespace Extensions\Controllers\Cron;

use Extensions\Controllers\Cron\Action;

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