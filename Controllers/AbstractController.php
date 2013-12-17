<?php
namespace Extensions\Controllers;
use Extensions\Controllers\Helpers\Basic as BasicHelpers;
use CController;
use Yii;
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
abstract class AbstractController extends CController
{
	protected $_require_auth = true;
	protected $_auth_exceptions = array();

	public $layout='//layouts/main';

	public function init()
	{
		parent::init();
		$this->attachBehavior('basic_helpers', new BasicHelpers());
	}

	/**
	 * Отправить ответ об успехе
	 * @param array $data
	 */
	protected function ajaxSuccess($data = array())
	{
		echo json_encode(array('status' => 'success', 'data' => $data));
	}

	/**
	 * Отправить ответ об ошибке
	 * @param array $data
	 */
	protected function ajaxError($data = array())
	{
		echo json_encode(array('status' => 'error', 'data' => $data));
	}

	protected function isAjax()
	{
		return Yii::app()->request->isAjaxRequest;
	}

	public function beforeAction($action)
	{
		parent::beforeAction($action);

		if (!$this->_checkAuth())
		{
			return $this->redirect($this->createUrl($this->createUrl(Yii::app()->user->loginUrl)));
		}

		return true;
	}

	private function _checkAuth()
	{
		foreach ($this->_auth_exceptions as &$value)
		{
			$value = strtolower($value);
		}

		if ($this->_require_auth)
		{
			if (!in_array(strtolower($this->action->id), $this->_auth_exceptions) && !$this->isAuth())
			{
				return false;
			}
		}
		else
		{
			if (in_array(strtolower($this->action->id), $this->_auth_exceptions) && !$this->isAuth())
			{
				return false;
			}
		}

		return true;
	}

	protected function isAuth()
	{
		return !Yii::app()->user->isGuest;
	}
}