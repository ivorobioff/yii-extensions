<?php
namespace Extensions\Handbook;

use Extensions\Model\AbstractModel;

class Countries extends AbstractModel
{
	public function getList()
	{
		$data = $this->_createQuery()->from('hb_countries')->queryAll(true);
		return $this->_formatResult($data)->getHash('id', 'name');
	}

	public function getIdByName($name)
	{
		$data = $this->_createQuery()
			->from('hb_countries')
			->where('name=:name', array(':name' => $name))
			->queryRow(true);

		return $this->_formatResult($data)->getValue('id');
	}
}