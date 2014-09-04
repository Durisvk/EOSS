<?php
	define('DIR_LIBS', getcwd().'/libs/');
	define('DIR_APP', getcwd().'/app/');
	define('DIR_DATA', getcwd().'/libs/data/');
	try {
	require 'libs/autoloader.php';
	} catch (Linda $e) {
		$e->errorMessage;
	}