<?php

namespace Frigg\Core\Components;

defined('APP_TOKEN') or die('This file can not be called directly');

class HttpComponent extends BaseComponent
{
	public function setPost($key, $value)
	{
		$_POST[$key] = $value;
	}

	public function getPost($key = false)
	{
		if(!$key) {
			return $_POST;
		}

		return $_POST[$key];
	}

	public function setGet($key, $value)
	{
		$_GET[$key] = $value;
	}

	public function getGet($key = false)
	{
		if(!$key) {
			return $_GET;
		}
		
		return $_GET[$key];
	}

	public function getRequest($key = false)
	{
		if(!$key) {
			return $_REQUEST;
		}
		
		return $_REQUEST[$key];
	}

	public function redirect($target)
	{
		header(sprintf('Location: %s', $target));
	}
}
