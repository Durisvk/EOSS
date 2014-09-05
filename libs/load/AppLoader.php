<?php
	class AppLoader {
		public $app=array();
		public function __construct() {
			$this->goThroughAppFiles(DIR_APP);
		}
		public function eossInit($eossreq=null) {

			$matches=preg_grep("/([a-zA-Z])+EOSS\.php/",$this->app);
			foreach($matches as $match) {
				$cls=explode('/',$match);
				$cls=$cls[count($cls)-1];
				$cls=explode('.',$cls);
				$cls=$cls[0];
				if(isset($eossreq) && $eossreq==$cls) {
					include $match;
					$rf = file_get_contents ($match);
					preg_match("/setFile\((.*)\)/",$rf,$matches);
					preg_match("/\(\"(.*)\"\)/",$matches[0],$matches);
					include DIR_APP.Config::getParam("layout_dir").$matches[1];
					$eoss=new $cls;
					return $eoss;
				} else {
					if(isset($_COOKIE['curEOSS'])) {
						if($cls==$_COOKIE['curEOSS']) {
							include $match;
							$rf = file_get_contents ($match);
							preg_match("/setFile\((.*)\)/",$rf,$matches);
							preg_match("/\(\"(.*)\"\)/",$matches[0],$matches);
							include DIR_APP.Config::getParam("layout_dir").$matches[1];
							$eoss=new $cls;
						}
					} else if(Config::getParam("home_eoss")==$cls) {
							include $match;
							$rf = file_get_contents ($match);
							preg_match("/setFile\((.*)\)/",$rf,$matches);
							preg_match("/\(\"(.*)\"\)/",$matches[0],$matches);
							include DIR_APP.Config::getParam("layout_dir").$matches[1];
							$eoss=new $cls;
					}
				}
			}
		}
		private function goThroughAppFiles($dir) {
			$dirHandle=opendir($dir);
			while($file = readdir($dirHandle)){
				if(is_dir(DIR_APP . $file) && $file != '.' && $file != '..'){
				   $this->goThroughAppFiles($dir.$file."/");
				}
				else if($file!='.' && $file!='..') {
				  array_push($this->app,$dir.$file);
				}
			}
		}
	}
?>