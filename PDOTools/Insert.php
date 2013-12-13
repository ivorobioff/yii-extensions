<?php
namespace Extensions\PDOTools;
/**
 * @author Igor Vorobioff<igor.vorobioff@gmail.com>
 */
class Insert
{
	private $_table_name;

	private $_duplicate_sql = '';
	private $_duplicate_params = array();

	private $_this_data = array();
	private $_these_data = array();

	static public function into($table_name)
	{
		return new self($table_name);
	}

	public function __construct($table_name)
	{
		$this->_table_name = $table_name;
	}

	public function run()
	{
		if ($this->_this_data)
		{
			$this->_insert();
		}
		else
		{
			$this->_insertAll();
		}

		return \Yii::app()->db->getLastInsertID();
	}

	public function thisData(array $data)
	{
		$this->_this_data = $data;
		return $this;
	}

	public function theseData(array $data)
	{
		$this->_these_data = $data;
		return $this;
	}

	private function _insert()
	{
		$real_keys = array_keys($this->_this_data);

		$values = implode(',', array_fill(0, count($real_keys), '?'));

		$sql = 'INSERT INTO `'.$this->_table_name.'` ('.$this->_prepareKeys($real_keys).') VALUES('.$values.') '.$this->_duplicate_sql;

		$command = \Yii::app()->db->createCommand($sql);

		$this->_bindRow($command, $this->_this_data, 1);

		$command->execute($this->_duplicate_params);

		$this->_clear();
	}

	private function _insertAll()
	{
		reset($this->_these_data);
		$row = current($this->_these_data);
		$real_keys = array_keys($row);

		$values = '';
		$d = '';

		foreach ($this->_these_data as $row)
		{
			$values .= $d.'('.implode(',', array_fill(0, count($row), '?')).')';
			$d = ',';
		}

		$sql = 'INSERT INTO `'.$this->_table_name.'` ('.$this->_prepareKeys($real_keys).') VALUES'.$values.' '.$this->_duplicate_sql;

		$command = \Yii::app()->db->createCommand($sql);

		$counter = 1;

		foreach ($this->_these_data as $row)
		{
			$this->_bindRow($command, $row, $counter);
			$counter += count($row);
		}

		$command->execute($this->_duplicate_params);

		$this->_clear();
	}

	public function ifDuplicate($sql, array $params = array())
	{
		$this->_duplicate_params = $params;
		$this->_duplicate_sql = 'ON DUPLICATE KEY UPDATE '.$sql;
		return $this;
	}

	private function _prepareKeys(array $keys)
	{
		$str = '';
		$d = '';

		foreach ($keys as $key)
		{
			$str .= $d.'`'.$key.'`';
			$d = ', ';
		}

		return $str;
	}

	private function _bindRow(\CDbCommand $command, array $row, $counter_start)
	{
		foreach ($row as $value)
		{
			$command->bindValue($counter_start, $value);
			$counter_start ++;
		}
	}

	private function _clear()
	{
		$this->_this_data = array();
		$this->_these_data = array();
		$this->_duplicate_sql = '';
		$this->_duplicate_params = array();
	}
}