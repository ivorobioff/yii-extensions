<?php
namespace Extensions\Utils;

class BitMask
{
	private $_value;

	public function __construct($value)
	{
		$this->_value = $value;
	}

	public function add($item)
	{
		$this->_value |=$this->_pow($item);
	}

	public function remove($item)
	{
		$this->_value ^=$this->_pow($item);
	}

	public function has($item)
	{
		return (bool) ($this->_value & $this->_pow($item));
	}

	public function getResult()
	{
		return $this->_value;
	}

	private function _pow($item)
	{
		return pow(2, $item);
	}
}