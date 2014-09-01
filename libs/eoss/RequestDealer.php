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
	
	define('DIR_LIBS',"../../libs/");
	define('DIR_APP',"../../app/");
	define('DIR_DATA',"../../libs/data/");
	include DIR_APP . $_GET['eoss'] . ".php";
	$eoss=new $_GET['eoss'];
	ModuleGlobal::setClassVariables($eoss);
	foreach(json_decode($_GET['values']) as $value) {
		$id=$value->id;
		$eoss->csi->$id->value=$value->val;
	}
	$eoss->bind();
	
	//DO FUNCTION...
	$bind_event=$eoss->csi->$_GET['id']->$_GET['event'];

	$eoss->$bind_event();
	ModuleGlobal::writeJsResponse($eoss,$_GET['id'].$_GET['event']);
	//...and then
	ModuleGlobal::getClassVariables($eoss);
	