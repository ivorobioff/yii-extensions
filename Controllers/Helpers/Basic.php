<?php
namespace Extensions\Controllers\Helpers;

use Extensions\JsComposer\Composer;
use Extensions\JsComposer\Exceptions\NoStart;
use CBehavior;
use Yii;

class Basic extends CBehavior
{
	/**
	 * Check whether the passed location is the current one.
	 * @param string $path
	 * @return boolean
	 */

	public function isLocation($path)
	{
		$path = explode('/', trim($path, '/'));

		if (count($path) < 3)
		{
			array_unshift($path, '');
		}

		$module = $path[0];
		$controller = $path[1];
		$action = $path[2];

		$real_module = !is_null(Yii::app()->controller->module) ? Yii::app()->controller->module->id : '';
		$real_controller = Yii::app()->controller->id;
		$real_action = Yii::app()->controller->action->id;

		$pass_module = preg_match('/^'.$module.'$/', $real_module);
		$pass_controller = preg_match('/^'.$controller.'$/', $real_controller);
		$pass_action = preg_match('/^'.$action.'$/', $real_action);

		return $pass_module && $pass_controller && $pass_action;
	}

	public function loadJs($common_bootstrap = null)
	{
		$module = strtolower(!is_null(Yii::app()->controller->module) ? Yii::app()->controller->module->id : '');
		$controller = strtolower(Yii::app()->controller->id);
		$action = strtolower(Yii::app()->controller->action->id);

		if ($action == 'index') $action = '';

		$bootstrap_name = trim($module.'-'.$controller.'-'.$action, '-');
		
		$bin = md5($bootstrap_name);

		if (Yii::app()->params['is_production'])
		{
			return '<script src="'.Yii::app()->request->baseUrl.'/js/app/bin/'.$bin.'.js"></script>';
		}

		$bootstrap_file = $bootstrap_name.'.js';
		$config = Yii::app()->params['js_composer'];

		$composer = new Composer($config);

		if ($common_bootstrap)
		{
			$composer->addBootstrap($common_bootstrap);
		}

		if (is_readable($config['bin_path'].'/'.$bootstrap_file))
		{
			$composer->addBootstrap($bootstrap_file);
		}

		try
		{
			$composer->process()->save($bin.'.js');
		}
		catch (NoStart $ex)
		{
			return '';
		}

		return '<script src="'.Yii::app()->request->baseUrl.'/js/app/bin/'.$bin.'.js"></script>';
	}

	public function setif(array $array, $key = null)
	{
		return setif($array, $key);
	}
}
