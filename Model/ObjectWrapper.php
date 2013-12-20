<?php
namespace Extensions\Model;

use Extensions\Models\Exceptions\WrongOutput;
use Extensions\Model\AbstractModel;
use Iterator as IteratorInterface;

class ObjectWrapper
{
	private $_model;
	private $_class;
	private $_is_list;

	public function __construct($class, AbstractModel $model, $is_list = false)
	{
		$this->_model = $model;
		$this->_class = $class;
		$this->_is_list = $is_list;
	}

	public function __call($name, $args)
	{
		$data = call_user_func_array(array($this->_model, $name), $args);

		if (!is_array($data) && !($data instanceof IteratorInterface))
		{
			throw new WrongOutput('The output must be Array or Iterator');
		}

		if (!$this->_is_list)
		{
			if (!$data) return null;

			$class = $this->_class;
			return new $class($data);
		}

		if ($data instanceof IteratorInterface)
		{
			return new Iterator($this->_class, $data);
		}

		$list = array();

		foreach ($data as $item)
		{
			$class = $this->_class;
			$list[]= new $class($item);
		}

		return $list;
	}
}