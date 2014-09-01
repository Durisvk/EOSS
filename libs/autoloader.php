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
	$eosscontainer=array();
	$eossdir=array();
	$apploader = new AppLoader;
	$apploader->eossInit($eosscontainer);
	include "RequireFactory.php";
?>
	