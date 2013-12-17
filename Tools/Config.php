<?php
namespace Extensions\Tools;

class Config
{
	static public function get($path)
	{
		$keys = explode('/', trim($path, '/'));

		$value = \Yii::app()->params;

		foreach ($keys as $key)
		{
			$value = $value[$key];
		}

		return $value;
	}
}