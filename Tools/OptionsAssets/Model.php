<?php
namespace Extensions\Tools\OptionsAssets;

use Extensions\Model\AbstractModel;

class Model extends AbstractModel
{
	public function getByAlias($alias)
	{
		$data = $this->_createQuery()
			->from('options')
			->where('alias=:alias', array(':alias' => $alias))
			->queryRow(true);

		return $this->_formatResult($data)->getValue('value', null);
	}
}