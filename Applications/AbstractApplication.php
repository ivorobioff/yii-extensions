<?php
namespace Extensions\Applications;

abstract class AbstractApplication
{
	protected $_root_dir;

	public function __construct($root_dir)
	{
		$this->_root_dir = $root_dir;

		defined('YII_DEBUG') or define('YII_DEBUG', true);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

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

		$config = $this->_mergeConfig($config, $app_config);

		$local_config = $this->_root_dir.'/protected/config/local/'.$alias.'.php';

		if (file_exists($local_config))
		{
			$local_config = include $local_config;
			$config = $this->_mergeConfig($config, $local_config);
		}

		return $config;
	}

	private function _mergeConfig(array $config1, array $config2)
	{
		foreach ($config2 as $index => $item)
		{
			if (!empty($config1[$index]) && is_array($config1[$index]) && is_array($item))
			{
				$config1[$index] = $this->_mergeConfig($config1[$index], $item);
			}
			else
			{
				if (is_numeric($index))
				{
					if (array_search($item, $config1) === false)
					{
						$config1[] = $item;
					}
				}
				else
				{
					$config1[$index] = $item;
				}
			}
		}

		return $config1;
	}

	abstract protected function _getApplicationInstance();
}