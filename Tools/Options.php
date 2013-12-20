<?php
namespace Extensions\Tools;

use Extensions\Exceptions\NotImplemented;
use Extensions\Tools\OptionsAssets\Model;
use Extensions\Tools\OptionsAssets\Exception;
class Options
{
	static private $_i;

	private $_model;
	private $_cache = array();

	static public function set($key, $value)
	{
		throw new NotImplemented(__METHOD__);
	}

	static public function get($key)
	{
		return self::_i()->getOpt($key);
	}

	static public function has($key)
	{
		throw new NotImplemented(__METHOD__);
	}

	static private function _i()
	{
		if (is_null(self::$_i))
		{
			self::$_i = new self();
		}

		return self::$_i;
	}

	public function __construct()
	{
		$this->_model = new Model();
	}

	public function getOpt($key)
	{
		if (!isset($this->_cache[$key]))
		{
			$opt = $this->_model->getByAlias($key);
			if (is_null($opt)) throw new Exception('The option <'.$key.'> is not found');
			$this->_cache[$key] = $opt;
		}

		return $this->_cache[$key];
	}
}