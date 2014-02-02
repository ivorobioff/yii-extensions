<?php
namespace Extensions\WidgetBuilder;

class Component extends \CBaseController
{
	protected $_id;
	protected $_view;
	protected $_title;

	protected $_controller;

	public function __construct($view, $id = null, $title = null)
	{
		$this->_view = $view;
		$this->_id = $id;
		$this->_title = $title;
	}

	public function setId($id)
	{
		$this->_id = $id;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
	}

	public function getTitle()
	{
		return $this->_title;
	}

	public function setController(\CBaseController $controller)
	{
		$this->_controller = $controller;
		return $this;
	}

	public function getController()
	{
		return $this->_controller;
	}

	public function getViewFile($view)
	{
		return $this->_controller->getViewFile($view);
	}

	public function render($return_output = false)
	{
		return $this->renderView($this->_view, null, $return_output);
	}

	public function renderView($view, $data = null, $return_output = false)
	{
		return $this->renderFile($this->getViewFile($view), $data, $return_output);
	}
}