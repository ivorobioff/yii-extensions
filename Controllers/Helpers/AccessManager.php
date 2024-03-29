<?php
namespace Extensions\Controllers\Helpers;

class AccessManager
{
	private $_is_required = true;
	private $_exceptions_list = array();
	private $_false;
	private $_action_id;

	public function __construct($action_id)
	{
		$this->_action_id = $action_id;
	}

	public function setRequirements($is_required, $exceptions_list = array())
	{
		$this->_is_required = $is_required;
		$this->_exceptions_list = $exceptions_list;
		return $this;
	}

	public function blockAccessIf($false)
	{
		$this->_false = $false;
		return $this;
	}

	public function isAccessBlocked()
	{
		foreach ($this->_exceptions_list as &$value)
		{
			$value = strtolower($value);
		}

		if ($this->_is_required)
		{
			if (!in_array(strtolower($this->_action_id), $this->_exceptions_list) && $this->_false)
			{
				return true;
			}
		}
		else
		{
			if (in_array(strtolower($this->_action_id), $this->_exceptions_list) && $this->_false)
			{
				return true;
			}
		}

		return false;
	}
}