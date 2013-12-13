<?php
namespace Extensions\Singleton;

abstract class AbstractSingleton
{
	static protected $_instances;

	static public function i()
	{
		$class = get_called_class();

		if (!isset(static::$_instances[$class]))
		{
			static::$_instances[$class] = new static();
		}

		return static::$_instances[$class];
	}
}