<?php
namespace Extensions\AuthorizeNet;

class SDKLoader
{
	static public function loadClasses()
	{
		require_once __DIR__.'/sdk/AuthorizeNet.php';
	}
}