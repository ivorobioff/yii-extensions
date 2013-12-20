<?php
namespace Extensions\Model;

abstract class AbstractObject
{
	protected $_data;

	public function __construct(array $data)
	{
		$this->_data = $data;
	}

	public function __get($key)
	{
		return $this->_data[$key];
	}

	public function toArray()
	{
		return $this->_data;
	}
}