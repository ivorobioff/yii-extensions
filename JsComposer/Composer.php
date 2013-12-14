<?php
namespace Extensions\JsComposer;

use Extensions\JsComposer\Exceptions\ErrorLoadingBootfile;
use Extensions\JsComposer\Exceptions\ErrorSavingFile;
use Extensions\JsComposer\Exceptions\ErrorLoadingClass;

/**
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Composer
{
	private $_bootfiles = array();
	private $_classes_dir;

	private $_classes = array();

	/**
	 * @param string $filename - имя файла бутстрапа
	 */
	public function __construct($classes_dir)
	{
		$this->_classes_dir = $classes_dir;
	}

	public function addBootfile($filename)
	{
		$this->_bootfiles[] = $filename;
		return $this;
	}

	public function process($filename)
	{
		if (!$this->_bootfiles) return false;

		$classes = array();

		foreach ($this->_bootfiles as $bootfile)
		{
			$classes = array_merge($classes, $this->_getBootClasses($bootfile));
		}

		if (!$classes) return false;

		$this->_loadClasses($classes);
		$this->_save($filename);

		return true;
	}

	private function _save($filename)
	{
		$this->_classes = array_reverse($this->_classes);

		$result = '';

		foreach ($this->_classes as $class)
		{
			$result .= $this->_getFileContentByClass($class)."\n";
		}

		if (file_put_contents($filename, $result) === false)
		{
			throw new ErrorSavingFile('Error saving the file "'.$filename.'"');
		}
	}


	private function _getBootClasses($filename)
	{
		if (!is_readable($filename))
		{
			throw new ErrorLoadingBootfile('The boot-file "'.$filename.'" MUST be readable');
		}

		$content = file_get_contents($filename);

		if ($content === false)
		{
			throw new ErrorLoadingBootfile('Error loading the boot-file "'.$filename.'"');
		}

		return $this->_parseHeader($content);
	}

	private function _loadClasses($classes)
	{
		$classes = array_unique($classes);

		foreach ($classes as $class)
		{
			$key_class = array_search($class, $this->_classes);

			if ($key_class !== false)
			{
				unset($this->_classes[$key_class]);
			}

			$this->_classes[] = $class;

			$content = $this->_getFileContentByClass($class);
			$parent_classes = $this->_parseHeader($content);

			if (!$parent_classes) continue ;

			$this->_loadClasses($parent_classes);
		}
	}

	private function _parseHeader($file)
	{
		$loads = array();

		$begin = strpos($file, '/**');
		$end = strpos($file, '*/');

		$header = substr($file, $begin, ($end - $begin) + 1);

		if (!preg_match_all('/@load [a-zA-Z\.]*/s', $header, $loads))
		{
			return array();
		}

		$loads = $loads[0];

		foreach ($loads as &$value)
		{
			$value = trim(ltrim($value, '@load'));
		}

		return $loads;

	}

	private function _getFileContentByClass($class)
	{
		$file = $this->_classes_dir.'/'.str_replace('.', '/', $class).'.js';

		if (!is_readable($file))
		{
			throw new ErrorLoadingClass('The class file "'.$file.'" MUST be readable');
		}

		$content = file_get_contents($file);

		if ($content === false)
		{
			throw new ErrorLoadingClass('Error loading the class "'.$class.'"');
		}

		return $content;
	}
}
