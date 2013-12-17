<?php
namespace Extensions\Tools;

use Yii;

class Log
{
	private $_path;
	private $_file;

	public function __construct($path)
	{
		$path = str_replace(array('{date}', '{time}'), array(date('Y-m-d'), date('H:i:s')), $path);

		$parts = pathinfo($path);

		$this->_path = Yii::app()->basePath.'/runtime/'.trim($parts['dirname'], '/');
		$this->_file = $parts['basename'];
	}

	public function saveMessage($message)
	{
		if (!is_dir($this->_path))
		{
			mkdir($this->_path, 0755, true);
		}

		$message = '['.date('Y-m-d H:i:s').'] '.$message."\r\n";

		file_put_contents($this->_path.'/'.$this->_file, $message, FILE_APPEND);
	}
}