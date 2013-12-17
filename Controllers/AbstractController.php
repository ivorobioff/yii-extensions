<?php
namespace Extensions\Controllers;

use Extensions\Controllers\Helpers\AccessManager;
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

	public function beforeAction($action)
	{
		$result = parent::beforeAction($action);

		$auth_manager = new AccessManager();

		$pass_auth = $auth_manager
			->setActionId($action->id)
			->setRequireFlag($this->_require_auth)
			->setExceptionsList($this->_auth_exceptions)
			->setConditionFlag($this->isAuth())
			->canAccess();

		if (!$pass_auth)
		{
			return $this->redirect($this->createUrl($this->createUrl(Yii::app()->user->loginUrl)));
		}

		return $result;
	}
}