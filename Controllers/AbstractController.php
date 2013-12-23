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

	protected $_require_not_auth = false;
	protected $_not_auth_exceptions = array();

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
		$this->_checkAuthAccess($action->getId());
		$this->_checkNotAuthAccess($action->getId());
		return $result;
	}

	protected function _checkAuthAccess($action_id)
	{
		$access_manager = new AccessManager($action_id);

		$access_manager
			->setRequirements($this->_require_auth, $this->_auth_exceptions)
			->blockAccessIf(!$this->isAuth());

		if ($access_manager->isAccessBlocked())
		{
			return $this->redirect($this->createUrl($this->createUrl(Yii::app()->user->loginUrl)));
		}
	}

	protected function _checkNotAuthAccess($action_id)
	{
		$access_manager = new AccessManager($action_id);

		$access_manager
			->setRequirements($this->_require_not_auth, $this->_not_auth_exceptions)
			->blockAccessIf($this->isAuth());

		if ($access_manager->isAccessBlocked())
		{
			$this->redirect($this->createUrl('/Analyzes/dashboard'));
		}
	}
}