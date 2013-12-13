<?php
namespace Extensions\Applications;

require_once 'AbstractApplication.php';

class Cron extends AbstractApplication
{
	protected function _getApplicationInstance()
	{
		return \Yii::createConsoleApplication($this->_loadConfig('cron'));
	}
}