<?php
namespace Extensions\Model;

use Iterator as IteratorInterface;
use Countable;

class Iterator implements IteratorInterface, Countable
{
	/**
	 * @var IteratorInterface
	 */
	private $_iterator;
	private $_class;

	public function __construct($class, IteratorInterface $iterator)
	{
		$this->_iterator = $iterator;
		$this->_class = $class;
	}

	public function current()
	{
		$item = $this->_iterator->current();
		$class = $this->_class;

		return new $class($item);
	}

	public function next()
	{
		$this->_iterator->next();
	}

	public function key()
	{
		return $this->_iterator->key();
	}

	public function valid()
	{
		return $this->_iterator->valid();
	}

	public function rewind()
	{
		$this->_iterator->rewind();
	}

	public function count()
	{
		return $this->_iterator->count();
	}
}