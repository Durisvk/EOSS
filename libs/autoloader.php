<?php
	function __autoload($class_name) {
		if(file_exists($class_name.'.php')) {
			include $class_name.'.php';
		} else {
			$dirs = array_filter(glob(DIR_LIBS.'*'), 'is_dir');
			foreach ($dirs as $dir) {
				if(file_exists($dir . '/' . $class_name . '.php')) {
					include $dir . '/' . $class_name . '.php';
				}
			}
		}
	}
	set_error_handler ("showLinda");
	register_shutdown_function('shutdown');
	ini_set( "display_errors", "off" );
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	function showLinda ($errno,$errstr,$errfile,$errline){
		require_once DIR_LIBS."/linda/Linda.php";
		Linda::outputLindaForPHPError($errstr,$errfile,$errline);
		exit();
	}
	function shutdown() {
		$error=error_get_last();
		require_once DIR_LIBS."/linda/Linda.php";
		Linda::outputLindaForPHPError($error['message'],$error['file'],$error['line']);
		exit();
	}
	$eosscontainer=array();
	$eossdir=array();
	$apploader = new AppLoader;
	$apploader->eossInit();
	include "RequireFactory.php";
?>
	