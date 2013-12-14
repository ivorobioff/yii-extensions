<?php
namespace Extensions\JsComposer;

use Extensions\JsComposer\Exceptions\ErrorLoadingBootstrap;
use Extensions\JsComposer\Exceptions\ErrorSavingFile;
use Extensions\JsComposer\Exceptions\ErrorLoadingClass;
use Extensions\JsComposer\Exceptions\NoStart;

/**
 * @author Igor Vorobioff<i_am_vib@yahoo.com>
 */
class Composer
{
	private $_bootstraps = array();
	private $_config;

	private $_classes = array();

	/**
	 * @param string $filename - имя файла бутстрапа
	 */
	public function __construct($config)
	{
		$this->_config = $config;
	}

	public function addBootstrap($filename)
	{
		$this->_bootstraps[] = $filename;
		return $this;
	}

	public function process()
	{
		if (!$this->_bootstraps) throw new NoStart();

		$classes = array();

		foreach ($this->_bootstraps as $bootstrap)
		{
			$classes = array_merge($classes, $this->_getBootstrapClasses($bootstrap));
		}

		if (!$classes) throw new NoStart();

		$this->_loadClasses($classes);

		return $this;
	}

	public function save($filename)
	{
		$this->_classes = array_reverse($this->_classes);

		$result = '';

		foreach ($this->_classes as $class)
		{
			$result .= $this->_getFileContentByClass($class)."\n";
		}

		$path = $this->_config['bin_path'].'/'.$filename;

		if (file_put_contents($path, $result) === false)
		{
			throw new ErrorSavingFile('Error saving the file "'.$path.'"');
		}
	}


	private function _getBootstrapClasses($filename)
	{
		$path = $this->_config['app_path'].'/bootstrap/'.$filename;

		if (!is_readable($path))
		{
			throw new ErrorLoadingBootstrap('The bootstrap file "'.$filename.'" MUST be readable');
		}

		$content = file_get_contents($path);

		if ($content === false)
		{
			throw new ErrorLoadingBootstrap('Error loading the bootstrap file "'.$filename.'"');
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
		$file = $this->_config['app_path'].'/'.str_replace('.', '/', $class).'.js';

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
