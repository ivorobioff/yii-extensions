<?php
namespace Extensions\Model;

class ResultFormater
{
	private $_data;

	public function __construct($data)
	{
		$this->_data = $data;
	}

	public function getVector($field)
	{
		$return = array();

		foreach ($this->_data as $value)
		{
			$return[] = $value[$field];
		}

		return $return;
	}

	public function getValue($key, $default = false)
	{
		return setif($this->_data, $key, $default);
	}

	public function getHash($key = 'id', $value = null)
	{
		$return = array();

		foreach ($this->_data as $values)
		{
			$return[$values[$key]] = is_null($value) ? $values : $values[$value];
		}

		return $return;
	}
}