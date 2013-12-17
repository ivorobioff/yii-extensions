<?php
namespace Extensions\Controllers\Helpers;

class AccessManager
{
	private $_require_flag;
	private $_exceptions_list = array();
	private $_condition_flag;
	private $_action_id;

	public function setRequireFlag($flag)
	{
		$this->_require_flag = $flag;
		return $this;
	}

	public function setExceptionsList(array $actions_list)
	{
		$this->_exceptions_list = $actions_list;
		return $this;
	}

	public function setConditionFlag($flag)
	{
		$this->_condition_flag = $flag;
		return $this;
	}

	public function setActionId($id)
	{
		$this->_action_id = $id;
		return $this;
	}

	public function canAccess()
	{
		foreach ($this->_exceptions_list as &$value)
		{
			$value = strtolower($value);
		}

		if ($this->_require_flag)
		{
			if (!in_array(strtolower($this->_action_id), $this->_exceptions_list) && !$this->_condition_flag)
			{
				return false;
			}
		}
		else
		{
			if (in_array(strtolower($this->_action_id), $this->_exceptions_list) && !$this->_condition_flag)
			{
				return false;
			}
		}

		return true;
	}
}