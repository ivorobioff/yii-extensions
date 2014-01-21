<?php
namespace Extensions\EventsManager;

use Extensions\EventsManager\Action;

class Event
{
	static public function trigger($path)
	{
		$modules = array_keys(\Yii::app()->getModules());

		foreach ($modules as $module)
		{
			$parts = explode('/', trim($path, '/'));

			$class = '\Modules\\'.$module.'\Controllers\\'.ucfirst($parts[0]).'Event';
			$method = 'on'.$parts[1];

			if (!@class_exists($class)) continue ;

			$ref = new \ReflectionClass($class);
			if (!$ref->hasMethod($method)) continue ;

			$controller = new $class();
			$action = new Action($controller, $parts[1]);
			$controller->init();
			$controller->beforeAction($action);

			$args = func_get_args();
			unset($args[0]);

			call_user_func_array(array($controller, $method), $args);
		}
	}
}