<?php
namespace Extensions\Model;

use Extensions\Model\ResultFormater;
abstract class AbstractModel
{
	/**
	 * @return \CDbCommand
	 */
	protected function _createQuery()
	{
		return \Yii::app()->db->createCommand();
	}

	protected function _formatResult($data)
	{
		return new ResultFormater($data);
	}
}