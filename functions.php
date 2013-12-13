<?php
function pre($str)
{
	echo '<pre>';
	print_r($str);
	echo '</pre>';
}

function pred($str)
{
	pre($str);
	die();
}

function vre($str)
{
	echo '<pre>';
	var_dump($str);
	echo '</pre>';
}

function vred($str)
{
	vre($str);
	die();
}

function setif($array, $key, $default = null)
{
	return isset($array[$key]) ? $array[$key] : $default;
}

function __(Closure $func)
{
	return $func();
}