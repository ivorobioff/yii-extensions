<?php
namespace Extensions\Validator;

abstract class AbstractValidator
{
	protected $_errors = array();
	protected $_data = array();

	public function setData(array $data)
	{
		$this->_data = $data;
		return $this;
	}

	public function validate()
	{
		foreach ($this->_getFieldsConfig() as $name => $config)
		{
			foreach ($config as $validator => $error)
			{
				if ($this->_hasError($name)) continue ;

				$func = '_check'.$validator;

				if (method_exists($this, $func))
				{
					$this->$func($name, $error);
				}
			}
		}

		return $this;
	}

	final protected function _checkSettness($field, $error)
	{
		if (!isset($this->_data[$field]))
		{
			$this->_addError($field, $error);
			return ;
		}

		$value = trim($this->_data[$field]);

		if ($value == '') $this->_addError($field, $error);
	}

	final protected function _checkEmail($field, $error)
	{
		if (!$this->_hasValue($field)) return ;

		if (!preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $this->_data[$field]))
		{
			$this->_addError($field, $error);
		}
	}

	final protected function _checkNumber($field, $error)
	{
		if (!$this->_hasValue($field)) return ;

		if (!is_numeric($this->_data[$field]))
		{
			$this->_addError($field, $error);
		}
	}

	final protected function _checkLength($field, $config)
	{
		if (!$this->_hasValue($field)) return ;

		$error = $config['error'];
		$max = $config['max'];
		$min = $config['min'];

		$len = strlen($this->_data[$field]);

		if ($len < $min || $len > $max)
		{
			$this->_addError($field, $error);
		}
	}

	final protected function _checkInList($field, $config)
	{
		if (!$this->_hasValue($field)) return ;

		$error = $config['error'];
		$list = $config['list'];

		if (!in_array($this->_data[$field], $list))
		{
			$this->_addError($field, $error);
		}
	}

	final protected function _checkDateFormat($field, $config)
	{
		if (!$this->_hasValue($field)) return ;

		$date_object = new \DateTime();

		$formated_date = $date_object->createFromFormat($config['format'], $this->_data[$field]);

		if (!$formated_date || $formated_date->format($config['format']) != $this->_data[$field])
		{
			$this->_addError($field, $config['error']);
		}
	}

	abstract protected function _getFieldsConfig();

	public function getErrors()
	{
		return $this->_errors;
	}

	protected function _addError($field, $error)
	{
		$this->_errors[$field] = $error;
	}

	protected function _hasValue($field)
	{
		if (!isset($this->_data[$field])) return false;
		if ($this->_data[$field] == '') return false;

		return true;
	}

	protected function _hasError($name)
	{
		return isset($this->_errors[$name]);
	}
}