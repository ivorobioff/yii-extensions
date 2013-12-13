<?php
namespace Extensions\Applications;

require_once 'AbstractApplication.php';

class Web extends AbstractApplication
{
	protected function _getApplicationInstance()
	{
		return \Yii::createWebApplication($this->_loadConfig('web'));
	}
}