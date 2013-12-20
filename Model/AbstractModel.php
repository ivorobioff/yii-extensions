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

	public function getObjectsList($class)
	{
		return new ObjectWrapper($class, $this, true);
	}

	public function getObject($class)
	{
		return new ObjectWrapper($class, $this);
	}
}