<?php
namespace Extensions\Utils;

class PeriodIterator implements \Iterator
{
	private $_year_from;
	private $_month_from;

	private $_year_to;
	private $_month_to;

	private $_year;
	private $_month;

	private $_days = array(
		1 => 31,
		2 => 28,
		3 => 31,
		4 => 30,
		5 => 31,
		6 => 30,
		7 => 31,
		8 => 31,
		9 => 30,
		10 => 31,
		11 => 30,
		12 => 31
	);

	private $_absolute_months = 1;

	private $_is_valid = true;

	public function __construct($date_from, $date_to)
	{
		$this->_year_from = date('Y', strtotime($date_from));
		$this->_year_to = date('Y', strtotime($date_to));

		$this->_month_from = intval(date('m', strtotime($date_from)));
		$this->_month_to = intval(date('m', strtotime($date_to)));

		$this->_year = $this->_year_from;
		$this->_month = $this->_month_from;
	}

	public function getYear()
	{
		return $this->_year;
	}

	public function getTotalDays()
	{
		return $this->_days[$this->_month];
	}

	public function current()
	{
		return $this->_month;
	}

	public function next()
	{
		if (($this->_month + 1) > $this->_month_to && $this->_year == $this->_year_to) $this->_is_valid = false;

		$this->_month ++;

		if ($this->_month > 12)
		{
			$this->_year ++;
			$this->_month = 1;
		}

		$this->_absolute_months ++;
	}

	public function key()
	{
		return $this->_absolute_months;
	}

	public function valid()
	{
		return $this->_is_valid;
	}

	public function rewind()
	{
		$this->_year = $this->_year_from;
		$this->_month = $this->_month_from;
		$this->_absolute_months = 1;
		$this->_is_valid = true;
	}
}