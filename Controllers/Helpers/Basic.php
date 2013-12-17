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

	public function loadJs($common_bootfile = null)
	{
		$module = strtolower(!is_null(Yii::app()->controller->module) ? Yii::app()->controller->module->id : '');
		$controller = strtolower(Yii::app()->controller->id);
		$action = strtolower(Yii::app()->controller->action->id);

		if ($action == 'index') $action = '';

		$bootfile_name = trim($module.'-'.$controller.'-'.$action, '-');

		$bin = md5($bootfile_name);

		if (Yii::app()->params['is_production'])
		{
			return '<script src="'.Yii::app()->request->baseUrl.'/js/app/bin/'.$bin.'.js"></script>';
		}

		$config = Yii::app()->params['js_composer'];
		$bootfile = $config['boot'].'/'.$bootfile_name.'.js';

		$composer = new Composer($config['classes']);

		if ($common_bootfile)
		{
			$composer->addBootfile($config['boot'].'/'.$common_bootfile);
		}

		if (is_readable($bootfile))
		{
			$composer->addBootfile($bootfile);
		}

		if (!$composer->process($config['bin'].'/'.$bin.'.js'))
		{
			return '';
		}

		return '<script src="'.Yii::app()->request->baseUrl.'/js/app/bin/'.$bin.'.js"></script>';
	}

	public function setif(array $array, $key = null)
	{
		return setif($array, $key);
	}

	public function buildMonthsList()
	{
		return array(
			1 => 'January',
		    2 => 'February',
		    3 => 'March',
		    4 => 'April',
		    5 => 'May',
		    6 => 'June',
		    7 => 'July ',
		    8 => 'August',
		    9 => 'September',
		    10 => 'October',
		    11 => 'November',
		    12 => 'December',
		);
	}

	public function isAuth()
	{
		return !Yii::app()->user->isGuest;
	}

	public function isAjax()
	{
		return Yii::app()->request->isAjaxRequest;
	}
}
