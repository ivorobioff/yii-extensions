<?php
namespace Extensions\Cron;

use Extensions\Controllers\Cron\Action;

class Runner
{
	public function run($path)
	{
		$parts = explode('/', trim($path, '/'));
		if (count($parts) < 3) $parts[] = 'index';

		$class = '\Modules\\'.$parts[0].'\Controllers\\'.ucfirst($parts[1]).'Cron';

		$controller = new $class();
		$action = new Action($controller, $parts[2]);
		$controller->init();
		$controller->beforeAction($action);

		unset($parts[0], $parts[1], $parts[2]);
		call_user_func_array(array($controller, 'action'.$action->getId()), $parts);
	}
}