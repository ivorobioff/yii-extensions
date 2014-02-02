<?php
namespace Extensions\WidgetBuilder;

use Extensions\WidgetBuilder\Component;

class ControllerHelper extends \CBehavior
{
	private $_widgets = array();

	public function attachWidget(Component $component)
	{
		$component->setController($this->getOwner());
		$this->_widgets[$component->getId()] = $component;
	}

	public function renderWidget($id, $return_output = false)
	{
		$this->_widgets[$id]->render($return_output);
	}
}