<?php
namespace Extensions\Model;

use Extensions\Model\ResultFormater;
abstract class AbstractModel
{
	/**
	 * @return \CDbCommand
	 */
	protected function _createQuery($sql = null)
	{
		return \Yii::app()->db->createCommand($sql);
	}

	protected function _formatResult($data)
	{
		return new ResultFormater($data);
	}
}