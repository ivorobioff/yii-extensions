<?php
namespace Extensions\Applications;

abstract class AbstractApplication
{
	protected $_root_dir;

	public function __construct($root_dir)
	{
		$this->_root_dir = $root_dir;

		require_once $this->_root_dir.'/../share/extensions/functions.php';
		require_once $this->_root_dir.'/../share/yii-framework/yii.php';
	}

	public function run()
	{
		$this->_getApplicationInstance()->run();
	}

	protected function _loadConfig($alias)
	{
		$config = include $this->_root_dir.'/protected/config/base.php';
		$app_config = include $this->_root_dir.'/protected/config/'.$alias.'.php';

		$config = \CMap::mergeArray($config, $app_config);

		$local_base_config = $this->_root_dir.'/protected/config/local/base.php';
		if (file_exists($local_base_config))
		{
			$local_base_config = include $local_base_config;
			$config = \CMap::mergeArray($config, $local_base_config);
		}

		$local_config = $this->_root_dir.'/protected/config/local/'.$alias.'.php';

		if (file_exists($local_config))
		{
			$local_config = include $local_config;
			$config = \CMap::mergeArray($config, $local_config);
		}

		return $config;
	}

	abstract protected function _getApplicationInstance();
}