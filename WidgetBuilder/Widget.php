<?php
namespace Extensions\WidgetBuilder;

class Widget extends Component
{
	private $_components = array();

	public function addComponent(Component $component)
	{
		$this->_components[$component->getId()] = $component;
	}

	public function getComponent($id)
	{
		return $this->_components[$id];
	}

	public function getComponents()
	{
		return $this->_components;
	}

	public function hasComponent($id)
	{
		return isset($this->_components[$id]);
	}

	public function setController(\CBaseController $controller)
	{
		$this->_controller = $controller;

		foreach ($this->getComponents() as $component)
		{
			$component->setController($controller);
		}
	}
}